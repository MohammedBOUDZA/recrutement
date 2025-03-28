<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('emplois', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('salary_type')->nullable(); // hourly, monthly, yearly
            $table->string('employment_type'); // full-time, part-time, contract, temporary
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->boolean('remote')->default(false);
            $table->boolean('hybrid')->default(false);
            $table->boolean('urgent')->default(false);
            $table->integer('views')->default(0);
            $table->integer('applications_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // For job archiving
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('emplois');
    }
};
