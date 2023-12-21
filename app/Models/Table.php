<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Table extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function reservation() : HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeAvailable($query, int $guestsCount, string $date, string $from, string $to) : Builder
    {
        return $query->whereHasCapacity($guestsCount)->whereDoesnotHaveReservations($date, $from, $to);
    }

    public function scopeWhereHasCapacity($query, int $guestsCount) : Builder
    {
        return $query->where('capacity', '>=', $guestsCount);
    }

    public function scopeWhereDoesnotHaveReservations($query, string $date, string $from, string $to) : Builder
    {
        $fromDateTime = Carbon::parse($date. ' ' .$from)->format('Y-m-d H:i:s');
        $toDateTime = Carbon::parse($date. ' ' .$to)->format('Y-m-d H:i:s');

        return $query->where(function ($q) use ($fromDateTime, $toDateTime) {
            $q->whereDoesntHave('reservation')->orWhereHas('reservation', function ($reservation) use ($fromDateTime, $toDateTime) {
                    $reservation->available($fromDateTime, $toDateTime);
                });
            });
    }

    public function waitingList() : HasMany
    {
        return $this->hasMany(WaitingList::class);
    }

    public function updateAvailabilty($isAvailable) : bool
    {
        return $this->update(['is_available' => $isAvailable]);
    }
}
