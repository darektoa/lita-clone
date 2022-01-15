<?php

namespace App\Helpers;

use Illuminate\Pagination\{LengthAwarePaginator, Paginator};

class CollectionHelper{
    static public function paginate(
        $items,
        int $perPage=15,
        string $pageName='page',
        int $page=null
    ) {
        $page       = $page ?: Paginator::resolveCurrentPage();
        $options    = [
            'path'      => Paginator::resolveCurrentPath(),
            'pageName'  => $pageName,
        ];
        
        return new LengthAwarePaginator(
            $items,
            $items->count(),
            $perPage,
            $page,
            $options,
        );
    }
}