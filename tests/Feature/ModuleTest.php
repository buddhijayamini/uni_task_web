<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\User;
use App\Models\Course;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\FacultySeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    use RefreshDatabase;

    protected $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        
        // Create a course to be used in the tests
        $this->course = Course::factory()->create();
    }

    public function test_admin_can_create_module()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $this->actingAs($admin)
            ->post(route('modules.store', ['courseId' => $this->course->id]), [
                'course_id' => $this->course->id,
                'code' => 'CS101',
                'name' => 'Introduction to Computer Science',
                'description' => 'Basic concepts of computer science',
                'status' => 'publish',
            ])
            ->assertRedirect(route('modules.index', ['courseId' => $this->course->id]));

        $this->assertDatabaseHas('modules', [
            'code' => 'CS101',
            'name' => 'Introduction to Computer Science',
        ]);
    }

    public function test_academic_head_can_create_module()
    {
        $academicHead = User::factory()->create();
        $academicHead->assignRole('Academic Head');

        $this->actingAs($academicHead)
            ->post(route('modules.store', ['courseId' => $this->course->id]), [
                'course_id' => $this->course->id,
                'code' => 'CS102',
                'name' => 'Data Structures',
                'description' => 'Introduction to data structures',
                'status' => 'draft',
            ])
            ->assertRedirect(route('modules.index', ['courseId' => $this->course->id]));

        $this->assertDatabaseHas('modules', [
            'code' => 'CS102',
            'name' => 'Data Structures',
            'status' => 'draft',
        ]);
    }

    public function test_teacher_and_student_can_view_module()
    {
        $module = Module::factory()->create([
            'course_id' => $this->course->id,
            'code' => 'CS103',
            'name' => 'Algorithms',
            'description' => 'Introduction to algorithms',
            'status' => 'publish',
        ]);

        $teacher = User::factory()->create();
        $teacher->assignRole('Teacher');

        $student = User::factory()->create();
        $student->assignRole('Student');

        // Teacher accesses the module
        $this->actingAs($teacher)
            ->get(route('modules.show', ['courseId' => $this->course->id, 'moduleId' => $module->id]))
            ->assertStatus(200)
            ->assertSee($module->name);

        // Student accesses the module
        $this->actingAs($student)
            ->get(route('modules.show', ['courseId' => $this->course->id, 'moduleId' => $module->id]))
            ->assertStatus(200)
            ->assertSee($module->name);
    }

    public function test_academic_head_can_update_module_within_time_limit()
    {
        $academicHead = User::factory()->create();
        $academicHead->assignRole('Academic Head');

        $module = Module::factory()->create([
            'course_id' => $this->course->id,
            'code' => 'CS104',
            'name' => 'Operating Systems',
            'description' => 'Introduction to operating systems',
            'status' => 'draft',
        ]);

        // Simulate the passage of time to be within 6 hours
        $this->travel(2)->hours();

        $this->actingAs($academicHead)
            ->put(route('modules.update', ['courseId' => $this->course->id, 'moduleId' => $module->id]), [
                'course_id' => $this->course->id,
                'code' => 'CS104',
                'name' => 'Operating Systems Updated',
                'description' => 'Updated description',
                'status' => 'draft',
            ])
            ->assertRedirect(route('modules.index', ['courseId' => $this->course->id]));

        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'name' => 'Operating Systems Updated',
            'credit' => 4,
        ]);
    }

    public function test_academic_head_cannot_update_module_after_time_limit()
    {
        $academicHead = User::factory()->create();
        $academicHead->assignRole('Academic Head');

        $module = Module::factory()->create([
            'course_id' => $this->course->id,
            'code' => 'CS105',
            'name' => 'Networks',
            'description' => 'Introduction to networking',
            'status' => 'publish',
        ]);

        // Check created_at for debugging
        Log::info('Module created_at: ' . $module->created_at);

        // Simulate the passage of time beyond 6 hours
        $this->travel(7)->hours();

        // Attempt to update the module
        $response = $this->actingAs($academicHead)
            ->put(route('modules.update', ['courseId' => $this->course->id, 'moduleId' => $module->id]), [
                'course_id' => $this->course->id,
                'code' => 'CS105',
                'name' => 'Networks Updated',
                'semester' => 1,
                'description' => 'Updated description',
                'credit' => 4,
                'status' => 'publish',
            ]);


        // Assert that the response redirects
        $response->assertRedirect(route('modules.index', ['courseId' => $this->course->id]));

        // Assert that the error message is present in the session
        $response->assertSessionHas('error', 'Cannot update module after 6 hours');

        // Ensure that the module has not been updated
        $this->assertDatabaseMissing('modules', [
            'name' => 'Networks Updated',
        ]);
    }

    public function test_admin_can_delete_module()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $module = Module::factory()->create([
            'course_id' => $this->course->id,
            'code' => 'CS106',
            'name' => 'Database Systems',
            'semester' => 1,
            'description' => 'Introduction to databases',
            'credit' => 3,
            'status' => 'publish',
        ]);

        $this->actingAs($admin)
            ->delete(route('modules.destroy', ['courseId' => $this->course->id, 'moduleId' => $module->id]))
            ->assertRedirect(route('modules.index', ['courseId' => $this->course->id]));

        $this->assertDatabaseMissing('modules', [
            'id' => $module->id,
        ]);
    }
}
