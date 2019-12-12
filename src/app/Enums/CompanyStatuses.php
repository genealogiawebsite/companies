<?php

namespace LaravelEnso\Companies\app\Enums;

use LaravelEnso\Enums\app\Services\Enum;

class CompanyStatuses extends Enum
{
    public const Active = 1;
    public const Overdue = 2;
    public const Litigation = 3;
    public const Insolvent = 4;
    public const Deregistered = 5;
}
