<?php
namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentSkillsTest extends TestCase {
    use RefreshDatabase;

    private User $user;
    private Student $student;

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->student = Student::create([
            'first_name' => 'Ana', 'last_name' => 'Cruz',
            'email' => 'ana@example.com', 'year' => 3, 'section' => 'C', 'status' => 'active',
        ]);
    }

    public function test_can_list_skills(): void {
        $this->student->skills()->create(['name' => 'PHP', 'proficiency' => 'intermediate']);
        $this->actingAs($this->user)
            ->getJson("/api/students/{$this->student->id}/skills")
            ->assertOk()->assertJsonCount(1)->assertJsonFragment(['name' => 'PHP']);
    }

    public function test_can_create_skill(): void {
        $this->actingAs($this->user)
            ->postJson("/api/students/{$this->student->id}/skills", [
                'name' => 'JavaScript', 'category' => 'Technical', 'proficiency' => 'advanced',
            ])
            ->assertCreated()->assertJsonFragment(['name' => 'JavaScript', 'proficiency' => 'advanced']);
    }

    public function test_create_requires_name(): void {
        $this->actingAs($this->user)
            ->postJson("/api/students/{$this->student->id}/skills", [])
            ->assertUnprocessable();
    }

    public function test_can_update_skill(): void {
        $s = $this->student->skills()->create(['name' => 'Python']);
        $this->actingAs($this->user)
            ->putJson("/api/students/{$this->student->id}/skills/{$s->id}", ['proficiency' => 'advanced'])
            ->assertOk()->assertJsonFragment(['proficiency' => 'advanced']);
    }

    public function test_can_delete_skill(): void {
        $s = $this->student->skills()->create(['name' => 'CSS']);
        $this->actingAs($this->user)
            ->deleteJson("/api/students/{$this->student->id}/skills/{$s->id}")
            ->assertNoContent();
        $this->assertDatabaseMissing('student_skills', ['id' => $s->id]);
    }
}
