<?php

namespace App\Helpers;

use Illuminate\Pagination\{LengthAwarePaginator, Paginator};
use Illuminate\Support\Collection;

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
        
        if(!($items instanceof Collection))
            $items = Collection::make($items);
        
        return new LengthAwarePaginator(
            $items->skip(($page-1) * $perPage)->take($perPage),
            $items->count(),
            $perPage,
            $page,
            $options,
        );
    }
}