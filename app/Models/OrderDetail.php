<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class OrderDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['order', 'meal'];

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function meal() : BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    public function scopeToday($query) : Builder
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function store(int $rderId, Meal $meal) : self
    {
        return $this->create([
            'order_id' => $rderId,
            'meal_id' => $meal->id,
            'amount_to_pay' => $meal->calculatePriceAfterDiscount()
        ]);
    }
}
