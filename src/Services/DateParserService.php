<?php
/**
 * Created by PhpStorm.
 * User: rafsan
 * Date: 1/2/22
 * Time: 6:31 AM
 */

namespace App\Services;


use Carbon\Carbon;

class DateParserService
{
    public function parseDate(string $dateRange) : array {
        $dateRangeParse = explode("-", $dateRange);

        return [
            'from' => Carbon::parse($dateRangeParse[0]),
            'to'   => Carbon::parse($dateRangeParse[1])
        ];
    }
}