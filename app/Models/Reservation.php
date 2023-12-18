<?php

namespace App\Models;

use App\Exceptions\ReseravtionNotAvailableException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    public function store(Table $table, int $guestsCount, string $date, string $from, string $to)
    {
        return $this->create([
            'table_id' => $table->id,
            'from_date_time' => Carbon::parse($date. ' ' .$from)->format('Y-m-d H:i:s'),
            'to_date_time' => Carbon::parse($date. ' ' .$to)->format('Y-m-d H:i:s'),
            'customer_id' => auth()->id(),
            'guests_count' => $guestsCount
        ]);
    }

    public function scopeAvailable($query, $date, $from, $to)
    {
        // fix where between dates here
        return $query->whereBetween('from_date_time', [
            Carbon::parse($date. ' ' .$from)->format('Y-m-d H:i:s'),
            Carbon::parse($date. ' ' .$to)->format('Y-m-d H:i:s')
        ])->whereBetween('to_date_time', [
            Carbon::parse($date. ' ' .$from)->format('Y-m-d H:i:s'),
            Carbon::parse($date. ' ' .$to)->format('Y-m-d H:i:s')
        ]);
    }
}
