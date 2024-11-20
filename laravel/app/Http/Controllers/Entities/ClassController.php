<?php

namespace App\Http\Controllers\Entities;

use App\Http\Requests\CreateClassRequest;
use App\Http\Requests\ManyIdsRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Models\Course;
use App\Models\Klass;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use DB;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    use HandlesPagination, HandlesFilter;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = Klass::query()->with([
            'course' => function ($query) {
                $query->select('id', 'title');
            }
        ]);

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateClassRequest $request)
    {

        $validated = $request->validated();

        $builder = Klass::query();

        $course = $builder->create($validated);

        return $course;


    }

    public function representation(string $klass)
    {
        return Klass::whereKey($klass)->firstOrFail()->getAttribute('title');
    }

    /**
     * Display the specified resource.
     */
    public function show(Klass $klass)
    {
        return $klass;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassRequest $request, Klass $klass)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the course's details
        $klass->update($validated);

        return $klass;
    }

    public function updateMany(UpdateClassRequest $request)
    {
        // Retrieve the array of student IDs and validate the data
        $validated = $request->validated();

        $ids = $validated['ids'];
        // Start a transaction for atomic updates
        DB::beginTransaction();

        try {
            if (
                isset($validated['course_id'])
            ) {
                Course::findOrFail($validated['course_id']);
            }
            $updatePayload = collect($validated)->except(['ids'])->toArray();
            // Bulk update fields that don’t require relationship handling
            Klass::whereIn('id', $ids)
                ->update($updatePayload);

            // Commit the transaction
            DB::commit();

            return response()->json(['updated_ids' => $ids, 'message' => trans('message.success.updated', ['resource' => trans('nouns.class')])]);
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollBack();
            return response()->json(['error' => 'Failed to update classes', 'details' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Klass $klass)
    {
        $klass->delete();
        return $klass;
    }


    public function destroyMany(ManyIdsRequest $request)
    {
        $ids = $request->validated()['ids'];

        // Perform the deletion of the majors
        $deleted = Klass::whereIn('id', $ids)
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        Klass::whereIn('id', $deleted)->delete();

        return $deleted;
    }

}
