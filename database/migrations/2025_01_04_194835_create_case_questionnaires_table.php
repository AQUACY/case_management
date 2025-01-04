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
        Schema::create('case_questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('case_id')->constrained()->onDelete('cascade');
            $table->enum('petition_type', ['EB-1A', 'EB-1B', 'EB-2 NIW'])->nullable();
            $table->enum('petitioner', ['Employer', 'Self'])->nullable();
            $table->string('family_name')->nullable();
            $table->string('given_name')->nullable();
            $table->string('full_middle_name')->nullable();
            $table->enum('native_alphabet', ['Yes', 'No'])->nullable();
            $table->string('street_number_name')->nullable();
            $table->enum('type', ['None', 'Apt', 'Ste', 'Flr'])->nullable();
            $table->string('type_detail')->nullable();
            $table->string('city_town')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->date('dob')->nullable();
            $table->string('birth_city_town_village')->nullable();
            $table->string('birth_state_province')->nullable();
            $table->string('birth_country')->nullable();
            $table->string('citizenship_country')->nullable();
            $table->enum('dual_citizenship', ['Yes', 'No'])->nullable();
            $table->string('secondary_country')->nullable()->nullable();
            $table->string('ssn')->nullable();
            $table->string('alien_registration_number')->nullable();
            $table->date('arrival_date')->nullable();
            $table->string('admission_record_number')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('passport_country')->nullable();
            $table->date('passport_expiration_date')->nullable();
            $table->string('admission_class')->nullable();
            $table->date('admit_until_date')->nullable();
            $table->string('occupation')->nullable();
            $table->decimal('annual_income', 15, 2)->nullable();
            $table->string('job_title')->nullable();
            $table->string('soc_code')->nullable();
            $table->text('nontechnical_job_description')->nullable();
            $table->enum('full_time_position', ['Yes', 'No'])->nullable();
            $table->integer('hours_per_week')->nullable();
            $table->enum('permanent_position', ['Yes', 'No'])->nullable();
            $table->enum('new_position', ['Yes', 'No'])->nullable();
            $table->decimal('wages', 15, 2)->nullable()->nullable();
            $table->string('wages_per')->nullable();
            $table->enum('worksite_type', ['Business premises', 'Employer private household', 'Own private residence', 'More than one location'])->nullable();
            $table->string('worksite_street_number_name')->nullable();
            $table->enum('work_building_type', ['None', 'Apt', 'Ste', 'Flr'])->nullable();
            $table->string('work_type_detail')->nullable();
            $table->string('work_city_town')->nullable();
            $table->string('work_state')->nullable();
            $table->string('work_county_township')->nullable();
            $table->string('work_zip_code')->nullable();
            $table->enum('apply_visa_abroad', ['Yes', 'No'])->nullable();
            $table->string('processing_country')->nullable();
            $table->string('processing_city')->nullable();
            $table->enum('file_adjustment_status', ['Yes', 'No'])->nullable();
            $table->string('current_residence_country')->nullable();
            $table->string('foreign_address_street_number_name')->nullable();
            $table->enum('foreign_address_type', ['None', 'Apt', 'Ste', 'Flr'])->nullable();
            $table->string('foreign_type_detail')->nullable();
            $table->string('foreign_city_town')->nullable();
            $table->string('foreign_state_province')->nullable();
            $table->string('foreign_postal_code')->nullable();
            $table->string('foreign_country')->nullable();
            $table->enum('simultaneous_petitions', ['Yes', 'No'])->nullable();
            $table->text('simultaneous_petitions_details')->nullable();
            $table->enum('prior_petition', ['Yes', 'No'])->nullable();
            $table->text('prior_petition_details')->nullable();
            $table->enum('removal_proceedings', ['Yes', 'No'])->nullable();
            $table->text('removal_proceedings_details')->nullable();
            $table->string('daytime_telephone')->nullable();
            $table->string('mobile_telephone')->nullable();
            $table->string('email_address')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_questionnaires');
    }
};
