<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emplois_id')->constrained()->onDelete('cascade'); // Job that was applied for
            $table->foreignId('chercheurs_id')->constrained()->onDelete('cascade'); // Applicant
            $table->string('resume')->nullable();
            $table->string('resume_path')->nullable();
            $table->text('cover_letter')->nullable(); // Optional cover letter
            $table->json('answers')->nullable();
            $table->string('current_company')->nullable();
            $table->string('current_position')->nullable();
            $table->decimal('current_salary', 10, 2)->nullable();
            $table->string('salary_expectation')->nullable();
            $table->date('available_start_date')->nullable();
            $table->boolean('relocation_willingness')->default(false);
            $table->string('relocation_location')->nullable();
            $table->text('portfolio_url')->nullable();
            $table->text('linkedin_url')->nullable();
            $table->text('github_url')->nullable();
            $table->enum('status', ['draft', 'submitted', 'reviewing', 'shortlisted', 'interviewed', 'offered', 'rejected', 'withdrawn'])
                  ->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
};
