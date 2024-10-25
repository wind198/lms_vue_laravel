<?php

namespace Tests\Feature;

use App\Constants\AppConstants;
use Database\Seeders\StudentSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Log;
use App\Http\Controllers\StudentsController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Log\Logger;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase; // This trait ensures that the database is refreshed for each test

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the database before each test
        $this->seed(StudentSeeder::class);
    }

    /** @test */
    public function test_get_list_paging(): void
    {
        // Create a user instance
        $user = User::first(); // Use a factory to create a user
        // Log in the user
        $this->actingAs($user);
        // Call the API at /students
        $response = $this->getJson(route(StudentsController::INDEX_ROUTE));

        // Print out the response (optional)
        // You can uncomment the line below to see the response during testing
        $content = $response->json
        ();

        Log::info($content);
        // Expect response to have status 200
        $response->assertStatus(200);

        // Expect response data to be an array
        $response->assertJsonStructure([
            'data' => [
                '*' => [], // Just checking that it's an array of objects without specific field requirements
            ],
        ]);
    }
    /** @test */
    public function test_create_student(): void
    {
        // Create a user instance
        $user = User::first(); // Use a factory to create a user
        // Log in the user
        $this->actingAs($user);
        // Call the API at /students
        $response = $this->postJson(route(StudentsController::CREATE_ROUTE), User::factory()->makeOne([
            'user_type' => AppConstants::STUDENT_ROLE,
            'education_background' => AppConstants::HIGH_SCHOOL,
        ])->toArray());

        // Print out the response (optional)
        // You can uncomment the line below to see the response during testing
        $content = $response->json
        ();

        Log::info($content);
        // Expect response to have status 200
        $response->assertStatus(201);
    }

    /** @test */
    public function test_update_student(): void
    {
        // Create a user instance
        $user = User::first(); // Use a factory to create a user
        // Log in the user
        $this->actingAs($user);
        // Call the API at /students

        $builder = User::query();
        $studentToUpdate = $builder->get()->random(1)->first();
        $response = $this->patchJson(route(StudentsController::UPDATE_ROUTE, ['user' => $studentToUpdate->getKey()]), ['dob' => '2002-02-19']);

        // Print out the response (optional)
        // You can uncomment the line below to see the response during testing
        $content = $response->json
        ();

        Log::info($content);
        // Expect response to have status 200
        $response->assertJsonFragment(['dob' => '2002-02-19']);
    }

    /** @test */
    public function test_update_many_student(): void
    {
        // Create a user instance
        $user = User::first(); // Use a factory to create a user
        // Log in the user
        $this->actingAs($user);
        // Call the API at /students

        $builder = User::query();
        $studentsToUpdate = $builder->get()->random(2);
        $studentsIds = $studentsToUpdate->pluck('id')->toArray();
        $response = $this->patchJson(route(StudentsController::UPDATE_MANY_ROUTE, ['ids' => $studentsIds]), ['dob' => '2002-02-19']);

        $content = $response->getContent();

        Log::info($content);

        // Assert the response has a JSON structure of an array
        $response->assertJson(function (AssertableJson $json) {
            // Check if the response is an array
            $content = $json->toArray();
            $this->assertIsArray($content);

            // Ensure each element in the array is numeric
            foreach ($json as $value) {
                $this->assertIsString($value);
            }
        });

    }
    /** @test */
    public function test_update_many_student_fail_for_empty(): void
    {
        // Create a user instance
        $user = User::first(); // Use a factory to create a user
        // Log in the user
        $this->actingAs($user);
        // Call the API at /students


        $response = $this->patchJson(route(StudentsController::UPDATE_MANY_ROUTE, ['ids' => []]), ['dob' => '2002-02-19']);


        // Assert the response has a JSON structure of an array
        $response->assertBadRequest();

    }

    public function test_delete(): void
    {
        $user = User::first(); // Use a factory to create a user
        // Log in the user
        $this->actingAs($user);
        $builder = User::query();
        $studentToDelete = $builder->get()->random(1)->first();

        $response = $this->deleteJson(route(StudentsController::DELETE_ROUTE, ['user' => $studentToDelete->getKey()]));

        $content = $response->getContent();

        Log::info($content);

        $response->assertOk();

        $response->assertJsonStructure([
            'first_name' => [],
            'last_name' => []
        ]);

    }


    /** @test */
    public function test_delete_many_student(): void
    {
        // Create a user instance
        $user = User::first(); // Use a factory to create a user
        // Log in the user
        $this->actingAs($user);
        // Call the API at /students

        $builder = User::query();
        $studentsToDelete = $builder->get()->random(2);
        $studentsIds = $studentsToDelete->pluck('id')->toArray();
        $response = $this->deleteJson(route(StudentsController::DELETE_MANY_ROUTE, ['ids' => $studentsIds]), );

        $content = $response->getContent();

        Log::info($content);

        $response->assertOk();
        // Assert the response has a JSON structure of an array
        $response->assertJson(function (AssertableJson $json) {
            // Check if the response is an array
            $content = $json->toArray();
            $this->assertIsArray($content);

            // Ensure each element in the array is numeric
            foreach ($json as $value) {
                $this->assertIsString($value);
            }
        });

    }

}
