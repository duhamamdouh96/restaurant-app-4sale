<?php

namespace App\Exceptions;

use App\Common\Enums\Message;
use App\Common\Response;
use Exception;

class ReseravtionNotAvailableException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        return false;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return (new Response)->exception(Message::RESERVATION_NOT_AVAILAIBLE);
    }
}
