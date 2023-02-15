<?php

namespace App\Enums;

class Periodicity
{
    const DAILY = '1';
    const WEEKLY = '2';
    const MONTHLY = '3';

    const PERIODICITY = [
        self::DAILY,
        self::WEEKLY,
        self::MONTHLY
    ];
}