# Student Sub-Resources CRUD Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add full CRUD for academic history, extra-curriculars, violations, skills, and organization affiliations as sections on the StudentProfile page.

**Architecture:** Five new Laravel migrations/models/controllers behind nested API routes (`/students/{student}/...`). StudentProfile fetches sub-resources locally with `api.get`. No AppContext changes.

**Tech Stack:** Laravel 11, Sanctum, SQLite (`:memory:` in tests via phpunit.xml), React 18, React Router v6, Vite

---

## File Map

**New (Backend)**
- `server/database/migrations/2026_04_27_000001_create_student_academic_histories_table.php`
- `server/database/migrations/2026_04_27_000002_create_student_extra_curriculars_table.php`
- `server/database/migrations/2026_04_27_000003_create_student_violations_table.php`
- `server/database/migrations/2026_04_27_000004_create_student_skills_table.php`
- `server/database/migrations/2026_04_27_000005_create_student_organizations_table.php`
- `server/app/Models/StudentAcademicHistory.php`
- `server/app/Models/StudentExtraCurricular.php`
- `server/app/Models/StudentViolation.php`
- `server/app/Models/StudentSkill.php`
- `server/app/Models/StudentOrganization.php`
- `server/app/Http/Controllers/Api/StudentAcademicHistoryController.php`
- `server/app/Http/Controllers/Api/StudentExtraCurricularController.php`
- `server/app/Http/Controllers/Api/StudentViolationController.php`
- `server/app/Http/Controllers/Api/StudentSkillController.php`
- `server/app/Http/Controllers/Api/StudentOrganizationController.php`
- `server/tests/Feature/StudentAcademicHistoryTest.php`
- `server/tests/Feature/StudentExtraCurricularsTest.php`
- `server/tests/Feature/StudentViolationsTest.php`
- `server/tests/Feature/StudentSkillsTest.php`
- `server/tests/Feature/StudentOrganizationsTest.php`

**Modified**
- `server/app/Models/Student.php` — add 5 `hasMany` relations
- `server/routes/api.php` — add nested routes
- `client/src/pages/San Jose/students/StudentProfile.jsx` — add 5 CRUD sections

---

### Task 1: Create all 5 migrations and run them

**Files:** 5 new migration files

- [ ] **Step 1: Create academic histories migration**

`server/database/migrations/2026_04_27_000001_create_student_academic_histories_table.php`:
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_academic_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->enum('level', ['elementary', 'high_school']);
            $table->string('school_name');
            $table->string('address')->nullable();
            $table->unsignedSmallInteger('year_graduated')->nullable();
            $table->string('honors')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_academic_histories'); }
};
```

- [ ] **Step 2: Create extra curriculars migration**

`server/database/migrations/2026_04_27_000002_create_student_extra_curriculars_table.php`:
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_extra_curriculars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('organization')->nullable();
            $table->unsignedSmallInteger('start_year')->nullable();
            $table->unsignedSmallInteger('end_year')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_extra_curriculars'); }
};
```

- [ ] **Step 3: Create violations migration**

`server/database/migrations/2026_04_27_000003_create_student_violations_table.php`:
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_violations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->text('description');
            $table->date('date');
            $table->string('penalty')->nullable();
            $table->enum('status', ['pending', 'resolved', 'dismissed'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_violations'); }
};
```

- [ ] **Step 4: Create skills migration**

`server/database/migrations/2026_04_27_000004_create_student_skills_table.php`:
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->string('name');
            $table->string('category')->nullable();
            $table->enum('proficiency', ['beginner', 'intermediate', 'advanced'])->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_skills'); }
};
```

- [ ] **Step 5: Create organizations migration**

`server/database/migrations/2026_04_27_000005_create_student_organizations_table.php`:
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->string('organization_name');
            $table->string('position')->nullable();
            $table->string('type')->nullable();
            $table->unsignedSmallInteger('start_year')->nullable();
            $table->unsignedSmallInteger('end_year')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_organizations'); }
};
```

- [ ] **Step 6: Run migrations**

From `server/`:
```bash
php artisan migrate
```
Expected: Lines like `2026_04_27_000001 ... DONE`, for all 5 new tables.

- [ ] **Step 7: Commit**

```bash
git add server/database/migrations/
git commit -m "feat: migrations for student sub-resources"
```

---

### Task 2: Create all 5 models and update Student model

- [ ] **Step 1: Create StudentAcademicHistory model**

`server/app/Models/StudentAcademicHistory.php`:
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAcademicHistory extends Model {
    protected $fillable = ['student_id','level','school_name','address','year_graduated','honors'];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
```

- [ ] **Step 2: Create StudentExtraCurricular model**

