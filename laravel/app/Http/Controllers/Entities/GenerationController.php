<?php

namespace App\Http\Controllers\Entities;

use App\Contracts\HasRepresentationRoute;
use App\Http\Requests\CreateGenerationRequest;
use App\Http\Requests\ManyIdsRequest;
use App\Http\Requests\UpdateGenerationRequest;
use App\Models\Generation;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;

class GenerationController extends Controller implements HasRepresentationRoute
{
    use HandlesPagination, HandlesFilter;

    
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

        $dateFilters = ['created_at'];
        $this->handleDateFilter($query, $filter, $dateFilters);


        $numberFilters = ['year'];
        $this->handleNumberFilter($query, $filter, $numberFilters);

        // Direct filters
        $directFilters = [];
        $this->handleDirectFilter($query, $filter, $directFilters);


        $res = $this->paginateQuery($query, $request->all());

        return $res['output'];
    }


    public function store(CreateGenerationRequest $request)
    {
        $validated = $request->validated();

        $builder = Generation::query();

        $generation = $builder->create($validated);

        return $generation;

    }


    public function representation(string $generation)
    {
        return Generation::whereKey($generation)->firstOrFail()->getAttribute('title');
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

    public function destroy(Generation $generation)
    {
        $generation->delete();
        return $generation;
    }

    /**
     * Delete multiple records at once.
     */
    public function destroyMany(ManyIdsRequest $request)
    {
        $ids = $request->validated()['ids'];

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
