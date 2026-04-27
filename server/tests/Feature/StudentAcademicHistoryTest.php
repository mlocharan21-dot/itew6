<?php
namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentAcademicHistoryTest extends TestCase {
    use RefreshDatabase;

    private User $user;
    private Student $student;

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->student = Student::create([
            'first_name' => 'John', 'last_name' => 'Doe',
            'email' => 'john@example.com', 'year' => 1, 'section' => 'A', 'status' => 'active',
        ]);
    }

    public function test_can_list_academic_history(): void {
        $this->student->academicHistories()->create(['level' => 'elementary', 'school_name' => 'San Jose Elem']);
        $this->actingAs($this->user)
            ->getJson("/api/students/{$this->student->id}/academic-history")
            ->assertOk()->assertJsonCount(1)->assertJsonFragment(['schoolName' => 'San Jose Elem']);
    }

    public function test_can_create_academic_history(): void {
        $this->actingAs($this->user)
            ->postJson("/api/students/{$this->student->id}/academic-history", [
                'level' => 'elementary', 'schoolName' => 'San Jose Elem', 'honors' => 'Valedictorian',
            ])
            ->assertCreated()->assertJsonFragment(['schoolName' => 'San Jose Elem', 'honors' => 'Valedictorian']);
    }

    public function test_create_requires_level_and_school_name(): void {
        $this->actingAs($this->user)
            ->postJson("/api/students/{$this->student->id}/academic-history", [])
            ->assertUnprocessable();
    }

    public function test_can_update_academic_history(): void {
        $h = $this->student->academicHistories()->create(['level' => 'elementary', 'school_name' => 'Old School']);
        $this->actingAs($this->user)
            ->putJson("/api/students/{$this->student->id}/academic-history/{$h->id}", ['schoolName' => 'New School'])
            ->assertOk()->assertJsonFragment(['schoolName' => 'New School']);
    }

    public function test_can_delete_academic_history(): void {
        $h = $this->student->academicHistories()->create(['level' => 'elementary', 'school_name' => 'Some School']);
        $this->actingAs($this->user)
            ->deleteJson("/api/students/{$this->student->id}/academic-history/{$h->id}")
            ->assertNoContent();
        $this->assertDatabaseMissing('student_academic_histories', ['id' => $h->id]);
    }
}
