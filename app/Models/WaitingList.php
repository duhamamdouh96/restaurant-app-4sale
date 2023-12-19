<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitingList extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['customer'];

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function store()
    {

    }
}
