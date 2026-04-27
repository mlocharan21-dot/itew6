<?php
namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentExtraCurricularsTest extends TestCase {
    use RefreshDatabase;

    private User $user;
    private Student $student;

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->student = Student::create([
            'first_name' => 'Jane', 'last_name' => 'Doe',
            'email' => 'jane@example.com', 'year' => 2, 'section' => 'B', 'status' => 'active',
        ]);
    }

    public function test_can_list_extra_curriculars(): void {
        $this->student->extraCurriculars()->create(['name' => 'Chess Club', 'role' => 'President']);
        $this->actingAs($this->user)
            ->getJson("/api/students/{$this->student->id}/extra-curriculars")
            ->assertOk()->assertJsonCount(1)->assertJsonFragment(['name' => 'Chess Club']);
    }

    public function test_can_create_extra_curricular(): void {
        $this->actingAs($this->user)
            ->postJson("/api/students/{$this->student->id}/extra-curriculars", [
                'name' => 'Math Club', 'role' => 'Secretary', 'startYear' => 2023,
            ])
            ->assertCreated()->assertJsonFragment(['name' => 'Math Club', 'role' => 'Secretary']);
    }

    public function test_create_requires_name(): void {
        $this->actingAs($this->user)
            ->postJson("/api/students/{$this->student->id}/extra-curriculars", [])
            ->assertUnprocessable();
    }

    public function test_can_update_extra_curricular(): void {
        $ec = $this->student->extraCurriculars()->create(['name' => 'Old Club']);
        $this->actingAs($this->user)
            ->putJson("/api/students/{$this->student->id}/extra-curriculars/{$ec->id}", ['name' => 'New Club'])
            ->assertOk()->assertJsonFragment(['name' => 'New Club']);
    }

    public function test_can_delete_extra_curricular(): void {
        $ec = $this->student->extraCurriculars()->create(['name' => 'Some Club']);
        $this->actingAs($this->user)
            ->deleteJson("/api/students/{$this->student->id}/extra-curriculars/{$ec->id}")
            ->assertNoContent();
        $this->assertDatabaseMissing('student_extra_curriculars', ['id' => $ec->id]);
    }
}
