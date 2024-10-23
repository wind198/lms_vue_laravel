<?php

namespace Tests\Feature;

use Database\Seeders\StudentSeeder;
use Log;
use App\Http\Controllers\StudentsController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Log\Logger;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase; // This trait ensures that the database is refreshed for each test


    /**
     * A basic feature test example.
     */
    /** @test */
    public function test_get_list_paging(): void
    {
        $this->seed(StudentSeeder::class);
        // Create a user instance
        $user = User::factory()->create(); // Use a factory to create a user
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
}
