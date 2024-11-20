<?php

namespace App\Http\Controllers\Entities;

use App\Http\Requests\CreateClassSessionRequest;
use App\Http\Requests\ManyIdsRequest;
use App\Http\Requests\UpdateClassSessionRequest;
use App\Models\ClassSession;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;

class ClassSessionController extends Controller
{
    use HandlesPagination, HandlesFilter;


    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = ClassSession::query()->with([

            'klass' => function ($query) {
                $query->select('id', 'title');
            }

        ]);

        // Text search filter
        if (!empty($filter['q'])) {
            $query->where(function ($q) use ($filter) {
                $q->where('title', 'like', "%{$filter['q']}%");
            });
        }

        $dateFilters = ['created_at', 'start_time', 'end_time'];
        $this->handleDateFilter($query, $filter, $dateFilters);


        $numberFilters = [];
        $this->handleNumberFilter($query, $filter, $numberFilters);

        // Direct filters
        $directFilters = [];
        $this->handleDirectFilter($query, $filter, $directFilters);


        $res = $this->paginateQuery($query, $request->all());

        return $res['output'];
    }


    public function store(CreateClassSessionRequest $request)
    {
        $validated = $request->validated();

        $builder = ClassSession::query();

        $classSession = $builder->create($validated);

        return $classSession;

    }


    public function representation(string $classSession)
    {
        $match = ClassSession::query()->with('klass')->whereKey($classSession)->firstOrFail()->toArray();
        return $match['klass']['title'] . '@' . $match['start_time'];
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassSession $classSession)
    {
        return $classSession;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassSession $classSession, UpdateClassSessionRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the classSession's details
        $classSession->update($validated);

        return $classSession;
    }

    public function destroy(ClassSession $classSession)
    {
        $classSession->delete();
        return $classSession;
    }

    /**
     * Delete multiple records at once.
     */
    public function destroyMany(ManyIdsRequest $request)
    {
        $ids = $request->validated()['ids'];

        // Perform the deletion of the classSessions
        $deleted = ClassSession::whereIn('id', $ids)
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        ClassSession::whereIn('id', $deleted)->delete();

        return $deleted;
    }
}
