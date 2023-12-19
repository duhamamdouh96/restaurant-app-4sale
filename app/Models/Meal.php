<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class Meal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function orders() : HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function scopeAvailable($query) : Builder
    {
        return $query->where('available_quantity', '>', 0)
            ->where(function ($q) {
                $q->whereDoesntHave('orders')->orWhereHas('orders', function ($order) {
                    $order->join('order_details as details', function ($join) {
                        $join->on('meals.id', '=', 'details.meal_id');
                    })
                ->select('meals.id', 'meals.available_quantity')
                ->groupBy('meals.id', 'meals.available_quantity')
                ->havingRaw('meals.available_quantity > COUNT(CASE WHEN DATE(details.created_at) = CURDATE() THEN details.meal_id END)');
            });
        });
    }

    public function validate(array $mealsIds) : array
    {
        $isValid = true;
        $mealsNotAvailable = [];
        $meals = $this->whereIn('id', $mealsIds)->get();

        foreach($meals as $meal) {
            if(!$meal->isAvailable()) {
                $mealsNotAvailable[] = $meal;
                $isValid = false;
            }
        }

        return [
            'status' => $isValid,
            'mealsNotAvailable' => $mealsNotAvailable
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
