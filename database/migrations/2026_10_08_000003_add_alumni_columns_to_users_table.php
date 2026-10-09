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
        Schema::table('users', function (Blueprint $table) {
            $table->string('matric_number')->nullable()->unique()->after('email');
            $table->string('phone', 30)->nullable()->after('matric_number');
            $table->enum('role', ['alumnus', 'student', 'moderator', 'admin'])->default('alumnus')->after('phone');
            $table->enum('status', ['pending_verification', 'active', 'suspended'])->default('active')->after('role');
            $table->foreignId('graduate_record_id')->nullable()->after('status')->constrained('graduate_records')->nullOnDelete();
            $table->boolean('ndpa_consent')->default(true)->after('graduate_record_id');
            $table->timestamp('ndpa_consent_at')->nullable()->after('ndpa_consent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['graduate_record_id']);
            $table->dropColumn([
                'matric_number',
                'phone',
                'role',
                'status',
                'graduate_record_id',
                'ndpa_consent',
                'ndpa_consent_at',
            ]);
        });
    }
};
