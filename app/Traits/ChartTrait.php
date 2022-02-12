<?php

namespace App\Traits;

use App\Helpers\DateHelper;
use Carbon\Carbon;

trait ChartTrait{
    public function scopeChartByCreatedAt($query, $startDate, $endDate) {
        $labels = DateHelper::range($startDate, $endDate);
        $model  = $query->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupByRaw('date')
            ->get();

        $data   = collect($labels)->map(function($item) use($model) {
            $date = Carbon::createFromFormat('d M y', $item)->toDateString();
            $data = $model->where('date', $date)->first();
            return $data->total ?? 0;
        });
        
        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }
}