<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCourseMarkRequest;
use App\Http\Requests\ManyIdsRequest;
use App\Models\CourseMark;
use App\Http\Requests\StoreCourseMarkRequest;
use App\Http\Requests\UpdateCourseMarkRequest;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use Request;

class CourseMarkController
{
    use HandlesPagination, HandlesFilter;


    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = CourseMark::query()->withCount('students');

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


    public function store(CreateCourseMarkRequest $request)
    {
        $validated = $request->validated();

        $builder = CourseMark::query();

        $courseMark = $builder->create($validated);

        return $courseMark;

    }


    public function representation(string $courseMark)
    {
        return CourseMark::whereKey($courseMark)->firstOrFail()->getAttribute('title');
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseMark $courseMark)
    {
        return $courseMark;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseMark $courseMark, UpdateCourseMarkRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the courseMark's details
        $courseMark->update($validated);

        return $courseMark;
    }

    public function destroy(CourseMark $courseMark)
    {
        $courseMark->delete();
        return $courseMark;
    }

    /**
     * Delete multiple records at once.
     */
    public function destroyMany(ManyIdsRequest $request)
    {
        $ids = $request->validated()['ids'];

        // Perform the deletion of the courseMarks
        $deleted = CourseMark::whereIn('id', $ids)
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        CourseMark::whereIn('id', $deleted)->delete();

        return $deleted;
    }
}
