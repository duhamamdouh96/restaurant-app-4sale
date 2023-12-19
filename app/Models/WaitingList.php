<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function store(
        int $guestsCount,
        string $date,
        string $to,
        string $from,
        int|null $customerId = null,
    ) : self
    {
        return $this->create([
            'customer_id' => $customerId ?? null,
            'guests_count' => $guestsCount,
            'from_date_time' => Carbon::parse($date. ' ' .$from)->format('Y-m-d H:i:s'),
            'to_date_time' => Carbon::parse($date. ' ' .$to)->format('Y-m-d H:i:s')
        ]);
    }
}
