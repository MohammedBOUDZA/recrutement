<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')
                  ->references('id')->on('job_categories')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('job_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
}; 