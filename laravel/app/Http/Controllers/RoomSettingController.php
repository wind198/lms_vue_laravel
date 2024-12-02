<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoomSettingRequest;
use App\Models\RoomOpenTime;
use App\Models\RoomSetting;
use App\Models\RoomSettingSetting;
use App\Http\Requests\StoreRoomSettingSettingRequest;
use App\Http\Requests\UpdateRoomSettingSettingRequest;
use App\Traits\HandlesFilter;
use App\Traits\HandlesPagination;
use DB;
use Request;

class RoomSettingController
{
    use HandlesPagination, HandlesFilter;


    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = RoomSetting::query();

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


    public function store(CreateRoomSettingRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $openTimes = $validated['open_times']; // Extract open times array

            $payload = collect($validated)->except('open_times')->toArray();

            // Create the RoomSetting record
            $roomSetting = RoomSetting::create($payload);

            // Bulk insert open_times for the room setting
            $formattedOpenTimes = array_map(function ($openTime) use ($roomSetting) {
                return [
                    'room_setting_id' => $roomSetting->id, // Assuming foreign key
                    'start_time' => $openTime['start_time'],
                    'end_time' => $openTime['end_time'],
                    'days' => json_encode($openTime['days']), // Store as JSON if needed
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $openTimes);

            // Assuming RoomSetting has a relationship with OpenTime model
            OpenTime::insert($formattedOpenTimes);

            DB::commit();

            return response()->json($roomSetting, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create room settings', 'details' => $e->getMessage()], 500);
        }
    }



    public function representation(string $roomSetting)
    {
        return RoomSetting::whereKey($roomSetting)->firstOrFail()->getAttribute('title');
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomSetting $roomSetting)
    {
        return $roomSetting;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomSetting $roomSetting, UpdateRoomSettingRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        // Update the roomSetting's details
        $roomSetting->update($validated);

        return $roomSetting;
    }

    public function destroy(RoomSetting $roomSetting)
    {
        $roomSetting->delete();
        return $roomSetting;
    }

    /**
     * Delete multiple records at once.
     */
    public function destroyMany(ManyIdsRequest $request)
    {
        $ids = $request->validated()['ids'];

        // Perform the deletion of the roomSettings
        $deleted = RoomSetting::whereIn('id', $ids)
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        RoomSetting::whereIn('id', $deleted)->delete();

        return $deleted;
    }
}
