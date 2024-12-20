<?php

namespace App\Http\Controllers\Entities;

use App\Http\Requests\CreateCourseConfigRequest;
use App\Http\Requests\ManyIdsRequest;
use App\Http\Requests\UpdateCourseConfigRequest;
use App\Models\Course;
use App\Models\CourseConfig;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use DB;
use Illuminate\Http\Request;

class CourseConfigController extends Controller
{
    use HandlesPagination, HandlesFilter;


    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = CourseConfig::query()->with
        ([
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


    public function store(CreateCourseConfigRequest $request)
    {
        $validated = $request->validated();

        $builder = CourseConfig::query();

        $major = $builder->create($validated);

        return $major;

    }


    public function representation(string $courseConfig)
    {
        return CourseConfig::whereKey($courseConfig)->firstOrFail()->getAttribute('title');
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseConfig $major)
    {
        return $major;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseConfig $courseConfig, UpdateCourseConfigRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the major's details
        $courseConfig->update($validated);

        return $courseConfig;
    }

    public function updateMany(UpdateCourseConfigRequest $request)
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
            CourseConfig::whereIn('id', $ids)
                ->update($updatePayload);

            // Commit the transaction
            DB::commit();

            return response()->json(['updated_ids' => $ids, 'message' => 'Course config updated successfully.']);
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollBack();
            return response()->json(['error' => 'Failed to update course config', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy(CourseConfig $courseConfig)
    {
        $courseConfig->delete();
        return $courseConfig;
    }

    /**
     * Delete multiple records at once.
     */
    public function destroyMany(ManyIdsRequest $request)
    {
        $ids = $request->validated()['ids'];

        // Perform the deletion of the majors
        $deleted = CourseConfig::whereIn('id', $ids)
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        CourseConfig::whereIn('id', $deleted)->delete();

        return $deleted;
    }
}
