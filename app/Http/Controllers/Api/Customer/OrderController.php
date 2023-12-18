<?php

namespace App\Http\Controllers\Api\Customer;

use App\Common\Enums\Message;
use App\Common\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Meal;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $order;
    public $response;
    public $meal;

    public function __construct(Response $response, Order $order, Meal $meal)
    {
        $this->order = $order;
        $this->response = $response;
        $this->meal = $meal;
    }

    public function store(StoreOrderRequest $request)
    {
        $validateMeals = $this->meal->validate($request->meals);
        if(!$validateMeals['status']) {
            return $this->response->error(Message::MEALS_NOT_AVAILABLE, $validateMeals['mealsNotAvailable']);
        }

        return (new OrderResource(
            $this->order->store($request->reservation_id, $request->table_id, $request->meals, auth()->id())
        ));
    }
}
