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
