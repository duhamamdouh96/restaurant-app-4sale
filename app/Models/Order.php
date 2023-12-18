<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['table', 'reservation', 'customer', 'waiter'];

    public function table() : BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function waiter() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reservation() : BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function details() : HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function store($reservationId, $tableId, $mealsIds, $customerId)
    {
        $order = $this->create([
            'reservation_id' => $reservationId,
            'table_id' => $tableId,
            'customer_id' => $customerId,
            'user_id' => User::first()->id ?? null
        ]);

        $order->attachMeals($mealsIds);
        $order->calculateTotal();

        return $order;
    }

    public function attachMeals(array $mealsIds)
    {
        $meals = (new Meal)->getByIds($mealsIds);

        return $meals->map(function($meal) {
            (new OrderDetail)->store($this->id, $meal);
        });
    }

    public function calculateTotal()
    {
        return $this->update(['total' => $this->details()->pluck('amount_to_pay')->sum()]);
    }
}
