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

class ReservationController extends Controller
{
    public $reservation;
    public $table;
    public $response;

    public function __construct(Reservation $reservation, Table $table, Response $response)
    {
        $this->reservation = $reservation;
        $this->table = $table;
        $this->response = $response;
    }

    public function checkAvailability(CheckAvailabiltyRequest $request)
    {
        $availabileTables = $this->table
            ->whereHasCapacity($request->guests_count)
            ->whereDoesnotHaveReservations($request->date, $request->from, $request->to)->get();

        if($availabileTables->isEmpty()) {
            return $this->response->error(Message::RESERVATION_NOT_AVAILAIBLE);
        }

        return TableResource::collection($availabileTables->get());
    }

    public function store(StoreReservationRequest $request)
    {
        $availabileTables = $this->table->whereHasCapacity($request->guests_count);

        if($availabileTables->count() <= 0) {
            return $this->response->error(Message::TABLE_CAPACITY_NOT_AVAILAIBLE);
        }

        $availabileTables = $availabileTables->whereDoesnotHaveReservations($request->date, $request->from, $request->to);

        if($availabileTables->count() <= 0) {
            return $this->response->error(Message::RESERVATION_NOT_AVAILAIBLE);
        }

        $reservation = $this->reservation->store(
            $availabileTables->first(),
            $request->guests_count,
            $request->date,
            $request->from,
            $request->to
        );

        return new ReservationResource($reservation);
    }
}
