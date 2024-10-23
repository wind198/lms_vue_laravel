<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateManyStudentsRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\User;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;

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
        $filters = $request->input('filters', []); // Filters array

        $query = User::where('user_type', 'student');

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($query) use ($q) {
                $query->where('email', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['created_at']['gte'])) {
            $query->where('created_at', '>=', $filters['created_at']['gte']);
        }

        if (!empty($filters['created_at']['lte'])) {
            $query->where('created_at', '<=', $filters['created_at']['lte']);
        }

        $res = $this->paginateQuery($query, $request->all(), ['created_at' => 'desc']);


        return $res['output'];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(StoreStudentRequest $request)
    {

        $validated = $request->validated();

        $validated['user_type'] = AppConstants::STUDENT_ROLE;
        $validated['password'] = User::generateRandomPassword(); // generate randow password

        $builder = User::query();
        $student = $builder->create($validated);

        $student->sendEmailVerificationNotification();

        return $student;

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
    public function update(User $user, UpdateStudentRequest $request, )
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
    public function updateMany(UpdateManyStudentsRequest $request)
    {
        // Retrieve the array of student IDs from the request
        $ids = $request->input('ids', []);
        // Validate the request data
        $validated = $request->validated();

        if (!is_array($ids)) {
            response()->json(['message' => trans('validation.array', ['attribute' => 'ids'])], 400);
        }
        // Ensure we have an array of student IDs and update data
        if (empty($ids)) {
            response()->json(['message' => trans('validation.empty', ['attribute' => 'ids'])], 400);
        }

        // Perform a batch update using the student IDs and provided update data
        $updatedCount = User::whereIn('id', $ids)
            ->where('user_type', AppConstants::STUDENT_ROLE) // Ensure only student records are updated
            ->update($validated);

        return $updatedCount;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete(User $user)
    {
        $user->delete();
        return true;
    }

    /**
     * Delete multiple records at once.
     */
    public function deleteMany(Request $request)
    {
        // Retrieve the array of student IDs from the request
        $ids = $request->input('ids', []);

        if (!is_array($ids)) {
            response()->json(['message' => trans('validation.array', ['attribute' => 'ids'])], 400);

        }
        if (empty($ids)) {
            response()->json(['message' => trans('validation.empty', ['attribute' => 'ids'])], 400);
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
