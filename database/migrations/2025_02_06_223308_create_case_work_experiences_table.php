<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('case_work_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->string('employer_name');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code');
            $table->string('business_type');
            $table->string('job_title');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('hours_worked');
            $table->text('job_details');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('case_work_experiences');
    }
};
