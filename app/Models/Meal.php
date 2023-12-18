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
        $orderMealsCount = $this->orders()->today()->count();

        return $query->where('available_quantity', '>', 0)->where('available_quantity', '>', $orderMealsCount);
    }

    public function validate(array $mealsIds) : array
    {
        $isValid = true;
        $mealsArray = [];
        $meals = $this->whereIn('id', $mealsIds)->get();

        foreach($meals as $meal) {
            if(!$meal->isAvailable()) {
                $mealsArray[] = $meal;
                $isValid = false;
            }
        }

        return [
            'status' => $isValid,
            'mealsNotAvailable' => $mealsArray
        ];
    }

    public function isAvailable() : bool
    {
        return ($this->available_quantity > 0) && ($this->available_quantity > $this->orders()->today()->count());
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
