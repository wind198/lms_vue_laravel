<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGenerationRequest;
use App\Http\Requests\UpdateGenerationRequest;
use App\Models\Generation;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;

class GenerationsController extends Controller
{
    use HandlesPagination, HandlesFilter;
    public const INDEX_ROUTE = 'generations';
    public const SHOW_ROUTE = self::INDEX_ROUTE . '.show';
    public const CREATE_ROUTE = self::INDEX_ROUTE . '.create';
    public const UPDATE_ROUTE = self::INDEX_ROUTE . '.update';
    public const UPDATE_MANY_ROUTE = self::INDEX_ROUTE . '.update-many';
    public const DELETE_ROUTE = self::INDEX_ROUTE . '.delete';
    public const DELETE_MANY_ROUTE = self::INDEX_ROUTE . '.delete-many';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = Generation::query()->withCount('students');

        // Text search filter
        if (!empty($filter['q'])) {
            $query->where(function ($q) use ($filter) {
                $q->where('title', 'like', "%{$filter['q']}%");
            });
        }

        // Date range filters

        $dateFilters = ['created_at'];
        $this->handleDateFilter($query, $filter, $dateFilters);


        // Direct filters
        // $directFilters = [];


        $res = $this->paginateQuery($query, $request->all());

        return $res['output'];
    }


    public function create(CreateGenerationRequest $request)
    {
        $validated = $request->validated();

        $builder = Generation::query();

        $generation = $builder->create($validated);

        return $generation;

    }


    /**
     * Display the specified resource.
     */
    public function show(Generation $generation)
    {
        return $generation;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Generation $generation, UpdateGenerationRequest $request)
    {

        // Validate the request data
        $validated = $request->validated();

        // Update the generation's details
        $generation->update($validated);

        return $generation;
    }

    public function delete(Generation $generation)
    {
        $generation->delete();
        return $generation;
    }

    /**
     * Delete multiple records at once.
     */
    public function deleteMany(Request $request)
    {
        // Retrieve the array of generation IDs from the request
        $ids = $request->input('ids', []);

        if (!is_array($ids)) {
            return response()->json(['message' => trans('validation.array', ['attribute' => 'ids'])], 400);

        }
        if (empty($ids)) {
            return response()->json(['message' => trans('validation.empty', ['attribute' => 'ids'])], 400);
        }
        // Perform the deletion of the generations
        $deleted = Generation::whereIn('id', $ids)
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        Generation::whereIn('id', $deleted)->delete();

        return $deleted;
    }
}
