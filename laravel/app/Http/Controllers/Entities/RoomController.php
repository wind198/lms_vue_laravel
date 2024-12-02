<?php

namespace App\Http\Controllers\Entities;

use App\Http\Requests\CreateRoomRequest;
use App\Http\Requests\ManyIdsRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    use HandlesPagination, HandlesFilter;


    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = Room::query();

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


    public function store(CreateRoomRequest $request)
    {
        $validated = $request->validated();

        $builder = Room::query();

        $room = $builder->create($validated);

        return $room;

    }


    public function representation(string $room)
    {
        return Room::whereKey($room)->firstOrFail()->getAttribute('title');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return $room;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Room $room, UpdateRoomRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the room's details
        $room->update($validated);

        return $room;
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return $room;
    }

    /**
     * Delete multiple records at once.
     */
    public function destroyMany(ManyIdsRequest $request)
    {
        $ids = $request->validated()['ids'];

        // Perform the deletion of the rooms
        $deleted = Room::whereIn('id', $ids)
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        Room::whereIn('id', $deleted)->delete();

        return $deleted;
    }
}
