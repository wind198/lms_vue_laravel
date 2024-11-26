<?php

namespace App\Http\Controllers\Entities;

use App\Constants\AppConstants;
use App\Contracts\HasRepresentationRoute;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\ManyIdsRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Major;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use DB;
use Illuminate\Http\Request;

class CourseController extends Controller implements HasRepresentationRoute
{
    use HandlesPagination, HandlesFilter;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = Course::query()->with([
            'major' => function ($query) {
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
    public function store(CreateCourseRequest $request)
    {

        $validated = $request->validated();

        $builder = Course::query();

        $course = $builder->create($validated);

        return $course;


    }

    public function representation(string $course)
    {
        return Course::whereKey($course)->firstOrFail()->getAttribute('title');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $course)
    {
        $output = Course::with([
            'major' => function ($query) {
                $query->select('id', 'title');
            }
        ])->findOrFail($course); //

        return $output;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the course's details
        $course->update($validated);

        return $course;
    }

    public function updateMany(UpdateCourseRequest $request)
    {
        // Retrieve the array of student IDs and validate the data
        $validated = $request->validated();

        $ids = $validated['ids'];
        // Start a transaction for atomic updates
        DB::beginTransaction();

        try {
            if (
                isset($validated['major_id'])
            ) {
                Major::findOrFail($validated['major_id']);
            }
            $updatePayload = collect($validated)->except(['ids'])->toArray();
            // Bulk update fields that don’t require relationship handling
            Course::whereIn('id', $ids)
                ->update($updatePayload);

            // Commit the transaction
            DB::commit();

            return response()->json(['updated_ids' => $ids, 'message' => trans('message.success.updated', ['resource' => trans('nouns.course')])]);
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollBack();
            return response()->json(['error' => 'Failed to update courses', 'details' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return $course;
    }


    public function destroyMany(ManyIdsRequest $request)
    {
        $ids = $request->validated()['ids'];

        // Perform the deletion of the majors
        $deleted = Course::whereIn('id', $ids)
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        Course::whereIn('id', $deleted)->delete();

        return $deleted;
    }

}