`server/app/Models/StudentExtraCurricular.php`:
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentExtraCurricular extends Model {
    protected $fillable = ['student_id','name','role','organization','start_year','end_year'];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
```

- [ ] **Step 3: Create StudentViolation model**

`server/app/Models/StudentViolation.php`:
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentViolation extends Model {
    protected $fillable = ['student_id','description','date','penalty','status','remarks'];
    protected $casts = ['date' => 'date:Y-m-d'];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
```

- [ ] **Step 4: Create StudentSkill model**

`server/app/Models/StudentSkill.php`:
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSkill extends Model {
    protected $fillable = ['student_id','name','category','proficiency','description'];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
```

- [ ] **Step 5: Create StudentOrganization model**

`server/app/Models/StudentOrganization.php`:
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentOrganization extends Model {
    protected $fillable = ['student_id','organization_name','position','type','start_year','end_year','is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
```

- [ ] **Step 6: Add hasMany relations to Student model**

Open `server/app/Models/Student.php`. Add this `use` import after the existing imports:
```php
use Illuminate\Database\Eloquent\Relations\HasMany;
```

Add these 5 methods inside the class, before the closing `}`:
```php
    public function academicHistories(): HasMany {
        return $this->hasMany(StudentAcademicHistory::class);
    }
    public function extraCurriculars(): HasMany {
        return $this->hasMany(StudentExtraCurricular::class);
    }
    public function violations(): HasMany {
        return $this->hasMany(StudentViolation::class);
    }
    public function skills(): HasMany {
        return $this->hasMany(StudentSkill::class);
    }
    public function organizations(): HasMany {
        return $this->hasMany(StudentOrganization::class);
    }
```

- [ ] **Step 7: Commit**

```bash
git add server/app/Models/
git commit -m "feat: student sub-resource models and Student hasMany relations"
```

---

### Task 3: Academic History — test, controller, routes

- [ ] **Step 1: Write the failing feature test**

`server/tests/Feature/StudentAcademicHistoryTest.php`:
```php
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
```

- [ ] **Step 2: Run test — expect failure (no routes yet)**

From `server/`:
```bash
php artisan test tests/Feature/StudentAcademicHistoryTest.php
```
Expected: All 5 tests FAIL with 404.

- [ ] **Step 3: Create controller**

`server/app/Http/Controllers/Api/StudentAcademicHistoryController.php`:
```php
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
```

- [ ] **Step 4: Add routes to api.php**

Add import at the top of `server/routes/api.php`:
```php
use App\Http\Controllers\Api\StudentAcademicHistoryController;
```

Inside the `auth:sanctum` middleware group, after the existing `Route::apiResource` lines:
```php
    Route::get('students/{student}/academic-history',              [StudentAcademicHistoryController::class, 'index']);
    Route::post('students/{student}/academic-history',             [StudentAcademicHistoryController::class, 'store']);
    Route::put('students/{student}/academic-history/{history}',    [StudentAcademicHistoryController::class, 'update']);
    Route::delete('students/{student}/academic-history/{history}', [StudentAcademicHistoryController::class, 'destroy']);
```

- [ ] **Step 5: Run test — expect all pass**

```bash
php artisan test tests/Feature/StudentAcademicHistoryTest.php
```
Expected: 5 tests, 5 passed.

- [ ] **Step 6: Commit**

```bash
git add server/tests/Feature/StudentAcademicHistoryTest.php \
        server/app/Http/Controllers/Api/StudentAcademicHistoryController.php \
        server/routes/api.php
git commit -m "feat: academic history CRUD routes and tests"
```

---

### Task 4: Extra-Curriculars — test, controller, routes

- [ ] **Step 1: Write the failing feature test**

`server/tests/Feature/StudentExtraCurricularsTest.php`:
```php
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
```

- [ ] **Step 2: Run test — expect failure**

```bash
php artisan test tests/Feature/StudentExtraCurricularsTest.php
```
Expected: All 5 tests FAIL with 404.

- [ ] **Step 3: Create controller**

`server/app/Http/Controllers/Api/StudentExtraCurricularController.php`:
```php
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
```

- [ ] **Step 4: Add routes to api.php**

Add import:
```php
use App\Http\Controllers\Api\StudentExtraCurricularController;
```

Add inside `auth:sanctum` group:
```php
    Route::get('students/{student}/extra-curriculars',                      [StudentExtraCurricularController::class, 'index']);
    Route::post('students/{student}/extra-curriculars',                     [StudentExtraCurricularController::class, 'store']);
    Route::put('students/{student}/extra-curriculars/{extraCurricular}',    [StudentExtraCurricularController::class, 'update']);
    Route::delete('students/{student}/extra-curriculars/{extraCurricular}', [StudentExtraCurricularController::class, 'destroy']);
```

- [ ] **Step 5: Run test — expect all pass**

```bash
php artisan test tests/Feature/StudentExtraCurricularsTest.php
```
Expected: 5 tests, 5 passed.

- [ ] **Step 6: Commit**

```bash
git add server/tests/Feature/StudentExtraCurricularsTest.php \
        server/app/Http/Controllers/Api/StudentExtraCurricularController.php \
        server/routes/api.php
git commit -m "feat: extra-curriculars CRUD routes and tests"
```

---

### Task 5: Violations — test, controller, routes

- [ ] **Step 1: Write the failing feature test**

`server/tests/Feature/StudentViolationsTest.php`:
```php
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
```

- [ ] **Step 2: Run test — expect failure**

```bash
php artisan test tests/Feature/StudentViolationsTest.php
```
Expected: All 5 tests FAIL with 404.

- [ ] **Step 3: Create controller**

`server/app/Http/Controllers/Api/StudentViolationController.php`:
```php
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
```

- [ ] **Step 4: Add routes to api.php**

Add import:
```php
use App\Http\Controllers\Api\StudentViolationController;
```

Add inside `auth:sanctum` group:
```php
    Route::get('students/{student}/violations',               [StudentViolationController::class, 'index']);
    Route::post('students/{student}/violations',              [StudentViolationController::class, 'store']);
    Route::put('students/{student}/violations/{violation}',   [StudentViolationController::class, 'update']);
    Route::delete('students/{student}/violations/{violation}',[StudentViolationController::class, 'destroy']);
```

- [ ] **Step 5: Run test — expect all pass**

```bash
php artisan test tests/Feature/StudentViolationsTest.php
```
Expected: 5 tests, 5 passed.

- [ ] **Step 6: Commit**

```bash
git add server/tests/Feature/StudentViolationsTest.php \
        server/app/Http/Controllers/Api/StudentViolationController.php \
        server/routes/api.php
git commit -m "feat: violations CRUD routes and tests"
```

---

### Task 6: Skills — test, controller, routes

- [ ] **Step 1: Write the failing feature test**

`server/tests/Feature/StudentSkillsTest.php`:
```php
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
```

- [ ] **Step 2: Run test — expect failure**

```bash
php artisan test tests/Feature/StudentSkillsTest.php
```
Expected: All 5 tests FAIL with 404.

- [ ] **Step 3: Create controller**

`server/app/Http/Controllers/Api/StudentSkillController.php`:
```php
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
```

- [ ] **Step 4: Add routes to api.php**

Add import:
```php
use App\Http\Controllers\Api\StudentSkillController;
```

Add inside `auth:sanctum` group:
```php
    Route::get('students/{student}/skills',           [StudentSkillController::class, 'index']);
    Route::post('students/{student}/skills',          [StudentSkillController::class, 'store']);
    Route::put('students/{student}/skills/{skill}',   [StudentSkillController::class, 'update']);
    Route::delete('students/{student}/skills/{skill}',[StudentSkillController::class, 'destroy']);
```

- [ ] **Step 5: Run test — expect all pass**

```bash
php artisan test tests/Feature/StudentSkillsTest.php
```
Expected: 5 tests, 5 passed.

- [ ] **Step 6: Commit**

```bash
git add server/tests/Feature/StudentSkillsTest.php \
        server/app/Http/Controllers/Api/StudentSkillController.php \
        server/routes/api.php
git commit -m "feat: skills CRUD routes and tests"
```

---

### Task 7: Organizations — test, controller, routes

- [ ] **Step 1: Write the failing feature test**

`server/tests/Feature/StudentOrganizationsTest.php`:
```php
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
```

- [ ] **Step 2: Run test — expect failure**

```bash
php artisan test tests/Feature/StudentOrganizationsTest.php
```
Expected: All 5 tests FAIL with 404.

- [ ] **Step 3: Create controller**

`server/app/Http/Controllers/Api/StudentOrganizationController.php`:
```php
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
```

- [ ] **Step 4: Add routes to api.php**

Add import:
```php
use App\Http\Controllers\Api\StudentOrganizationController;
```

Add inside `auth:sanctum` group:
```php
    Route::get('students/{student}/organizations',                 [StudentOrganizationController::class, 'index']);
    Route::post('students/{student}/organizations',                [StudentOrganizationController::class, 'store']);
    Route::put('students/{student}/organizations/{organization}',  [StudentOrganizationController::class, 'update']);
    Route::delete('students/{student}/organizations/{organization}',[StudentOrganizationController::class, 'destroy']);
```

- [ ] **Step 5: Run all tests — confirm full suite passes**

```bash
php artisan test
```
Expected: All tests pass including 25 new tests across the 5 feature test files.

- [ ] **Step 6: Commit**

```bash
git add server/tests/Feature/StudentOrganizationsTest.php \
        server/app/Http/Controllers/Api/StudentOrganizationController.php \
        server/routes/api.php
git commit -m "feat: organizations CRUD routes and tests"
```

---

### Task 8: Update StudentProfile.jsx with 5 CRUD sections

**File:** `client/src/pages/San Jose/students/StudentProfile.jsx`

This task replaces the entire file. All existing functionality is preserved.

- [ ] **Step 1: Replace StudentProfile.jsx with the full new version**

`client/src/pages/San Jose/students/StudentProfile.jsx`:
```jsx
import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useApp } from '../../../context/AppContext';
import { useToast } from '../../../context/ToastContext';
import { api } from '../../../services/api';
import { Modal, FormInput } from '../../../components';
import {
  MailIcon, PhoneIcon, GraduationIcon, SearchIcon,
  PlusIcon, EditIcon, TrashIcon,
} from '../../../components/common/Icons';

const emptyAcademic  = { level: 'elementary', schoolName: '', address: '', yearGraduated: '', honors: '' };
const emptyEC        = { name: '', role: '', organization: '', startYear: '', endYear: '' };
const emptyViolation = { description: '', date: '', penalty: '', status: 'pending', remarks: '' };
const emptySkill     = { name: '', category: '', proficiency: '', description: '' };
const emptyOrg       = { organizationName: '', position: '', type: '', startYear: '', endYear: '', isActive: true };

const SubSection = ({ title, onAdd, children }) => (
  <div className="profile-section" style={{ marginBottom: '24px' }}>
    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px' }}>
      <h3 className="profile-section-title" style={{ margin: 0 }}>{title}</h3>
      <button className="btn btn-primary" style={{ padding: '6px 12px', fontSize: '12px' }} onClick={onAdd}>
        <PlusIcon size={13} /> Add
      </button>
    </div>
    {children}
  </div>
);

const EmptyRow = ({ message }) => (
  <p style={{ color: 'var(--text-secondary)', fontSize: '13px', margin: '8px 0' }}>{message}</p>
);

const RecordRow = ({ children, onEdit, onDelete }) => (
  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', padding: '8px 0', borderBottom: '1px solid var(--border)' }}>
    <div style={{ flex: 1 }}>{children}</div>
    <div style={{ display: 'flex', gap: '8px', marginLeft: '12px' }}>
      <button className="btn btn-ghost" style={{ padding: '4px 8px' }} onClick={onEdit}><EditIcon size={14} /></button>
      <button className="btn btn-ghost" style={{ padding: '4px 8px', color: 'var(--error)' }} onClick={onDelete}><TrashIcon size={14} /></button>
    </div>
  </div>
);

const DeleteConfirm = ({ isOpen, onClose, onConfirm, label }) => (
  <Modal isOpen={isOpen} onClose={onClose} title="Confirm Delete"
    footer={<><button className="btn btn-secondary" onClick={onClose}>Cancel</button><button className="btn btn-danger" onClick={onConfirm}>Delete</button></>}
  >
    <p>Are you sure you want to delete <strong>{label}</strong>? This action cannot be undone.</p>
  </Modal>
);

const StudentProfile = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const { students, courses, fetchStudents, fetchCourses } = useApp();
  const { showToast } = useToast();
  const [loading, setLoading] = useState(true);

  const [academicHistory, setAcademicHistory]   = useState([]);
  const [extraCurriculars, setExtraCurriculars] = useState([]);
  const [violations, setViolations]             = useState([]);
  const [skills, setSkills]                     = useState([]);
  const [organizations, setOrganizations]       = useState([]);

  const [academicModal, setAcademicModal]       = useState({ open: false, item: null, form: emptyAcademic, deleting: null });
  const [ecModal, setEcModal]                   = useState({ open: false, item: null, form: emptyEC, deleting: null });
  const [violationModal, setViolationModal]     = useState({ open: false, item: null, form: emptyViolation, deleting: null });
  const [skillModal, setSkillModal]             = useState({ open: false, item: null, form: emptySkill, deleting: null });
  const [orgModal, setOrgModal]                 = useState({ open: false, item: null, form: emptyOrg, deleting: null });

  useEffect(() => {
    Promise.all([
      students.length === 0 ? fetchStudents() : Promise.resolve(),
      courses.length === 0  ? fetchCourses()  : Promise.resolve(),
      api.get(`/students/${id}/academic-history`).then(setAcademicHistory).catch(() => {}),
      api.get(`/students/${id}/extra-curriculars`).then(setExtraCurriculars).catch(() => {}),
      api.get(`/students/${id}/violations`).then(setViolations).catch(() => {}),
      api.get(`/students/${id}/skills`).then(setSkills).catch(() => {}),
      api.get(`/students/${id}/organizations`).then(setOrganizations).catch(() => {}),
    ]).finally(() => setLoading(false));
  }, [id]);

  const openAdd  = (setter, empty) => setter(s => ({ ...s, open: true, item: null, form: { ...empty } }));
  const openEdit = (setter, item, toForm) => setter(s => ({ ...s, open: true, item, form: toForm(item) }));
  const closeModal = (setter) => setter(s => ({ ...s, open: false, item: null }));
  const setForm  = (setter, field, value) => setter(s => ({ ...s, form: { ...s.form, [field]: value } }));
  const openDel  = (setter, item) => setter(s => ({ ...s, deleting: item }));
  const closeDel = (setter) => setter(s => ({ ...s, deleting: null }));

  const handleAcademicSubmit = async () => {
    const f = academicModal.form;
    if (!f.schoolName.trim()) { showToast('School name is required', 'error'); return; }
    try {
      if (academicModal.item) {
        const updated = await api.put(`/students/${id}/academic-history/${academicModal.item.id}`, f);
        setAcademicHistory(prev => prev.map(h => h.id === updated.id ? updated : h));
        showToast('Academic history updated', 'success');
      } else {
        const created = await api.post(`/students/${id}/academic-history`, f);
        setAcademicHistory(prev => [...prev, created]);
        showToast('Academic history added', 'success');
      }
      closeModal(setAcademicModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };
  const handleAcademicDelete = async () => {
    try {
      await api.delete(`/students/${id}/academic-history/${academicModal.deleting.id}`);
      setAcademicHistory(prev => prev.filter(h => h.id !== academicModal.deleting.id));
      showToast('Deleted', 'success'); closeDel(setAcademicModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };

  const handleEcSubmit = async () => {
    const f = ecModal.form;
    if (!f.name.trim()) { showToast('Activity name is required', 'error'); return; }
    try {
      if (ecModal.item) {
        const updated = await api.put(`/students/${id}/extra-curriculars/${ecModal.item.id}`, f);
        setExtraCurriculars(prev => prev.map(e => e.id === updated.id ? updated : e));
        showToast('Activity updated', 'success');
      } else {
        const created = await api.post(`/students/${id}/extra-curriculars`, f);
        setExtraCurriculars(prev => [...prev, created]);
        showToast('Activity added', 'success');
      }
      closeModal(setEcModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };
  const handleEcDelete = async () => {
    try {
      await api.delete(`/students/${id}/extra-curriculars/${ecModal.deleting.id}`);
      setExtraCurriculars(prev => prev.filter(e => e.id !== ecModal.deleting.id));
      showToast('Deleted', 'success'); closeDel(setEcModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };

  const handleViolationSubmit = async () => {
    const f = violationModal.form;
    if (!f.description.trim()) { showToast('Description is required', 'error'); return; }
    if (!f.date) { showToast('Date is required', 'error'); return; }
    try {
      if (violationModal.item) {
        const updated = await api.put(`/students/${id}/violations/${violationModal.item.id}`, f);
        setViolations(prev => prev.map(v => v.id === updated.id ? updated : v));
        showToast('Violation updated', 'success');
      } else {
        const created = await api.post(`/students/${id}/violations`, f);
        setViolations(prev => [...prev, created]);
        showToast('Violation recorded', 'success');
      }
      closeModal(setViolationModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };
  const handleViolationDelete = async () => {
    try {
      await api.delete(`/students/${id}/violations/${violationModal.deleting.id}`);
      setViolations(prev => prev.filter(v => v.id !== violationModal.deleting.id));
      showToast('Deleted', 'success'); closeDel(setViolationModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };

  const handleSkillSubmit = async () => {
    const f = skillModal.form;
    if (!f.name.trim()) { showToast('Skill name is required', 'error'); return; }
    try {
      if (skillModal.item) {
        const updated = await api.put(`/students/${id}/skills/${skillModal.item.id}`, f);
        setSkills(prev => prev.map(s => s.id === updated.id ? updated : s));
        showToast('Skill updated', 'success');
      } else {
        const created = await api.post(`/students/${id}/skills`, f);
        setSkills(prev => [...prev, created]);
        showToast('Skill added', 'success');
      }
      closeModal(setSkillModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };
  const handleSkillDelete = async () => {
    try {
      await api.delete(`/students/${id}/skills/${skillModal.deleting.id}`);
      setSkills(prev => prev.filter(s => s.id !== skillModal.deleting.id));
      showToast('Deleted', 'success'); closeDel(setSkillModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };

  const handleOrgSubmit = async () => {
    const f = orgModal.form;
    if (!f.organizationName.trim()) { showToast('Organization name is required', 'error'); return; }
    try {
      if (orgModal.item) {
        const updated = await api.put(`/students/${id}/organizations/${orgModal.item.id}`, f);
        setOrganizations(prev => prev.map(o => o.id === updated.id ? updated : o));
        showToast('Organization updated', 'success');
      } else {
        const created = await api.post(`/students/${id}/organizations`, f);
        setOrganizations(prev => [...prev, created]);
        showToast('Organization added', 'success');
      }
      closeModal(setOrgModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };
  const handleOrgDelete = async () => {
    try {
      await api.delete(`/students/${id}/organizations/${orgModal.deleting.id}`);
      setOrganizations(prev => prev.filter(o => o.id !== orgModal.deleting.id));
      showToast('Deleted', 'success'); closeDel(setOrgModal);
    } catch (err) { showToast(err.message || 'Something went wrong', 'error'); }
  };

  if (loading) {
    return (
      <div className="fade-in">
        <div className="page-header">
          <div className="page-header-left">
            <div className="skeleton-cell" style={{ width: '140px', height: '36px', borderRadius: '8px' }} />
          </div>
        </div>
        <div className="profile-header">
          <div className="profile-header-content">
            <div className="skeleton-cell" style={{ width: '72px', height: '72px', borderRadius: '50%' }} />
            <div style={{ flex: 1, display: 'flex', flexDirection: 'column', gap: '10px' }}>
              <div className="skeleton-cell" style={{ width: '200px', height: '24px' }} />
              <div className="skeleton-cell" style={{ width: '100px', height: '16px' }} />
              <div className="skeleton-cell" style={{ width: '300px', height: '16px' }} />
            </div>
          </div>
        </div>
        <div className="profile-body">
          {[...Array(6)].map((_, i) => (
            <div key={i} className="profile-detail">
              <div className="skeleton-cell" style={{ width: '120px', height: '14px' }} />
              <div className="skeleton-cell" style={{ width: '200px', height: '14px' }} />
            </div>
          ))}
        </div>
      </div>
    );
  }

  const student = students.find(s => String(s.id) === id);

  if (!student) {
    return (
      <div className="fade-in">
        <div className="card">
          <div className="empty-state">
            <div className="empty-state-icon"><SearchIcon size={40} stroke="#94a3b8" /></div>
            <h3 className="empty-state-title">Student not found</h3>
            <p className="empty-state-description">The student you're looking for doesn't exist.</p>
            <button className="btn btn-primary" onClick={() => navigate('/students')}>Back to Students</button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="fade-in">
      <div className="page-header">
        <div className="page-header-left">
          <button className="btn btn-ghost" onClick={() => navigate('/students')}>← Back to Students</button>
        </div>
      </div>

      <div className="profile-header">
        <div className="profile-header-content">
          <div className="profile-avatar">{student.firstName[0]}{student.lastName[0]}</div>
          <div className="profile-info">
            <h1 className="profile-name">{student.firstName} {student.lastName}</h1>
            <div className="profile-id">{student.id}</div>
            <div className="profile-meta">
              <span className="profile-meta-item"><MailIcon size={14} /> {student.email}</span>
              <span className="profile-meta-item"><PhoneIcon size={14} /> {student.phone}</span>
              <span className="profile-meta-item"><GraduationIcon size={14} /> Year {student.year} - Section {student.section}</span>
            </div>
          </div>
          <span className={`badge ${student.status === 'active' ? 'badge-success' : 'badge-error'}`} style={{ fontSize: '14px', padding: '8px 16px' }}>
            {student.status}
          </span>
        </div>
      </div>

      <div className="profile-body">
        <div className="profile-section">
          <h3 className="profile-section-title">Personal Information</h3>
          <div className="profile-detail"><span className="profile-detail-label">Full Name</span><span className="profile-detail-value">{student.firstName} {student.lastName}</span></div>
          <div className="profile-detail"><span className="profile-detail-label">Email</span><span className="profile-detail-value">{student.email}</span></div>
          <div className="profile-detail"><span className="profile-detail-label">Phone</span><span className="profile-detail-value">{student.phone || 'Not provided'}</span></div>
          <div className="profile-detail"><span className="profile-detail-label">Address</span><span className="profile-detail-value">{student.address || 'Not provided'}</span></div>
          <div className="profile-detail"><span className="profile-detail-label">Birthday</span><span className="profile-detail-value">{student.birthday || 'Not provided'}</span></div>
        </div>

        <div>
          <div className="profile-section" style={{ marginBottom: '24px' }}>
            <h3 className="profile-section-title">Academic Information</h3>
            <div className="profile-detail"><span className="profile-detail-label">Student ID</span><span className="profile-detail-value">{student.id}</span></div>
            <div className="profile-detail"><span className="profile-detail-label">Year</span><span className="profile-detail-value">{student.year}</span></div>
            <div className="profile-detail"><span className="profile-detail-label">Section</span><span className="profile-detail-value">{student.section}</span></div>
            <div className="profile-detail">
              <span className="profile-detail-label">Status</span>
              <span className="profile-detail-value">
                <span className={`badge ${student.status === 'active' ? 'badge-success' : 'badge-error'}`}>{student.status}</span>
              </span>
            </div>
            <div className="profile-detail"><span className="profile-detail-label">Enrolled Date</span><span className="profile-detail-value">{student.enrolledDate}</span></div>
          </div>

          {/* Academic History */}
          <SubSection title="Academic History" onAdd={() => openAdd(setAcademicModal, emptyAcademic)}>
            {academicHistory.length === 0 ? <EmptyRow message="No academic history records." /> :
              academicHistory.map(h => (
                <RecordRow key={h.id}
                  onEdit={() => openEdit(setAcademicModal, h, x => ({ level: x.level, schoolName: x.schoolName, address: x.address || '', yearGraduated: x.yearGraduated || '', honors: x.honors || '' }))}
                  onDelete={() => openDel(setAcademicModal, h)}
                >
                  <div style={{ fontWeight: 500 }}>{h.schoolName}</div>
                  <div style={{ fontSize: '12px', color: 'var(--text-secondary)' }}>
                    {h.level === 'elementary' ? 'Elementary' : 'High School'}
                    {h.yearGraduated ? ` · Graduated ${h.yearGraduated}` : ''}
                    {h.honors ? ` · ${h.honors}` : ''}
                  </div>
                </RecordRow>
              ))
            }
          </SubSection>

          {/* Extra-Curricular Activities */}
          <SubSection title="Extra-Curricular Activities" onAdd={() => openAdd(setEcModal, emptyEC)}>
            {extraCurriculars.length === 0 ? <EmptyRow message="No extra-curricular activities." /> :
              extraCurriculars.map(e => (
                <RecordRow key={e.id}
                  onEdit={() => openEdit(setEcModal, e, x => ({ name: x.name, role: x.role || '', organization: x.organization || '', startYear: x.startYear || '', endYear: x.endYear || '' }))}
                  onDelete={() => openDel(setEcModal, e)}
                >
                  <div style={{ fontWeight: 500 }}>{e.name}</div>
                  <div style={{ fontSize: '12px', color: 'var(--text-secondary)' }}>
                    {[e.role, e.organization, e.startYear ? `${e.startYear}${e.endYear ? `–${e.endYear}` : '–present'}` : null].filter(Boolean).join(' · ')}
                  </div>
                </RecordRow>
              ))
            }
          </SubSection>

          {/* Violations */}
          <SubSection title="Violations" onAdd={() => openAdd(setViolationModal, emptyViolation)}>
            {violations.length === 0 ? <EmptyRow message="No violation records." /> :
              violations.map(v => (
                <RecordRow key={v.id}
                  onEdit={() => openEdit(setViolationModal, v, x => ({ description: x.description, date: x.date, penalty: x.penalty || '', status: x.status, remarks: x.remarks || '' }))}
                  onDelete={() => openDel(setViolationModal, v)}
                >
                  <div style={{ fontWeight: 500 }}>{v.description}</div>
                  <div style={{ fontSize: '12px', color: 'var(--text-secondary)', display: 'flex', alignItems: 'center', gap: '6px' }}>
                    {v.date}{v.penalty ? ` · ${v.penalty}` : ''} ·
                    <span className={`badge ${v.status === 'resolved' ? 'badge-success' : v.status === 'dismissed' ? 'badge-warning' : 'badge-error'}`} style={{ fontSize: '11px' }}>{v.status}</span>
                  </div>
                </RecordRow>
              ))
            }
          </SubSection>

          {/* Skills */}
          <SubSection title="Skills" onAdd={() => openAdd(setSkillModal, emptySkill)}>
            {skills.length === 0 ? <EmptyRow message="No skills recorded." /> :
              skills.map(s => (
                <RecordRow key={s.id}
                  onEdit={() => openEdit(setSkillModal, s, x => ({ name: x.name, category: x.category || '', proficiency: x.proficiency || '', description: x.description || '' }))}
                  onDelete={() => openDel(setSkillModal, s)}
                >
                  <div style={{ fontWeight: 500 }}>{s.name}</div>
                  <div style={{ fontSize: '12px', color: 'var(--text-secondary)' }}>
                    {[s.category, s.proficiency].filter(Boolean).join(' · ')}
                  </div>
                </RecordRow>
              ))
            }
          </SubSection>

          {/* Organization Affiliations */}
          <SubSection title="Organization Affiliations" onAdd={() => openAdd(setOrgModal, emptyOrg)}>
            {organizations.length === 0 ? <EmptyRow message="No organization affiliations." /> :
              organizations.map(o => (
                <RecordRow key={o.id}
                  onEdit={() => openEdit(setOrgModal, o, x => ({ organizationName: x.organizationName, position: x.position || '', type: x.type || '', startYear: x.startYear || '', endYear: x.endYear || '', isActive: x.isActive }))}
                  onDelete={() => openDel(setOrgModal, o)}
                >
                  <div style={{ fontWeight: 500 }}>{o.organizationName}</div>
                  <div style={{ fontSize: '12px', color: 'var(--text-secondary)', display: 'flex', alignItems: 'center', gap: '6px' }}>
                    {[o.position, o.type, o.startYear ? `${o.startYear}${o.endYear ? `–${o.endYear}` : '–present'}` : null].filter(Boolean).join(' · ')}
                    {' · '}
                    <span className={`badge ${o.isActive ? 'badge-success' : 'badge-error'}`} style={{ fontSize: '11px' }}>{o.isActive ? 'Active' : 'Inactive'}</span>
                  </div>
                </RecordRow>
              ))
            }
          </SubSection>
        </div>
      </div>

      {/* Academic History Modal */}
      <Modal isOpen={academicModal.open} onClose={() => closeModal(setAcademicModal)}
        title={academicModal.item ? 'Edit Academic History' : 'Add Academic History'}
        footer={<><button className="btn btn-secondary" onClick={() => closeModal(setAcademicModal)}>Cancel</button><button className="btn btn-primary" onClick={handleAcademicSubmit}>{academicModal.item ? 'Update' : 'Add'}</button></>}
      >
        <FormInput label="Level" name="level" type="select" value={academicModal.form.level}
          onChange={e => setForm(setAcademicModal, 'level', e.target.value)} required
          options={[{ value: 'elementary', label: 'Elementary' }, { value: 'high_school', label: 'High School' }]}
        />
        <FormInput label="School Name" name="schoolName" value={academicModal.form.schoolName}
          onChange={e => setForm(setAcademicModal, 'schoolName', e.target.value)} required />
        <FormInput label="Address" name="address" value={academicModal.form.address}
          onChange={e => setForm(setAcademicModal, 'address', e.target.value)} />
        <div className="form-row">
          <FormInput label="Year Graduated" name="yearGraduated" type="number" value={academicModal.form.yearGraduated}
            onChange={e => setForm(setAcademicModal, 'yearGraduated', e.target.value)} placeholder="e.g. 2020" />
          <FormInput label="Honors" name="honors" value={academicModal.form.honors}
            onChange={e => setForm(setAcademicModal, 'honors', e.target.value)} placeholder="e.g. Valedictorian" />
        </div>
      </Modal>
      <DeleteConfirm isOpen={!!academicModal.deleting} onClose={() => closeDel(setAcademicModal)}
        onConfirm={handleAcademicDelete} label={academicModal.deleting?.schoolName} />

      {/* Extra-Curricular Modal */}
      <Modal isOpen={ecModal.open} onClose={() => closeModal(setEcModal)}
        title={ecModal.item ? 'Edit Activity' : 'Add Activity'}
        footer={<><button className="btn btn-secondary" onClick={() => closeModal(setEcModal)}>Cancel</button><button className="btn btn-primary" onClick={handleEcSubmit}>{ecModal.item ? 'Update' : 'Add'}</button></>}
      >
        <FormInput label="Activity Name" name="name" value={ecModal.form.name}
          onChange={e => setForm(setEcModal, 'name', e.target.value)} required />
        <div className="form-row">
          <FormInput label="Role" name="role" value={ecModal.form.role}
            onChange={e => setForm(setEcModal, 'role', e.target.value)} placeholder="e.g. President" />
          <FormInput label="Organization" name="organization" value={ecModal.form.organization}
            onChange={e => setForm(setEcModal, 'organization', e.target.value)} />
        </div>
        <div className="form-row">
          <FormInput label="Start Year" name="startYear" type="number" value={ecModal.form.startYear}
            onChange={e => setForm(setEcModal, 'startYear', e.target.value)} placeholder="e.g. 2023" />
          <FormInput label="End Year" name="endYear" type="number" value={ecModal.form.endYear}
            onChange={e => setForm(setEcModal, 'endYear', e.target.value)} placeholder="e.g. 2024" />
        </div>
      </Modal>
      <DeleteConfirm isOpen={!!ecModal.deleting} onClose={() => closeDel(setEcModal)}
        onConfirm={handleEcDelete} label={ecModal.deleting?.name} />

      {/* Violation Modal */}
      <Modal isOpen={violationModal.open} onClose={() => closeModal(setViolationModal)}
        title={violationModal.item ? 'Edit Violation' : 'Add Violation'}
        footer={<><button className="btn btn-secondary" onClick={() => closeModal(setViolationModal)}>Cancel</button><button className="btn btn-primary" onClick={handleViolationSubmit}>{violationModal.item ? 'Update' : 'Add'}</button></>}
      >
        <FormInput label="Description" name="description" type="textarea" value={violationModal.form.description}
          onChange={e => setForm(setViolationModal, 'description', e.target.value)} required />
        <div className="form-row">
          <FormInput label="Date" name="date" type="date" value={violationModal.form.date}
            onChange={e => setForm(setViolationModal, 'date', e.target.value)} required />
          <FormInput label="Status" name="status" type="select" value={violationModal.form.status}
            onChange={e => setForm(setViolationModal, 'status', e.target.value)}
            options={[{ value: 'pending', label: 'Pending' }, { value: 'resolved', label: 'Resolved' }, { value: 'dismissed', label: 'Dismissed' }]}
          />
        </div>
        <FormInput label="Penalty" name="penalty" value={violationModal.form.penalty}
          onChange={e => setForm(setViolationModal, 'penalty', e.target.value)} placeholder="e.g. Written reprimand" />
        <FormInput label="Remarks" name="remarks" type="textarea" value={violationModal.form.remarks}
          onChange={e => setForm(setViolationModal, 'remarks', e.target.value)} />
      </Modal>
      <DeleteConfirm isOpen={!!violationModal.deleting} onClose={() => closeDel(setViolationModal)}
        onConfirm={handleViolationDelete} label={violationModal.deleting?.description?.slice(0, 40)} />

      {/* Skill Modal */}
      <Modal isOpen={skillModal.open} onClose={() => closeModal(setSkillModal)}
        title={skillModal.item ? 'Edit Skill' : 'Add Skill'}
        footer={<><button className="btn btn-secondary" onClick={() => closeModal(setSkillModal)}>Cancel</button><button className="btn btn-primary" onClick={handleSkillSubmit}>{skillModal.item ? 'Update' : 'Add'}</button></>}
      >
        <div className="form-row">
          <FormInput label="Skill Name" name="name" value={skillModal.form.name}
            onChange={e => setForm(setSkillModal, 'name', e.target.value)} required />
          <FormInput label="Category" name="category" value={skillModal.form.category}
            onChange={e => setForm(setSkillModal, 'category', e.target.value)} placeholder="e.g. Technical" />
        </div>
        <FormInput label="Proficiency" name="proficiency" type="select" value={skillModal.form.proficiency}
          onChange={e => setForm(setSkillModal, 'proficiency', e.target.value)}
          options={[{ value: 'beginner', label: 'Beginner' }, { value: 'intermediate', label: 'Intermediate' }, { value: 'advanced', label: 'Advanced' }]}
        />
        <FormInput label="Description" name="description" type="textarea" value={skillModal.form.description}
          onChange={e => setForm(setSkillModal, 'description', e.target.value)} />
      </Modal>
      <DeleteConfirm isOpen={!!skillModal.deleting} onClose={() => closeDel(setSkillModal)}
        onConfirm={handleSkillDelete} label={skillModal.deleting?.name} />

      {/* Organization Modal */}
      <Modal isOpen={orgModal.open} onClose={() => closeModal(setOrgModal)}
        title={orgModal.item ? 'Edit Organization' : 'Add Organization'}
        footer={<><button className="btn btn-secondary" onClick={() => closeModal(setOrgModal)}>Cancel</button><button className="btn btn-primary" onClick={handleOrgSubmit}>{orgModal.item ? 'Update' : 'Add'}</button></>}
      >
        <FormInput label="Organization Name" name="organizationName" value={orgModal.form.organizationName}
          onChange={e => setForm(setOrgModal, 'organizationName', e.target.value)} required />
        <div className="form-row">
          <FormInput label="Position" name="position" value={orgModal.form.position}
            onChange={e => setForm(setOrgModal, 'position', e.target.value)} placeholder="e.g. President" />
          <FormInput label="Type" name="type" value={orgModal.form.type}
            onChange={e => setForm(setOrgModal, 'type', e.target.value)} placeholder="e.g. Academic" />
        </div>
        <div className="form-row">
          <FormInput label="Start Year" name="startYear" type="number" value={orgModal.form.startYear}
            onChange={e => setForm(setOrgModal, 'startYear', e.target.value)} placeholder="e.g. 2023" />
          <FormInput label="End Year" name="endYear" type="number" value={orgModal.form.endYear}
            onChange={e => setForm(setOrgModal, 'endYear', e.target.value)} placeholder="e.g. 2024" />
        </div>
        <FormInput label="Status" name="isActive" type="select" value={orgModal.form.isActive ? 'true' : 'false'}
          onChange={e => setForm(setOrgModal, 'isActive', e.target.value === 'true')}
          options={[{ value: 'true', label: 'Active' }, { value: 'false', label: 'Inactive' }]}
        />
      </Modal>
      <DeleteConfirm isOpen={!!orgModal.deleting} onClose={() => closeDel(setOrgModal)}
        onConfirm={handleOrgDelete} label={orgModal.deleting?.organizationName} />
    </div>
  );
};

export default StudentProfile;
```

- [ ] **Step 2: Verify build**

From `client/`:
```bash
npm run build
```
Expected: Exits with 0 errors.

- [ ] **Step 3: Manual test in browser**

Start the dev server (`npm run dev` from `client/`) and the Laravel server (`php artisan serve` from `server/`).

1. Log in, navigate to any student profile (`/students/:id`).
2. Confirm 5 new sections appear below Academic Information: Academic History, Extra-Curricular Activities, Violations, Skills, Organization Affiliations.
3. For each section: click Add, fill the required fields, submit — record appears in the list.
4. Click the pencil icon on a record — modal opens pre-filled. Edit and save — list updates.
5. Click the trash icon — confirm modal appears. Confirm — record is removed.

- [ ] **Step 4: Commit**

```bash
git add "client/src/pages/San Jose/students/StudentProfile.jsx"
git commit -m "feat: add 5 CRUD sections to StudentProfile"
```
