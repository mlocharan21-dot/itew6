<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentOrganization;
use Illuminate\Http\Request;

class StudentOrganizationController extends Controller {
    public function index(Student $student) {
        return response()->json($student->organizations->map(fn($o) => $this->format($o)));
    }

    public function store(Request $request, Student $student) {
        $data = $request->validate([
            'organizationName' => 'required|string',
            'position'         => 'nullable|string',
            'type'             => 'nullable|string',
            'startYear'        => 'nullable|integer|digits:4',
            'endYear'          => 'nullable|integer|digits:4',
            'isActive'         => 'boolean',
        ]);
        $o = $student->organizations()->create([
            'organization_name' => $data['organizationName'],
            'position'          => $data['position'] ?? null,
            'type'              => $data['type'] ?? null,
            'start_year'        => $data['startYear'] ?? null,
            'end_year'          => $data['endYear'] ?? null,
            'is_active'         => $data['isActive'] ?? true,
        ]);
        return response()->json($this->format($o), 201);
    }

    public function update(Request $request, Student $student, StudentOrganization $organization) {
        $data = $request->validate([
            'organizationName' => 'sometimes|string',
            'position'         => 'nullable|string',
            'type'             => 'nullable|string',
            'startYear'        => 'nullable|integer|digits:4',
            'endYear'          => 'nullable|integer|digits:4',
            'isActive'         => 'boolean',
        ]);
        $update = array_filter([
            'organization_name' => $data['organizationName'] ?? null,
            'position'          => $data['position'] ?? null,
            'type'              => $data['type'] ?? null,
            'start_year'        => $data['startYear'] ?? null,
            'end_year'          => $data['endYear'] ?? null,
        ], fn($v) => $v !== null);
        if (isset($data['isActive'])) {
            $update['is_active'] = $data['isActive'];
        }
        $organization->update($update);
        return response()->json($this->format($organization->fresh()));
    }

    public function destroy(Student $student, StudentOrganization $organization) {
        $organization->delete();
        return response()->noContent();
    }

    private function format(StudentOrganization $o): array {
        return [
            'id' => $o->id, 'studentId' => $o->student_id,
            'organizationName' => $o->organization_name,
            'position' => $o->position, 'type' => $o->type,
            'startYear' => $o->start_year, 'endYear' => $o->end_year,
            'isActive' => $o->is_active,
        ];
    }
}
