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
        Schema::create('publication_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->onDelete('cascade');
            $table->integer('peer_reviewed_journal_articles')->default(0);
            $table->longText('notes_peer_reviewed_journal')->nullable();
            $table->integer('peer_reviewed_conference_articles')->default(0);
            $table->longText('notes_peer_reviewed_conference')->nullable();
            $table->integer('conference_abstracts')->default(0);
            $table->longText('notes_conference_abstracts')->nullable();
            $table->integer('pre_prints')->default(0);
            $table->longText('notes_pre_prints')->nullable();
            $table->integer('book_chapters')->default(0);
            $table->longText('notes_book_chapters')->nullable();
            $table->integer('books')->default(0);
            $table->longText('notes_books')->nullable();
            $table->integer('technical_reports')->default(0);
            $table->longText('notes_technical_reports')->nullable();
            $table->integer('granted_patents')->default(0);
            $table->longText('notes_granted_patents')->nullable();
            $table->longText('others')->nullable();
            $table->longText('in_preparation_manuscripts')->nullable();
            $table->longText('research_topic')->nullable();
            $table->longText('significance')->nullable();
            $table->longText('funding_sources')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication_records');
    }
};
