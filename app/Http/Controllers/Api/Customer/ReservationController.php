<?php

namespace App\Http\Controllers\Api\Customer;

use App\Common\Enums\Message;
use App\Common\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckAvailabiltyRequest;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\TableResource;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\WaitingList;

class ReservationController extends Controller
{
    public $reservation;
    public $table;
    public $response;
    public $waitingList;

    public function __construct(
        Reservation $reservation,
        Table $table,
        Response $response,
        WaitingList $waitingList
    )
    {
        $this->reservation = $reservation;
        $this->table = $table;
        $this->response = $response;
        $this->waitingList = $waitingList;
    }

    public function checkAvailability(CheckAvailabiltyRequest $request)
    {
        $availabileTables = $this->table->available(
            $request->guests_count,
            $request->date,
            $request->from,
            $request->to
        )->get();

        if($availabileTables->isEmpty()) {
            return $this->response->exception(Message::RESERVATION_NOT_AVAILAIBLE);
        }

        return $this->response->success(['available_tables' => TableResource::collection($availabileTables)]);
    }

    public function store(StoreReservationRequest $request)
    {
        $availabileTables = $this->table->available(
            $request->guests_count,
            $request->date,
            $request->from,
            $request->to
        )->get();

        if($availabileTables->isEmpty()) {
            $this->waitingList->store($request->guests_count, $request->date, $request->from, $request->to, auth()->id());

            return $this->response->exception(Message::RESERVATION_NOT_AVAILAIBLE);
        }

        $reservation = $this->reservation->store(
            $availabileTables->first(),
            $request->guests_count,
            $request->date,
            $request->from,
            $request->to
        );

        $reservation->table->updateAvailabilty(false);

        return (new ReservationResource($reservation))->response()->setStatusCode(200);
    }
}
