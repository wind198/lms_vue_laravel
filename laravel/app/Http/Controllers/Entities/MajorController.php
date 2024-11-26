<?php

namespace App\Http\Controllers\Entities;

use App\Contracts\HasRepresentationRoute;
use App\Http\Requests\CreateMajorRequest;
use App\Http\Requests\ManyIdsRequest;
use App\Http\Requests\UpdateMajorRequest;
use App\Models\Major;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;

class MajorController extends Controller implements HasRepresentationRoute
{
    use HandlesPagination, HandlesFilter;


    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = Major::query();

        // Text search filter
        if (!empty($filter['q'])) {
            $query->where(function ($q) use ($filter) {
                $q->where('title', 'like', "%{$filter['q']}%");
            });
        }

        $dateFilters = ['created_at'];
        $this->handleDateFilter($query, $filter, $dateFilters);


        $numberFilters = [];
        $this->handleNumberFilter($query, $filter, $numberFilters);

        // Direct filters
        $directFilters = [];
        $this->handleDirectFilter($query, $filter, $directFilters);


        $res = $this->paginateQuery($query, $request->all());

        return $res['output'];
    }


    public function store(CreateMajorRequest $request)
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

    public function destroy(Major $major)
    {
        $major->delete();
        return $major;
    }

    /**
     * Delete multiple records at once.
     */
    public function destroyMany(ManyIdsRequest $request)
    {
        $ids = $request->validated()['ids'];

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
