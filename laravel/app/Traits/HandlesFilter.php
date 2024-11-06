<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HandlesFilter
{
    public function handleDateFilter(Builder $query, array $filter, array $dateFields)
    {
        foreach ($dateFields as $field) {
            if (!empty($filter[$field]['gte'])) {
                $query->where($field, '>=', $filter[$field]['gte']);
            }
            if (!empty($filter[$field]['lte'])) {
                $query->where($field, '<=', $filter[$field]['lte']);
            }
        }

    }
    public function handleDirectFilter(Builder $query, array $filter, array $directFilters)
    {
        foreach ($directFilters as $field) {
            if (!empty($filter[$field])) {
                $query->where($field, $filter[$field]);
            }
        }
    }
}
