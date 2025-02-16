<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('case_education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->string('university_name');
            $table->year('completion_year');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('case_education');
    }
};
