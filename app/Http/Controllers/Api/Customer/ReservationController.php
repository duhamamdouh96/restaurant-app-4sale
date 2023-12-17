<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckAvailabiltyRequest;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function checkAvailability(CheckAvailabiltyRequest $request)
    {


        // Check if there is any reservation for the given table and datetime
        // $existingReservation = Reservation::where('table_id', $request->tableId)
        //     ->where('from', '<=', $request->datetime)
        //     ->where('to', '>', $request->datetime)
        //     ->first();


    }
}
