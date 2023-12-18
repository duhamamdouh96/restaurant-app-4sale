<?php

namespace App\Policies;

use App\Exceptions\TableNotAuthorizedException;
use App\Models\Customer;
use App\Models\Table;
use Illuminate\Auth\Access\HandlesAuthorization;

class TablePolicy
{
    use HandlesAuthorization;

    public function own(Customer $customer, Table $table)
    {
        if ($reservation->customer_id != $customer->id) {
            throw new TableNotAuthorizedException();
        }

        return true;
    }
}
