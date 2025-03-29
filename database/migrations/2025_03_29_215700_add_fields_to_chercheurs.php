<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chercheurs', function (Blueprint $table) {
            $table->string('title')->nullable()->after('user_id');
            $table->text('bio')->nullable()->after('title');
            $table->string('education_level')->nullable()->after('experience');
            $table->decimal('current_salary', 10, 2)->nullable()->after('education_level');
            $table->decimal('expected_salary', 10, 2)->nullable()->after('current_salary');
            $table->string('availability')->nullable()->after('expected_salary');
            $table->string('linkedin_url')->nullable()->after('availability');
            $table->string('github_url')->nullable()->after('linkedin_url');
            $table->string('portfolio_url')->nullable()->after('github_url');
            $table->string('phone')->nullable()->after('portfolio_url');
            $table->string('location')->nullable()->after('phone');
            $table->boolean('willing_to_relocate')->default(false)->after('location');
            $table->string('remote_preference')->default('office')->after('willing_to_relocate');
        });
    }

    public function down()
    {
        Schema::table('chercheurs', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'bio', 'education_level', 'current_salary', 'expected_salary',
                'availability', 'linkedin_url', 'github_url', 'portfolio_url', 'phone',
                'location', 'willing_to_relocate', 'remote_preference'
            ]);
        });
    }
}; 