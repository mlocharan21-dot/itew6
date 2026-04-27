<?php
namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentViolationsTest extends TestCase {
    use RefreshDatabase;

    private User $user;
    private Student $student;

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->student = Student::create([
            'first_name' => 'Mark', 'last_name' => 'Smith',
            'email' => 'mark@example.com', 'year' => 1, 'section' => 'A', 'status' => 'active',
        ]);
    }

    public function test_can_list_violations(): void {
        $this->student->violations()->create(['description' => 'Late submission', 'date' => '2026-01-10', 'status' => 'pending']);
        $this->actingAs($this->user)
            ->getJson("/api/students/{$this->student->id}/violations")
            ->assertOk()->assertJsonCount(1)->assertJsonFragment(['description' => 'Late submission']);
    }

    public function test_can_create_violation(): void {
        $this->actingAs($this->user)
            ->postJson("/api/students/{$this->student->id}/violations", [
                'description' => 'Cheating', 'date' => '2026-02-15', 'status' => 'resolved',
            ])
            ->assertCreated()->assertJsonFragment(['description' => 'Cheating', 'status' => 'resolved']);
    }

    public function test_create_requires_description_and_date(): void {
        $this->actingAs($this->user)
            ->postJson("/api/students/{$this->student->id}/violations", [])
            ->assertUnprocessable();
    }

    public function test_can_update_violation(): void {
        $v = $this->student->violations()->create(['description' => 'Minor offense', 'date' => '2026-01-01', 'status' => 'pending']);
        $this->actingAs($this->user)
            ->putJson("/api/students/{$this->student->id}/violations/{$v->id}", ['status' => 'resolved'])
            ->assertOk()->assertJsonFragment(['status' => 'resolved']);
    }

    public function test_can_delete_violation(): void {
        $v = $this->student->violations()->create(['description' => 'Some offense', 'date' => '2026-01-01', 'status' => 'pending']);
        $this->actingAs($this->user)
            ->deleteJson("/api/students/{$this->student->id}/violations/{$v->id}")
            ->assertNoContent();
        $this->assertDatabaseMissing('student_violations', ['id' => $v->id]);
    }
}
