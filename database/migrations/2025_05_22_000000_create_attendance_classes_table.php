<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_classes', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('program');
            $table->string('section');
            $table->string('year_level');
            $table->date('date');
            $table->time('time');
            $table->string('attendance_status')->nullable();
            $table->string('instructor')->nullable();
            $table->string('action')->nullable();
            $table->string('image')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_classes');
    }
};
