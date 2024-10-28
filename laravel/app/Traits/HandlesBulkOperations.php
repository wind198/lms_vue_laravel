<?php

namespace App\Traits;

trait HandlesBulkOperations
{
    /**
     * Update multiple records at once.
     *
     * @param array $ids
     * @param array $validated
     * @param string $modelClass
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMany(array $ids, array $validated, string $modelClass)
    {
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => trans('validation.empty', ['attribute' => 'ids'])], 400);
        }

        $updatedCount = $modelClass::whereIn('id', $ids)->update($validated);

        return response()->json(['updated' => $updatedCount]);
    }

    /**
     * Delete multiple records at once.
     *
     * @param array $ids
     * @param string $modelClass
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMany(array $ids, string $modelClass)
    {
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => trans('validation.empty', ['attribute' => 'ids'])], 400);
        }

        $deletedIds = $modelClass::whereIn('id', $ids)->pluck('id');

        if ($deletedIds->isEmpty()) {
            return response()->json([], 204);
        }

        $modelClass::whereIn('id', $deletedIds)->delete();

        return response()->json($deletedIds);
    }
}
