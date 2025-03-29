<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->string('size')->nullable()->after('industry');
            $table->year('founded_year')->nullable()->after('size');
            $table->string('linkedin_url')->nullable()->after('founded_year');
            $table->string('logo')->nullable()->after('linkedin_url');
        });
    }

    public function down()
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropColumn(['size', 'founded_year', 'linkedin_url', 'logo']);
        });
    }
}; 