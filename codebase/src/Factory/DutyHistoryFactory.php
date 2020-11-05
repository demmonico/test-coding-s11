<?php

namespace App\Factory;

use App\Entity\Duty;
use App\Entity\DutyHistory;

class DutyHistoryFactory
{
    public function createDutyHistory(Duty $duty, string $action): DutyHistory
    {
        return new DutyHistory($duty, $action);
    }
}
