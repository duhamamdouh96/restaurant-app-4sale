<?php

namespace App\Http\Controllers\Api\Customer;

use App\Common\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public $meal;
    public $response;

    public function __construct(Response $response, Meal $meal)
    {
        $this->meal = $meal;
        $this->response = $response;
    }

    public function index()
    {
        return MealResource::collection($this->meal->available()->get());
    }
}
