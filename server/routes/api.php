<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\LaboratoryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\SyllabusController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\CurriculumController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\FacultyAssignmentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\StudentAcademicHistoryController;
use App\Http\Controllers\Api\StudentExtraCurricularController;
use App\Http\Controllers\Api\StudentViolationController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/activities',      [DashboardController::class, 'activities']);

    Route::apiResource('students',            StudentController::class);
    Route::apiResource('faculty',             FacultyController::class);
    Route::apiResource('courses',             CourseController::class);
    Route::apiResource('sections',            SectionController::class);
    Route::apiResource('rooms',               RoomController::class);
    Route::apiResource('laboratories',        LaboratoryController::class);
    Route::apiResource('events',              EventController::class);
    Route::apiResource('syllabi',             SyllabusController::class);
    Route::apiResource('lessons',             LessonController::class);
    Route::apiResource('curricula',           CurriculumController::class);
    Route::apiResource('schedules',           ScheduleController::class);
    Route::apiResource('faculty-assignments', FacultyAssignmentController::class);

    Route::get('students/{student}/academic-history',              [StudentAcademicHistoryController::class, 'index']);
    Route::post('students/{student}/academic-history',             [StudentAcademicHistoryController::class, 'store']);
    Route::put('students/{student}/academic-history/{history}',    [StudentAcademicHistoryController::class, 'update']);
    Route::delete('students/{student}/academic-history/{history}', [StudentAcademicHistoryController::class, 'destroy']);

    Route::get('students/{student}/extra-curriculars',                      [StudentExtraCurricularController::class, 'index']);
    Route::post('students/{student}/extra-curriculars',                     [StudentExtraCurricularController::class, 'store']);
    Route::put('students/{student}/extra-curriculars/{extraCurricular}',    [StudentExtraCurricularController::class, 'update']);
    Route::delete('students/{student}/extra-curriculars/{extraCurricular}', [StudentExtraCurricularController::class, 'destroy']);

    Route::get('students/{student}/violations',               [StudentViolationController::class, 'index']);
    Route::post('students/{student}/violations',              [StudentViolationController::class, 'store']);
    Route::put('students/{student}/violations/{violation}',   [StudentViolationController::class, 'update']);
    Route::delete('students/{student}/violations/{violation}',[StudentViolationController::class, 'destroy']);
});
