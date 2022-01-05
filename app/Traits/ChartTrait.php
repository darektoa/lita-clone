<?php

namespace App\Traits;

use App\Helpers\DateHelper;

trait ChartTrait{
    static public function chartByCreatedAt($startDate, $endDate) {
        return [
            'labels' => DateHelper::range($startDate, $endDate),
            'data'   => self::selectRaw('SUBSTR(created_at, 1, 10) as date, COUNT(*) as total ')
                ->groupBy('date')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->pluck('total')
                ->pad($startDate->diffInDays($endDate), 0)
                ->toArray(),
        ];
    }
}