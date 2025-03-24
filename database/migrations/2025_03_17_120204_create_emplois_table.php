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
            $table->foreignId('entreprise_id');
            $table->string('title')->NULL;
            $table->string('description')->NULL;
            $table->string('location')->NULL;
            $table->float('salary')->NULL;
            $table->string('emploi_type')->NULL;
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('emplois');
    }
};
