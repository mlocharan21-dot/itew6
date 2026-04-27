<?php
namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentOrganizationsTest extends TestCase {
    use RefreshDatabase;

    private User $user;
    private Student $student;

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->student = Student::create([
            'first_name' => 'Luis', 'last_name' => 'Reyes',
            'email' => 'luis@example.com', 'year' => 4, 'section' => 'A', 'status' => 'active',
        ]);
    }

    public function test_can_list_organizations(): void {
        $this->student->organizations()->create(['organization_name' => 'IEEE', 'is_active' => true]);
        $this->actingAs($this->user)
            ->getJson("/api/students/{$this->student->id}/organizations")
            ->assertOk()->assertJsonCount(1)->assertJsonFragment(['organizationName' => 'IEEE']);
    }

    public function test_can_create_organization(): void {
        $this->actingAs($this->user)
            ->postJson("/api/students/{$this->student->id}/organizations", [
                'organizationName' => 'ICTSO', 'position' => 'VP', 'type' => 'academic', 'isActive' => true,
            ])
            ->assertCreated()->assertJsonFragment(['organizationName' => 'ICTSO', 'position' => 'VP']);
    }

    public function test_create_requires_organization_name(): void {
        $this->actingAs($this->user)
            ->postJson("/api/students/{$this->student->id}/organizations", [])
            ->assertUnprocessable();
    }

    public function test_can_update_organization(): void {
        $o = $this->student->organizations()->create(['organization_name' => 'Old Org', 'is_active' => true]);
        $this->actingAs($this->user)
            ->putJson("/api/students/{$this->student->id}/organizations/{$o->id}", ['isActive' => false])
            ->assertOk()->assertJsonFragment(['isActive' => false]);
    }

    public function test_can_delete_organization(): void {
        $o = $this->student->organizations()->create(['organization_name' => 'Some Org', 'is_active' => true]);
        $this->actingAs($this->user)
            ->deleteJson("/api/students/{$this->student->id}/organizations/{$o->id}")
            ->assertNoContent();
        $this->assertDatabaseMissing('student_organizations', ['id' => $o->id]);
    }
}
