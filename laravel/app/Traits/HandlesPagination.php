<?php

namespace App\Traits;

use App;
use Illuminate\Database\Eloquent\Builder;
use Log;

trait HandlesPagination
{
    /**
     * Paginate and order a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $requestParams
     * @param  array  $defaultOrder
     * @return \Illuminate\Pagination\LengthAwarePaginator
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
        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
