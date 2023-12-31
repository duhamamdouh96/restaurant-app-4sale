<?php

namespace App\Http\Controllers\Api\Waiter\Auth;

use App\Common\Enums\Message;
use App\Common\Response;
use App\Exceptions\CredentialsNotCorrectException;
use App\Exceptions\RegisterException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WaiterLoginRequest;
use App\Http\Requests\WaiterRegisterRequest;
use App\Http\Resources\WaiterResource;
use App\Models\User;

class AuthController extends Controller
{
    public $waiter;
    public $response;

    public function __construct(User $waiter, Response $response)
    {
        $this->waiter = $waiter;
        $this->response = $response;
    }

    public function register(WaiterRegisterRequest $request)
    {
        try {
            $waiter = $this->waiter->register($request->name, $request->email, $request->password, $request->phone);

            return (new WaiterResource($waiter))
                ->additional(['token' => $waiter->createToken('waiterApiToken')->plainTextToken])
                ->response()
                ->setStatusCode(200);
        } catch (RegisterException $exception) {
            return $exception->render();
        }
    }

    public function login(WaiterLoginRequest $request)
    {
        try {
            $waiter = $this->waiter->login($request->email, $request->password);

            return (new WaiterResource($waiter))
                ->additional(['token' => $waiter->createToken('waiterApiToken')->plainTextToken])
                ->response()
                ->setStatusCode(200);
        } catch (CredentialsNotCorrectException $exception) {
            return $exception->render();
        }
    }

    public function logout()
    {
        auth()->guard('api')->user()->tokens()->delete();

        return $this->response->success([], Message::LOGUT);
    }
}
