<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Constants\AppConstants;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateManyUsersRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Generation;
use App\Models\User;
use App\Traits\HandlesPagination;
use DB;
use Illuminate\Http\Request;
use Log;

class StudentsController extends Controller
{
    use HandlesPagination;
    public const INDEX_ROUTE = 'students';
    public const SHOW_ROUTE = self::INDEX_ROUTE . '.show';
    public const CREATE_ROUTE = self::INDEX_ROUTE . '.create';
    public const UPDATE_ROUTE = self::INDEX_ROUTE . '.update';
    public const UPDATE_MANY_ROUTE = self::INDEX_ROUTE . '.update-many';
    public const DELETE_ROUTE = self::INDEX_ROUTE . '.delete';
    public const DELETE_MANY_ROUTE = self::INDEX_ROUTE . '.delete-many';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = User::where('user_type', 'student')->with([
            'generation' => function ($query) {
                $query->select('id', 'title');
            }
        ]);

        // Text search filter
        if (!empty($filter['q'])) {
            $query->where(function ($q) use ($filter) {
                $q->where('email', 'like', "%{$filter['q']}%")
                    ->orWhere('first_name', 'like', "%{$filter['q']}%")
                    ->orWhere('last_name', 'like', "%{$filter['q']}%")
                    ->orWhere('phone', 'like', "%{$filter['q']}%");
            });
        }

        // Date range filters
        $dateFilters = ['created_at', 'dob'];
        foreach ($dateFilters as $field) {
            if (!empty($filter[$field]['gte'])) {
                $query->where($field, '>=', $filter[$field]['gte']);
            }
            if (!empty($filter[$field]['lte'])) {
                $query->where($field, '<=', $filter[$field]['lte']);
            }
        }

        // Direct filters
        $directFilters = ['gender', 'education_background', 'user_type'];
        foreach ($directFilters as $field) {
            if (!empty($filter[$field])) {
                $query->where($field, $filter[$field]);
            }
        }

        $res = $this->paginateQuery($query, $request->all(), ['created_at' => 'desc']);

        return $res['output'];
    }


    public function create(CreateUserRequest $request)
    {
        $validated = $request->validated();
        $payload = User::augmentCreateUserPayload($validated, AppConstants::STUDENT_ROLE);

        DB::beginTransaction();
        try {
            $builder = User::query();

            // Find the generation and associate it with the user
            $matchGeneration = Generation::findOrFail($validated['generation_id']);
            $student = $builder->create($payload);
            $student->generation()->associate($matchGeneration);

            $student->save();  // Ensure the association is persisted

            // Send email verification notification
            $student->sendEmailVerificationNotification();

            DB::commit();

            return $student;
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the exception for troubleshooting
            Log::error('Failed to create student: ' . $e->getMessage(), [
                'exception' => $e,
                'payload' => $payload,
            ]);

            // Return a JSON response with an error message
            return response(trans('message.error.generic'), 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UpdateUserRequest $request, )
    {

        // Validate the request data
        $validated = $request->validated();

        // Update the student's details
        $user->update($validated);


        // If email was updated, send a new email verification notification
        if ($user->wasChanged('email')) {
            $user->sendEmailVerificationNotification();
        }
        return $user;
    }

    /**
     * Update the multiple record at once.
     */
    public function updateMany(UpdateManyUsersRequest $request)
    {
        // Retrieve the array of student IDs from the request
        $ids = $request->input('ids', []);
        // Validate the request data
        $validated = $request->validated();

        if (!is_array($ids)) {
            return response()->json(['message' => trans('validation.array', ['attribute' => 'ids'])], 400);
        }
        // Ensure we have an array of student IDs and update data
        if (empty($ids)) {
            return response()->json(['message' => trans('validation.empty', ['attribute' => 'ids'])], 400);
        }

        User::whereIn('id', $ids)
            ->where('user_type', AppConstants::STUDENT_ROLE)->update($validated);


        return $ids;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete(User $user)
    {
        $user->delete();
        return $user;
    }

    /**
     * Delete multiple records at once.
     */
    public function deleteMany(Request $request)
    {
        // Retrieve the array of student IDs from the request
        $ids = $request->input('ids', []);

        if (!is_array($ids)) {
            return response()->json(['message' => trans('validation.array', ['attribute' => 'ids'])], 400);

        }
        if (empty($ids)) {
            return response()->json(['message' => trans('validation.empty', ['attribute' => 'ids'])], 400);
        }
        // Perform the deletion of the students
        $deleted = User::whereIn('id', $ids)
            ->where('user_type', AppConstants::STUDENT_ROLE) // Ensure only student records are deleted
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        User::whereIn('id', $deleted)->delete();

        return $deleted;
    }
}
