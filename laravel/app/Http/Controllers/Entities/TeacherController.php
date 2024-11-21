<?php

namespace App\Http\Controllers\Entities;

use App\Contracts\HasRepresentationRoute;
use App\Constants\AppConstants;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ManyIdsRequest;
use App\Http\Requests\UpdateManyUsersRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Traits\HandlesPagination;
use Illuminate\Http\Request;

class TeacherController extends Controller implements HasRepresentationRoute
{
    use HandlesPagination;
    public function representation(string $user)
    {
        return User::whereKey($user)->firstOrFail()->getAttribute('full_name');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', []); // Filters array

        $query = User::query()->where('user_type', 'teacher');

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


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {

        $validated = $request->validated();

        $payload = User::augmentCreateUserPayload($validated, AppConstants::TEACHER_ROLE);

        $builder = User::query();
        $teacher = $builder->create($payload);

        $teacher->sendEmailVerificationNotification();

        return $teacher;

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if (!$user->exists) {
            abort(404, 'Not found');
        }
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UpdateUserRequest $request, )
    {

        // Validate the request data
        $validated = $request->validated();

        // Update the teacher's details
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
        // Retrieve the array of teacher IDs from the request
        $validated = $request->validated();

        $ids = $validated['ids'];


        User::whereIn('id', $ids)
            ->where('user_type', AppConstants::TEACHER_ROLE)->update($validated);


        return $ids;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $user;
    }

    /**
     * Delete multiple records at once.
     */
    public function destroyMany(ManyIdsRequest $request)
    {
        $ids = $request->validated()['ids'];

        $deleted = User::whereIn('id', $ids)
            ->where('user_type', AppConstants::TEACHER_ROLE) // Ensure only teacher records are deleted
            ->pluck('id'); // Retrieve IDs of the records to be deleted

        if ($deleted->isEmpty()) {
            return response()->json([], 204);
        }

        // Actually delete the records
        User::whereIn('id', $deleted)->delete();

        return $deleted;
    }
}
