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
        Schema::create('background_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('case_id');
            $table->longText('main_academic_field');
            $table->longText('specialization');
            $table->longText('unique_skillset');
            $table->enum('filing_niw', ['yes', 'no']);
            $table->longText('critical_discussion_1');
            $table->longText('critical_discussion_2');
            $table->longText('critical_discussion_3');
            $table->longText('key_issue_1');
            $table->longText('key_issue_2');
            $table->longText('key_issue_2_discussion_field_1');
            $table->longText('key_issue_2_discussion_field_2');
            $table->longText('key_issue_3');
            $table->longText('key_issue_3_discussion_field_1');
            $table->longText('key_issue_3_discussion_field_2');
            $table->longText('benefit_us_issue_1');
            $table->longText('benefit_us_issue_1_discussion_field_1');
            $table->longText('benefit_us_issue_1_discussion_field_2');
            $table->longText('benefit_us_issue_2');
            $table->longText('benefit_us_issue_2_discussion_field_1');
            $table->longText('benefit_us_issue_2_discussion_field_2');
            $table->longText('benefit_us_issue_3');
            $table->longText('benefit_us_issue_3_discussion_field_1');
            $table->longText('benefit_us_issue_3_discussion_field_2');
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();

            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('background_information');
    }
};
