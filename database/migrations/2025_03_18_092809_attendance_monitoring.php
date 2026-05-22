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
        // Create accounts table
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('account_id')->primary();

            //Only allow 64 digits in every Validation
            $table->string('first_name', 255);
            $table->string('middle_name', 255)->nullable();
            $table->string('last_name', 255);

            // Allow Max
            $table->string('email', 255)->unique();
            $table->string('password', 255);

            //Allow 6 
            $table->string('otp', 255)->nullable();
            $table->dateTime('otp_expiry')->nullable();
            //255 length for future (encryption)

            $table->enum('role', ['Admin', 'Instructor']);
            $table->timestamps();
        });

        Schema::create('machines', function (Blueprint $table) {
            $table->uuid('machine_id')->primary();
            $table->string('machine_name', 255)->unique();
            $table->string('password', 255);
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->uuid('department_id')->primary();
            $table->string('department_name', 255)->unique();
            $table->string('department_code', 255)->unique();
            $table->string('department_description', 255)->nullable();
            $table->timestamps();
        });


        Schema::create('programs', function (Blueprint $table) {
            $table->uuid('program_id')->primary();
            $table->string('program_name', 255)->unique();
            $table->string('program_code', 255)->unique();
            $table->string('program_description', 255)->nullable();
            $table->uuid('department_id')->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
        });

        
        Schema::create('year_levels', function (Blueprint $table) {
            $table->uuid('year_level_id')->primary();
            $table->string('year_level_name', 255)->unique();
            $table->string('year_level_description', 255)->nullable();
            $table->string('year_level_code', 255)->unique();
            $table->uuid('department_id')->nullable();
            $table->timestamps();


            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
        });

        Schema::create('school_years', function (Blueprint $table){
            $table->uuid('school_year_id')->primary();
            $table->string('school_year_name', 255)->unique();
            $table->string('school_year_description', 255)->nullable();
            $table->string('school_year_code', 255)->unique();
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->uuid('section_id')->primary();
            $table->string('section_name', 255);
            $table->string('section_description', 255)->nullable();
            $table->string('section_code', 255)->unique();
            $table->uuid('program_id')->nullable();
            $table->uuid('department_id')->nullable();
            $table->uuid('year_level_id')->nullable();
            $table->uuid('school_year_id')->nullable();
            $table->timestamps();

            $table->foreign('program_id')->references('program_id')->on('programs')->onDelete('cascade');
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
            $table->foreign('year_level_id')->references('year_level_id')->on('year_levels')->onDelete('cascade');
            $table->foreign('school_year_id')->references('school_year_id')->on('school_years')->onDelete('cascade');
        });

        // Create assignments table (Now Uses UUID)
        Schema::create('assignments', function (Blueprint $table) {
            $table->uuid('assignment_id')->primary();
            $table->uuid('instructor_id');

            $table->uuid('section_id')->nullable();
            $table->uuid('year_level_id')->nullable();
            $table->uuid('program_id')->nullable();
            $table->uuid('department_id')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('instructor_id')->references(columns: 'account_id')->on('accounts')->onDelete('cascade');
            $table->foreign('program_id')->references('program_id')->on('programs')->onDelete('cascade');
            $table->foreign('section_id')->references('section_id')->on('sections')->onDelete('cascade');
            $table->foreign('year_level_id')->references('year_level_id')->on('year_levels')->onDelete('cascade');
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
        });

        Schema::create('student_records', function (Blueprint $table) {
            $table->uuid('record_id')->primary(); // Internal UUID primary key
            $table->string('student_id')->unique(); // Externally assigned student ID

            //Only allow 55 digits in every Validation
            $table->string('first_name', 255);
            $table->string('middle_name', 255)->nullable();
            $table->string('last_name', 255);
            $table->uuid('section_id')->nullable();
            $table->uuid('year_level_id')->nullable();
            $table->uuid('school_year_id')->nullable();
            $table->uuid('program_id')->nullable();
            $table->uuid('department_id')->nullable();

            $table->string('rfid', 255)->unique()->nullable();
            //255 length for future (encryption)

            $table->timestamps();


            $table->foreign('school_year_id')->references('school_year_id')->on('school_years')->onDelete('cascade');
            $table->foreign('section_id')->references('section_id')->on('sections')->onDelete('cascade');
            $table->foreign('year_level_id')->references('year_level_id')->on('year_levels')->onDelete('cascade');
            $table->foreign('program_id')->references('program_id')->on('programs')->onDelete('cascade');
            $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('cascade');
        });

        

        // Create RFID log table (Now Uses UUID)
        Schema::create('rfid_logs', function (Blueprint $table) {
            $table->uuid('rfid_log_id')->primary();
            $table->uuid('record_id');
            $table->enum('action', ['Log-in', 'Log-out']);
            $table->timestamp('scanned_at')->useCurrent();
            $table->string('image', 255)->unique();
            $table->timestamps();

            // Foreign key constraint with UUID
            $table->foreign('record_id')->references('record_id')->on('student_records')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfid_logs');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('student_records');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('year_levels');
        Schema::dropIfExists('machines');
        Schema::dropIfExists('school_years');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('departments');
    }
};

// php artisan migrate:refresh