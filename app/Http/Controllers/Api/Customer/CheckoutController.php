<?php

namespace App\Http\Controllers\Api\Customer;

use App\Common\Response;
use App\Exceptions\CheckoutException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Common\Services\CheckoutService;
use Exception;

class CheckoutController extends Controller
{
    public $order;
    public $response;
    public $meal;
    public $checkoutService;

    public function __construct(Response $response, Order $order, CheckoutService $checkoutService)
    {
        $this->order = $order;
        $this->response = $response;
        $this->checkoutService = $checkoutService;
    }

    public function index(CheckoutRequest $request)
    {
        try {
            $order = Order::findOrFail($request->order_id);

            $this->checkoutService->checkout($request->checkout_method, $order);

            // print invoice
            return (new OrderResource($order->refresh()));
        } catch(Exception $exception) {
            throw new CheckoutException();
        }

    }
}
