<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

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

    public function store(Table $table, int $guestsCount, string $date, string $from, string $to) : self
    {
        return $this->create([
            'table_id' => $table->id,
            'from_date_time' => Carbon::parse($date. ' ' .$from)->format('Y-m-d H:i:s'),
            'to_date_time' => Carbon::parse($date. ' ' .$to)->format('Y-m-d H:i:s'),
            'customer_id' => auth()->id(),
            'guests_count' => $guestsCount,
        ]);
    }

    public function scopeAvailable($query, string $fromDateTime, string $toDateTime) : Builder
    {
        return $query->join('reservations as res', function ($join) {
            $join->on('tables.id', '=', 'res.table_id');
        })->select('tables.id', 'tables.is_available')->groupBy('tables.id')
            ->havingRaw("MAX(res.to_date_time) <= '$fromDateTime' OR MIN(res.from_date_time) >= '$toDateTime' OR tables.is_available = 1");
    }
}
