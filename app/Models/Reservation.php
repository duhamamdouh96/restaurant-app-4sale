<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['table', 'customer'];

    public function table() : BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
