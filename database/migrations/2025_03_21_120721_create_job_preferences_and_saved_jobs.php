<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('job_search_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('keywords')->nullable();
            $table->string('location')->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('employment_type')->nullable();
            $table->boolean('remote')->default(false);
            $table->boolean('hybrid')->default(false);
            $table->json('categories')->nullable();
            $table->json('skills')->nullable();
            $table->string('experience_level')->nullable();
            $table->string('education_level')->nullable();
            $table->boolean('email_alerts')->default(true);
            $table->timestamps();
        });

        Schema::create('saved_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('emploi_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->boolean('applied')->default(false);
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
        });

        Schema::create('job_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emploi_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_views');
        Schema::dropIfExists('saved_jobs');
        Schema::dropIfExists('job_search_preferences');
    }
}; 