<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('media_coverages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->string('media_name');
            $table->date('date_published');
            $table->string('author');
            $table->string('outlet_name');
            $table->integer('circulation_count');
            $table->text('article_summary');
            $table->text('work_relevance');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media_coverages');
    }
};
