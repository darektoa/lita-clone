<?php

namespace App\Helpers;

use Carbon\{CarbonPeriod};

class DateHelper{
    static public function range($start, $end, $format='d M y') {
        $dates  = [];
        $period = CarbonPeriod::create($start, $end);

        foreach($period as $item)
            $dates[] = $item->format($format);

        return $dates;
    }
}