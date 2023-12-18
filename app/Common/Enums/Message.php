<?php

namespace App\Common\Enums;

enum Message
{
    const WRONG_CREDENTIALS = 'credentialsNotCorrect';
    const REGISTER_ERROR = 'registerError';
    const LOGUT = 'successLogout';
    const NOT_ALLOWED = 'notAllowed';
    const TABLE_NOT_FOUND_ERROR = 'tableNotFound';
    const RESERVATION_NOT_AVAILAIBLE = 'reservationNotAvailaible';
    const TABLE_CAPACITY_NOT_AVAILAIBLE = 'tableCapacityNotAvailaible';
    const RESERVATION_AVAILAIBLE = 'reservationIsAvailaible';
    const RESERVATION_NOT_AUTHORIZED = 'reservationNotAuthorized';
    const TABLE_NOT_AUTHORIZED = 'tableNotAuthorized';
    const MEALS_NOT_AVAILABLE = 'mealsNotAvailable';
}
