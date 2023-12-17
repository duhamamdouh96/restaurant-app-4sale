<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
