<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseEntityController extends Controller
{
    /**
     * Update a specific resource.
     *
     * @param  Model $model
     * @param  array $validatedData
     * @return \Illuminate\Http\Response
     */
    protected function update(Model $model, array $validatedData)
    {
        $model->update($validatedData);
        
        // If email was updated, send a new email verification notification
        if ($model->wasChanged('email')) {
            $model->sendEmailVerificationNotification();
        }

        return response()->json($model, 200);
    }

    /**
     * Update multiple resources.
     *
     * @param  array $ids
     * @param  array $validatedData
     * @param  string $modelClass
     * @return \Illuminate\Http\Response
     */
    protected function updateMany(array $ids, array $validatedData, string $modelClass)
    {
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => trans('validation.array', ['attribute' => 'ids'])], 400);
        }

        $updatedCount = $modelClass::whereIn('id', $ids)->update($validatedData);
        
        return response()->json(['updated_count' => $updatedCount], 200);
    }

    /**
     * Delete a specific resource.
     *
     * @param  Model $model
     * @return \Illuminate\Http\Response
     */
    protected function delete(Model $model)
    {
        $model->delete();
        return response()->json(null, 204);
    }

    /**
     * Delete multiple resources.
     *
     * @param  array $ids
     * @param  string $modelClass
     * @return \Illuminate\Http\Response
     */
    protected function deleteMany(array $ids, string $modelClass)
    {
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => trans('validation.empty', ['attribute' => 'ids'])], 400);
        }

        $deletedCount = $modelClass::destroy($ids);
        
        return response()->json(['deleted_count' => $deletedCount], 200);
    }
}
