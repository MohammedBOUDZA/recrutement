<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('draft', 'submitted', 'reviewing', 'shortlisted', 'interviewed', 'offered', 'accepted', 'rejected', 'withdrawn') DEFAULT 'submitted'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('draft', 'submitted', 'reviewing', 'shortlisted', 'interviewed', 'offered', 'rejected', 'withdrawn') DEFAULT 'draft'");
    }
}; 