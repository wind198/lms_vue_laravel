<?php

namespace App\Http\Controllers\Entities;

use App\Contracts\HasRepresentationRoute;
use App\Models\Major;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;

class MajorController extends Controller implements HasRepresentationRoute
{
    use HandlesPagination, HandlesFilter;
    public const INDEX_ROUTE = 'majors';
    public const REPRESENTATION_ROUTE = self::INDEX_ROUTE . '.representation';

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

        $query = Major::query()->withCount('students');

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


    public function create(CreateMajorRequest $request)
    {
        $validated = $request->validated();

        $builder = Major::query();

        $major = $builder->create($validated);

        return $major;

    }


    public function representation(string $major)
    {
        return Major::whereKey($major)->firstOrFail()->getAttribute('title');
    }

    /**
     * Display the specified resource.
     */
    public function show(Major $major)
    {
        return $major;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Major $major, UpdateMajorRequest $request)
    {

        // Validate the request data
        $validated = $request->validated();

        // Update the major's details
        $major->update($validated);

        return $major;
    }

    public function delete(Major $major)
    {
        $major->delete();
        return $major;
    }

    /**
     * Delete multiple records at once.
     */
    public function deleteMany(Request $request)
    {
        // Retrieve the array of major IDs from the request
        $ids = $request->input('ids', []);

        if (!is_array($ids)) {
            return response()->json(['message' => trans('validation.array', ['attribute' => 'ids'])], 400);

        }
        if (empty($ids)) {
            return response()->json(['message' => trans('validation.empty', ['attribute' => 'ids'])], 400);
        }
        // Perform the deletion of the majors
        $deleted = Major::whereIn('id', $ids)
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        Major::whereIn('id', $deleted)->delete();

        return $deleted;
    }
}
