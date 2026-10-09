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
        Schema::create('graduate_records', function (Blueprint $table) {
            $table->id();
            $table->string('matric_number')->unique()->index();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('full_name')->index();
            $table->foreignId('faculty_id')->constrained('faculties')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('degree');
            $table->string('class_of_degree')->nullable();
            $table->year('graduation_year')->index();
            $table->enum('status', ['unclaimed', 'claimed', 'disputed'])->default('unclaimed')->index();
            $table->foreignId('claimed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('claimed_at')->nullable();
            $table->string('verification_hash')->nullable();
            $table->timestamps();

            $table->fullText('full_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduate_records');
    }
};
