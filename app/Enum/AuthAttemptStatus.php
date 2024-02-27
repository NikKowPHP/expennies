<?php

declare(strict_types = 1);

namespace App\Enum;

enum AuthAttemptStatus: string
{
    case FAILED = 'failed';
    case TWO_FACTOR_AUTH= 'two_factor_auth';
    case SUCCESS = 'success';
   
}
