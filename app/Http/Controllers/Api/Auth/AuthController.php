<?php

namespace App\Http\Controllers\Api\Auth;

use App\Common\Enums\Message;
use App\Common\Response;
use App\Exceptions\CredentialsNotCorrectException;
use App\Exceptions\RegisterException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;

class AuthController extends Controller
{
    public $customer;
    public $response;

    public function __construct(Customer $customer, Response $response)
    {
        $this->customer = $customer;
        $this->response = $response;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $customer = $this->customer->register($request->name, $request->email, $request->password, $request->phone);

            return (new CustomerResource($customer))
                ->additional(['token' => $customer->createToken('customerApiToken')->plainTextToken])
                ->response()
                ->setStatusCode(200);
        } catch (RegisterException $exception) {
            return $exception->render();
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $customer = $this->customer->login($request->email, $request->password);

            return (new CustomerResource($customer))
                ->additional(['token' => $customer->createToken('customerApiToken')->plainTextToken])
                ->response()
                ->setStatusCode(200);
        } catch (CredentialsNotCorrectException $exception) {
            return $exception->render();
        }
    }

    public function logout()
    {
        auth()->guard('customer')->user()->tokens()->delete();

        return $this->response->success([], Message::LOGUT);
    }
}
