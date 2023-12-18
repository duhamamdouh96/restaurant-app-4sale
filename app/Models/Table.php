<?php

namespace App\Models;

use App\Exceptions\TableNotFoundException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Table extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function reservation() : HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function checkCapacity($guestsCount) : bool
    {
        return ($this->isCapacityAvailaible($guestsCount)->get()->isNotEmpty())
            ? true
            : false;
    }

    public function scopeWhereHasCapacity($query, $guestsCount)
    {
        return $query->where('capacity', '>=', $guestsCount);
    }

    public function scopeWhereDoesnotHaveReservations($query, $date, $from, $to)
    {
        return $query->whereNotIn(
            'id',
            (new Reservation)->available($date, $from, $to)->pluck('table_id')->toArray()
        );
    }
}
