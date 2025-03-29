<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('emplois', function (Blueprint $table) {
            $table->string('experience_level')->nullable()->after('employment_type');
            $table->string('education_level')->nullable()->after('experience_level');
            $table->boolean('featured')->default(false)->after('urgent');
            $table->json('questions')->nullable()->after('featured');
        });
    }

    public function down()
    {
        Schema::table('emplois', function (Blueprint $table) {
            $table->dropColumn(['experience_level', 'education_level', 'featured', 'questions']);
        });
    }
}; 