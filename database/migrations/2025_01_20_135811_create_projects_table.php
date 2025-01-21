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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->onDelete('cascade');
            $table->text('title_of_project');
            $table->date('date_of_initiation_from');
            $table->date('date_of_initiation_to');
            $table->longText('resulting_publications_1')->nullable();
            $table->longText('resulting_publications_2')->nullable();
            $table->longText('resulting_publications_3')->nullable();
            $table->text('funding_sources_1')->nullable();
            $table->text('funding_sources_2')->nullable();
            $table->text('funding_sources_3')->nullable();
            $table->text('summary_of_work');
            $table->text('niw_project_description')->nullable();
            $table->enum('alignment_with_section_i', ['yes', 'no']);
            $table->text('citation_1')->nullable();
            $table->text('citation_2')->nullable();
            $table->text('citation_3')->nullable();
            $table->text('citation_4')->nullable();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
