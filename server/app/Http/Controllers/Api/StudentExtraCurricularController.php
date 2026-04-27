<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentExtraCurricular;
use Illuminate\Http\Request;

class StudentExtraCurricularController extends Controller {
    public function index(Student $student) {
        return response()->json($student->extraCurriculars->map(fn($e) => $this->format($e)));
    }

    public function store(Request $request, Student $student) {
        $data = $request->validate([
            'name'         => 'required|string',
            'role'         => 'nullable|string',
            'organization' => 'nullable|string',
            'startYear'    => 'nullable|integer|digits:4',
            'endYear'      => 'nullable|integer|digits:4',
        ]);
        $ec = $student->extraCurriculars()->create([
            'name'         => $data['name'],
            'role'         => $data['role'] ?? null,
            'organization' => $data['organization'] ?? null,
            'start_year'   => $data['startYear'] ?? null,
            'end_year'     => $data['endYear'] ?? null,
        ]);
        return response()->json($this->format($ec), 201);
    }

    public function update(Request $request, Student $student, StudentExtraCurricular $extraCurricular) {
        $data = $request->validate([
            'name'         => 'sometimes|string',
            'role'         => 'nullable|string',
            'organization' => 'nullable|string',
            'startYear'    => 'nullable|integer|digits:4',
            'endYear'      => 'nullable|integer|digits:4',
        ]);
        $extraCurricular->update(array_filter([
            'name'         => $data['name'] ?? null,
            'role'         => $data['role'] ?? null,
            'organization' => $data['organization'] ?? null,
            'start_year'   => $data['startYear'] ?? null,
            'end_year'     => $data['endYear'] ?? null,
        ], fn($v) => $v !== null));
        return response()->json($this->format($extraCurricular->fresh()));
    }

    public function destroy(Student $student, StudentExtraCurricular $extraCurricular) {
        $extraCurricular->delete();
        return response()->noContent();
    }

    private function format(StudentExtraCurricular $e): array {
        return [
            'id' => $e->id, 'studentId' => $e->student_id,
            'name' => $e->name, 'role' => $e->role,
            'organization' => $e->organization,
            'startYear' => $e->start_year, 'endYear' => $e->end_year,
        ];
    }
}
