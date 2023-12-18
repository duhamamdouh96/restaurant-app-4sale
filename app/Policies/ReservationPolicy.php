<?php

namespace App\Policies;

use App\Exceptions\ReseravtionNotAuthorizedException;
use App\Models\Customer;
use App\Models\Reservation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization;

    public function own(Customer $customer, Reservation $reservation)
    {
        if ($reservation->customer_id != $customer->id) {
            throw new ReseravtionNotAuthorizedException();
        }

        return true;
    }
}
