<?php

namespace App\Models;

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
        return $query->whereHasCapacity($guestsCount)
            ->whereDoesnotHaveReservations($query, $date, $from, $to);
    }

    public function scopeWhereHasCapacity($query, int $guestsCount) : Builder
    {
        return $query->where('capacity', '>=', $guestsCount);
    }

    public function scopeWhereDoesnotHaveReservations($query, string $date, string $from, string $to) : Builder
    {
        return $query->whereNotIn(
            'id',
            (new Reservation)->available($date, $from, $to)->pluck('table_id')->toArray()
        );
    }
}
