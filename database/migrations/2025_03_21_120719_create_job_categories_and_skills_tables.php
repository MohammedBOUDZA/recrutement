<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('job_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('job_skill', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emploi_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->enum('level', ['required', 'preferred', 'nice_to_have'])->default('required');
            $table->timestamps();
        });

        Schema::create('job_category_emploi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emploi_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_category_emploi');
        Schema::dropIfExists('job_skill');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('job_categories');
    }
}; 