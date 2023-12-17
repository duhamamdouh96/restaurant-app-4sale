<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
