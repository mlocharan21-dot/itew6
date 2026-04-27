<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentViolation;
use Illuminate\Http\Request;

class StudentViolationController extends Controller {
    public function index(Student $student) {
        return response()->json($student->violations->map(fn($v) => $this->format($v)));
    }

    public function store(Request $request, Student $student) {
        $data = $request->validate([
            'description' => 'required|string',
            'date'        => 'required|date',
            'penalty'     => 'nullable|string',
            'status'      => 'in:pending,resolved,dismissed',
            'remarks'     => 'nullable|string',
        ]);
        $v = $student->violations()->create([
            'description' => $data['description'],
            'date'        => $data['date'],
            'penalty'     => $data['penalty'] ?? null,
            'status'      => $data['status'] ?? 'pending',
            'remarks'     => $data['remarks'] ?? null,
        ]);
        return response()->json($this->format($v), 201);
    }

    public function update(Request $request, Student $student, StudentViolation $violation) {
        $data = $request->validate([
            'description' => 'sometimes|string',
            'date'        => 'sometimes|date',
            'penalty'     => 'nullable|string',
            'status'      => 'in:pending,resolved,dismissed',
            'remarks'     => 'nullable|string',
        ]);
        $violation->update(array_filter([
            'description' => $data['description'] ?? null,
            'date'        => $data['date'] ?? null,
            'penalty'     => $data['penalty'] ?? null,
            'status'      => $data['status'] ?? null,
            'remarks'     => $data['remarks'] ?? null,
        ], fn($v) => $v !== null));
        return response()->json($this->format($violation->fresh()));
    }

    public function destroy(Student $student, StudentViolation $violation) {
        $violation->delete();
        return response()->noContent();
    }

    private function format(StudentViolation $v): array {
        return [
            'id' => $v->id, 'studentId' => $v->student_id,
            'description' => $v->description,
            'date' => $v->date?->format('Y-m-d'),
            'penalty' => $v->penalty, 'status' => $v->status, 'remarks' => $v->remarks,
        ];
    }
}
