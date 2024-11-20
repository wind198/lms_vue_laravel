<?php

namespace App\Http\Controllers\Entities;

use App\Contracts\HasRepresentationRoute;
use App\Constants\AppConstants;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ManyIdsRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateManyUsersRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Generation;
use App\Models\User;
use App\Traits\HandlesPagination;
use DB;
use Illuminate\Http\Request;
use Log;

class StudentController extends Controller implements HasRepresentationRoute
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


    public function store(CreateUserRequest $request)
    {
        $validated = $request->validated();
        $payload = User::augmentCreateUserPayload($validated, AppConstants::STUDENT_ROLE);

        $builder = User::query();

        // Find the generation and associate it with the user
        $matchGeneration = Generation::findOrFail($validated['generation_id']);
        $student = $builder->create($payload);
        $student->generation()->associate($matchGeneration);

        $student->save();  // Ensure the association is persisted

        // Send email verification notification
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
        // Retrieve the array of student IDs and validate the data
        $validated = $request->validated();

        $ids = $validated['ids'];
        // Start a transaction for atomic updates
        DB::beginTransaction();

        try {
            if (
                isset($validated['generation_id'])
            ) {
                Generation::findOrFail($validated['generation_id']);
            }
            $updatePayload = collect($validated)->except(['ids'])->toArray();
            // Bulk update fields that don’t require relationship handling
            User::whereIn('id', $ids)
                ->where('user_type', AppConstants::STUDENT_ROLE)
                ->update($updatePayload);

            // Commit the transaction
            DB::commit();

            return response()->json(['updated_ids' => $ids, 'message' => 'Users updated successfully.']);
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollBack();
            return response()->json(['error' => 'Failed to update users', 'details' => $e->getMessage()], 500);
        }
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
        // Retrieve the array of student IDs from the request
        $ids = $request->validated()['ids'];

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
