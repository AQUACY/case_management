<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->string('award_name');
            $table->string('award_recipient');
            $table->string('awarding_institution');
            $table->text('award_criteria');
            $table->text('award_significance');
            $table->integer('number_of_recipients');
            $table->text('competitor_limitations');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('awards');
    }
};
