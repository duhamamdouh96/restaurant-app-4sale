<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Meal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function orders() : HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function scopeAvailable($query)
    {
        $remaminigMealsCount = $this->orders()->today()->count();

        return $query->where('available_quantity', '>=', $remaminigMealsCount);
    }

    public function validate(array $mealsIds) : array
    {
        $mealsNotAvailable = [];
        $meals = $this->whereIn('id', $mealsIds)->get();

        foreach($meals as $meal) {
            if(!$meal->isAvailable()) {
                $mealsNotAvailable[] = $meal;
            }
        }

        return [
            'status' => $meals->count() == count($mealsIds),
            'mealsNotAvailaible' => $mealsNotAvailable
        ];
    }

    public function isAvailable() : bool
    {
        return $this->available_quantity >= $this->orders()->today()->count();
    }

    public function getByIds($mealsIds) : Collection
    {
        return $this->whereIn('id', $mealsIds)->get();
    }

    public function calculatePriceAfterDiscount() : float
    {
        return (float) $this->price - (float) $this->discount;
    }
}
