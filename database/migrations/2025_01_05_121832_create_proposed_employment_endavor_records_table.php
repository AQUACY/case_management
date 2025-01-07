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
        Schema::create('proposed_employment_endavor_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->onDelete('cascade'); // Foreign key to cases table
            $table->json('type')->nullable();
            $table->enum('selection', ['yes', 'no'])->nullable(); // Selection for NIW
            $table->text('proposed_endavor_field_1')->nullable();
            $table->text('proposed_endavor_field_2')->nullable();
            $table->text('proposed_endavor_field_3')->nullable();
            $table->text('past_experience')->nullable();
            $table->text('publication_plans')->nullable();
            $table->enum('status', ['pending','review','finalized', 'rejected'])->default('pending');
            // niw records
            $table->enum('currently_student_us_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_academic_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_postdoctoral_niw', ['yes', 'no'])->nullable();
            $table->enum('received_promotion_notice_niw', ['yes', 'no'])->nullable();
            $table->enum('not_promoted_notice_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_medical_resident_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_visiting_scholar_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_us_business_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_outside_us_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_unemployed_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_student_outside_us_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_intern_part_time_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_visa_expiring_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_intern_student_niw', ['yes', 'no'])->nullable();
            $table->enum('currently_unemployed_with_offer_niw', ['yes', 'no'])->nullable();
            $table->enum('other_niw', ['yes', 'no'])->nullable();
            // eb1a records
            $table->enum('currently_student_us_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_academic_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_postdoctoral_eb1a', ['yes', 'no'])->nullable();
            $table->enum('received_promotion_notice_eb1a', ['yes', 'no'])->nullable();
            $table->enum('not_promoted_notice_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_medical_resident_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_visiting_scholar_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_us_business_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_outside_us_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_unemployed_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_student_outside_us_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_intern_part_time_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_visa_expiring_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_intern_student_eb1a', ['yes', 'no'])->nullable();
            $table->enum('currently_unemployed_with_offer_eb1a', ['yes', 'no'])->nullable();
            $table->enum('other_eb1a', ['yes', 'no'])->nullable();
            // eb1b records
            $table->enum('currently_student_us_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_academic_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_postdoctoral_eb1b', ['yes', 'no'])->nullable();
            $table->enum('received_promotion_notice_eb1b', ['yes', 'no'])->nullable();
            $table->enum('not_promoted_notice_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_medical_resident_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_visiting_scholar_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_us_business_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_outside_us_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_unemployed_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_student_outside_us_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_intern_part_time_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_visa_expiring_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_intern_student_eb1b', ['yes', 'no'])->nullable();
            $table->enum('currently_unemployed_with_offer_eb1b', ['yes', 'no'])->nullable();
            $table->enum('other_eb1b', ['yes', 'no'])->nullable();
            // 01 records
            $table->enum('currently_student_us_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_academic_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_postdoctoral_o1', ['yes', 'no'])->nullable();
            $table->enum('received_promotion_notice_o1', ['yes', 'no'])->nullable();
            $table->enum('not_promoted_notice_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_medical_resident_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_visiting_scholar_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_us_business_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_outside_us_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_unemployed_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_student_outside_us_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_intern_part_time_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_employed_visa_expiring_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_intern_student_o1', ['yes', 'no'])->nullable();
            $table->enum('currently_unemployed_with_offer_o1', ['yes', 'no'])->nullable();
            $table->enum('other_o1', ['yes', 'no'])->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposed_employment_endavor_records');
    }
};
