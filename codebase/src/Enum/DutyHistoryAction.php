<?php

namespace App\Enum;

final class DutyHistoryAction
{
    const ACTION_SET = 'set';
    const ACTION_UNSET = 'unset';

    public static function getActions(): array
    {
        return [
            self::ACTION_SET,
            self::ACTION_UNSET,
        ];
    }
}
