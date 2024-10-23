<?php

namespace App\Traits;

use App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;

trait HandlesPagination
{
    /**
     * @return  array{output: array, pagination: LengthAwarePaginator}
     */

    public function paginateQuery(Builder $query, array $requestParams, array $defaultOrder = ['created_at' => 'desc'])
    {
        // Extract pagination parameters
        $perPage = $requestParams['per_page'] ?? 10;
        $page = $requestParams['page'] ?? 1;

        // Extract ordering parameters
        $orderBy = $requestParams['order_by'] ?? array_key_first($defaultOrder);
        $order = $requestParams['order'] ?? $defaultOrder[$orderBy];

        // Apply ordering to the query
        $query->orderBy($orderBy, $order);

        if (App::environment('local')) {
            $sqlQuery = vsprintf(str_replace('?', '"%s"', $query->toSql()), $query->getBindings());
            Log::info($sqlQuery); // Log the query for debugging
        }

        // Paginate the results
        $pagination = $query->paginate($perPage, ['*'], 'page', $page);
        $output = [
            'data' => $pagination->items(),
            'params' => [

                'from' => $pagination->firstItem(),
                'to' => $pagination->lastItem(),
                'total' => $pagination->total()
            ]
        ];
        return ['output' => $output, 'pagination' => $pagination];
    }
}
