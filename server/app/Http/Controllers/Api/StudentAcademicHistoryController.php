<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAcademicHistory;
use Illuminate\Http\Request;

class StudentAcademicHistoryController extends Controller {
    public function index(Student $student) {
        return response()->json($student->academicHistories->map(fn($h) => $this->format($h)));
    }

    public function store(Request $request, Student $student) {
        $data = $request->validate([
            'level'         => 'required|in:elementary,high_school',
            'schoolName'    => 'required|string',
            'address'       => 'nullable|string',
            'yearGraduated' => 'nullable|integer|digits:4',
            'honors'        => 'nullable|string',
        ]);
        $h = $student->academicHistories()->create([
            'level'          => $data['level'],
            'school_name'    => $data['schoolName'],
            'address'        => $data['address'] ?? null,
            'year_graduated' => $data['yearGraduated'] ?? null,
            'honors'         => $data['honors'] ?? null,
        ]);
        return response()->json($this->format($h), 201);
    }

    public function update(Request $request, Student $student, StudentAcademicHistory $history) {
        $data = $request->validate([
            'level'         => 'sometimes|in:elementary,high_school',
            'schoolName'    => 'sometimes|string',
            'address'       => 'nullable|string',
            'yearGraduated' => 'nullable|integer|digits:4',
            'honors'        => 'nullable|string',
        ]);
        $history->update(array_filter([
            'level'          => $data['level'] ?? null,
            'school_name'    => $data['schoolName'] ?? null,
            'address'        => $data['address'] ?? null,
            'year_graduated' => $data['yearGraduated'] ?? null,
            'honors'         => $data['honors'] ?? null,
        ], fn($v) => $v !== null));
        return response()->json($this->format($history->fresh()));
    }

    public function destroy(Student $student, StudentAcademicHistory $history) {
        $history->delete();
        return response()->noContent();
    }

    private function format(StudentAcademicHistory $h): array {
        return [
            'id' => $h->id, 'studentId' => $h->student_id,
            'level' => $h->level, 'schoolName' => $h->school_name,
            'address' => $h->address, 'yearGraduated' => $h->year_graduated,
            'honors' => $h->honors,
        ];
    }
}
