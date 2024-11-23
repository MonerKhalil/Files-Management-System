<?php

namespace App\Helpers\Traits;

use App\Helpers\ClassesBase\BaseRequest;
use App\Helpers\MyApp;
use Illuminate\Pagination\LengthAwarePaginator;

trait TPaginationData
{
    private int $DEFAULT_PAGES_Count = 20;

    /**
     * @param $queryBuilder
     * @return mixed
     * @author moner khalil
     */
    private function dataPaginate($queryBuilder): mixed
    {
        $tempCount = $this->countItemsPaginate();

        if ($tempCount === "all"){
            $data = $queryBuilder->get();
        }else{
            $data = $queryBuilder->paginate($tempCount);
        }
        return $data;
    }

    /**
     * @return int|string
     * @author moner khalil
     */
    private function countItemsPaginate(): int|string
    {
        if ( isset(request()->countItems) &&
            (
                (is_numeric(request()->countItems) && request()->countItems >= 1)
                ||
                (request()->countItems == 'all')
            )
        ){
            return request()->countItems;
        }

        return $this->DEFAULT_PAGES_Count;
    }

    /**
     * @description check if is api => handle data response
     * @param mixed $dataCollection
     * @return array
     * @author moner khalil
     */
    private function handleResponsePaginationData(mixed $dataCollection)
    {
        if ($dataCollection instanceof LengthAwarePaginator && BaseRequest::urlIsApi()){
            $allQueryParams = request()->all();
            $paginate = $dataCollection->appends($allQueryParams);
            return [
                "items" => $paginate->items(),
                "current_page" => $paginate->currentPage(),
                "url_next_page" => $paginate->nextPageUrl(),
                "url_pre_page" => $paginate->previousPageUrl(),
                "url_first_page" => $paginate->url(1),
                "url_last_page" => $paginate->url($paginate->lastPage()),
                "total_pages" => $paginate->lastPage(),
                "total_items" => $paginate->total(),
                "has_more_pages" => $paginate->hasMorePages(),
            ];
        }
        return $dataCollection;
    }

}
