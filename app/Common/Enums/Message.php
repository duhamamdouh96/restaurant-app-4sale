<?php

namespace App\Common\Enums;

enum Message
{
    const WRONG_CREDENTIALS = 'credentialsNotCorrect';
    const REGISTER_ERROR = 'registerError';
    const LOGUT = 'successLogout';
    const NOT_ALLOWED = 'notAllowed';
}
