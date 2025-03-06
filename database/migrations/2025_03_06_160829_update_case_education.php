<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('case_education', function (Blueprint $table) {
            $table->string('location');
            $table->string('degree_type');
            $table->string('degree_majors')->nullable();
            $table->string('degree_minors')->nullable();
            $table->year('start_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
