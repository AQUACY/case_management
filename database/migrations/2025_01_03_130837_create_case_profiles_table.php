<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseProfilesTable extends Migration
{
    public function up(): void
    {
        Schema::create('case_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id'); // Foreign key to cases table
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');

            $table->string('academic_degree')->nullable();
            $table->text('citation_database_link')->nullable();
            $table->string('current_us_position')->nullable();
            $table->string('proposed_employment_us')->nullable();
            $table->enum('same_or_similar_field', ['yes', 'no'])->nullable();
            $table->string('alternative_field_1')->nullable(); // If "no" to the field question
            $table->string('alternative_field_2')->nullable(); // If "no" to the field question
            $table->enum('conduct_research', ['yes', 'no'])->nullable();
            $table->string('ongoing_project_1')->nullable(); // If "yes" to ongoing projects
            $table->string('ongoing_project_2')->nullable(); // If "yes" to ongoing projects
            $table->string('number_of_papers_reviewed')->nullable();
            $table->enum('editor_role', ['yes', 'no'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_profiles');
    }
}
