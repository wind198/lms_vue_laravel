<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\User;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    use HandlesPagination;
    public const INDEX_ROUTE = 'settings.students';
    public const STORE_ROUTE = self::INDEX_ROUTE . '.store';
    public const UPDATE_ROUTE = self::INDEX_ROUTE . '.update';
    public const UPDATE_MANY_ROUTE = self::INDEX_ROUTE . '.update-many';
    public const DESTROY_ROUTE = self::INDEX_ROUTE . '.destroy';
    public const DESTROY_MANY_ROUTE = self::INDEX_ROUTE . '.destroy-many';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []); // Filters array

        $query = User::where('user_type', 'student')->with(['generation']);

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

        $students = $this->paginateQuery($query, $request->all(), ['created_at' => 'desc']);

        return $students;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {

        $validated = $request->validated();

        $validated['user_type'] = AppConstants::$STUDENT_ROLE;
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
    public function updateMany(UpdateManyUsersRequest $request)
    {
        // Retrieve the array of student IDs from the request
        $ids = $request->input('ids', []);
        // Validate the request data
        $validated = $request->validated();

        // Ensure we have an array of student IDs and update data
        if (empty($ids) || !is_array($ids)) {
            Session::flash('message', [
                "content" => trans('message.update_empty', ['resource' => trans('noun.student')]),
                "type" => "error"
            ]);

            return redirect()->route(self::INDEX_ROUTE);
        }

        try {
            // Perform a batch update using the student IDs and provided update data
            $updatedCount = User::whereIn('id', $ids)
                ->where('user_type', User::$STUDENT_ROLE) // Ensure only student records are updated
                ->update($validated);

            $message = trans('message.update_ok', ['count' => $updatedCount, 'resource' => trans('noun.student')]);

            // Check if any students were actually updated
            if ($updatedCount > 0) {
                Session::flash('message', ["content" => $message, "type" => "success"]);
            } else {
                Session::flash('message', ["content" => $message, "type" => "error"]);
            }

            return redirect()->route(self::INDEX_ROUTE);

        } catch (\Exception $e) {
            // Handle exception and log error if necessary
            Session::flash('message', [
                "content" => trans('message.update_fail', ['resource' => trans('noun.student')]),
                "type" => "error"
            ]);

            return redirect()->route(self::INDEX_ROUTE);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
    }
}
