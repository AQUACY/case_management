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
        Schema::create('recommenders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id'); // Foreign key to the case
            $table->string('name'); // Name of the recommender
            $table->enum('dependent_or_independent', ['dependent', 'independent'])->nullable(); // Dependent or Independent
            $table->string('title')->nullable(); // Title/position
            $table->string('institution')->nullable(); // Institution
            $table->string('country')->nullable(); // Country
            $table->text('faculty_biography_link')->nullable(); // Link to faculty/company biography
            $table->text('google_scholar_link')->nullable(); // Link to Google Scholar profile
            $table->text('relationship')->nullable(); // How the recommender knows the user
            $table->text('projects_discussed')->nullable(); // Projects discussed in the recommendation letter
            $table->boolean('cited_project')->default(false); // Whether the recommender cited a project
            $table->text('cited_project_details')->nullable(); // Details of the citation if cited_project is true
            $table->enum('status', ['pending', 'finalized'])->default('pending'); // Status
            $table->timestamps();

            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommenders');
    }
};
