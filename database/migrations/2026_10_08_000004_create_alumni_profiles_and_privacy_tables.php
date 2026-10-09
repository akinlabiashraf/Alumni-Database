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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('avatar')->nullable();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('current_job_title')->nullable();
            $table->string('current_company')->nullable();
            $table->string('industry')->nullable()->index();
            $table->string('work_location')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Nigeria');
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->json('skills')->nullable();
            $table->json('education_history')->nullable();
            $table->json('career_history')->nullable();
            $table->timestamps();
        });

        Schema::create('privacy_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->enum('profile_visibility', ['public', 'alumni_only', 'private'])->default('alumni_only');
            $table->enum('email_visibility', ['public', 'alumni_only', 'private'])->default('private');
            $table->enum('phone_visibility', ['public', 'alumni_only', 'private'])->default('private');
            $table->enum('work_visibility', ['public', 'alumni_only', 'private'])->default('alumni_only');
            $table->enum('location_visibility', ['public', 'alumni_only', 'private'])->default('alumni_only');
            $table->boolean('allow_messaging')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privacy_settings');
        Schema::dropIfExists('profiles');
    }
};
