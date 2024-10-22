<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
   use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles to ensure they are present during tests
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_create_course()
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->assignRole('Admin'); // Assign the Admin role to the user

        // Prepare course data
        $courseData = [
            'name' => 'B.Sc Computing',
            'seo_url' => 'bsc-computing',
            'faculty_id' => 1,
            'category_id' => 1,
            'status' => 'publish'
        ];

        // Act as the admin and make a POST request to create a course
        $response = $this->actingAs($admin)->post('/courses', $courseData);

        // Assert that the response redirects to the course index
        $response->assertRedirect('/courses');

        // Assert that the course was created in the database
        $this->assertDatabaseHas('courses', $courseData);
    }
}
