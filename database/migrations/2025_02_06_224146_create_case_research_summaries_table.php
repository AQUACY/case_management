<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('case_research_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->text('field_description');
            $table->text('expertise_description');
            $table->text('work_impact');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('case_research_summaries');
    }
};
