<?php

namespace App\Traits;

use App\Helpers\DateHelper;
use Carbon\Carbon;

trait ChartTrait{
    static public function chartByCreatedAt($startDate, $endDate) {
        $labels = DateHelper::range($startDate, $endDate);
        $data   = collect($labels)->map(function($item) {
            $date   = Carbon::createFromFormat('d M y', $item)->toDateString();
            $total  = self::whereDate('created_at', $date)->count();
            return $total;
        });

        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }
}