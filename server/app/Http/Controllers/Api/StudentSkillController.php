<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentSkill;
use Illuminate\Http\Request;

class StudentSkillController extends Controller {
    public function index(Student $student) {
        return response()->json($student->skills->map(fn($s) => $this->format($s)));
    }

    public function store(Request $request, Student $student) {
        $data = $request->validate([
            'name'        => 'required|string',
            'category'    => 'nullable|string',
            'proficiency' => 'nullable|in:beginner,intermediate,advanced',
            'description' => 'nullable|string',
        ]);
        $skill = $student->skills()->create([
            'name'        => $data['name'],
            'category'    => $data['category'] ?? null,
            'proficiency' => $data['proficiency'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
        return response()->json($this->format($skill), 201);
    }

    public function update(Request $request, Student $student, StudentSkill $skill) {
        $data = $request->validate([
            'name'        => 'sometimes|string',
            'category'    => 'nullable|string',
            'proficiency' => 'nullable|in:beginner,intermediate,advanced',
            'description' => 'nullable|string',
        ]);
        $skill->update(array_filter([
            'name'        => $data['name'] ?? null,
            'category'    => $data['category'] ?? null,
            'proficiency' => $data['proficiency'] ?? null,
            'description' => $data['description'] ?? null,
        ], fn($v) => $v !== null));
        return response()->json($this->format($skill->fresh()));
    }

    public function destroy(Student $student, StudentSkill $skill) {
        $skill->delete();
        return response()->noContent();
    }

    private function format(StudentSkill $s): array {
        return [
            'id' => $s->id, 'studentId' => $s->student_id,
            'name' => $s->name, 'category' => $s->category,
            'proficiency' => $s->proficiency, 'description' => $s->description,
        ];
    }
}
