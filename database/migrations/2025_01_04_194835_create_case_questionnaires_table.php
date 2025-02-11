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
            // basic information
            $table->enum('petition_type', ['EB-1A', 'EB-1B', 'EB-2 NIW'])->nullable();
            $table->enum('petitioner', ['Employer', 'Self'])->nullable();

            // personal information
            $table->string('family_name')->nullable();
            $table->string('given_name')->nullable();
            $table->string('full_middle_name')->nullable();
            $table->enum('native_alphabet', ['Yes', 'No'])->nullable();
            $table->date('dob')->nullable();
            $table->string('city_town_village_of_birth')->nullable();
            $table->string('state_of_birth')->nullable();
            $table->string('country_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('alien_registration_number')->nullable();
            $table->string('ssn')->nullable();

            // mail address
            $table->string('street_number_name')->nullable();
            $table->enum('type', ['None', 'Apt', 'Ste', 'Flr'])->nullable();
            $table->string('type_detail')->nullable();
            $table->string('city_town')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('mobile_telephone')->nullable();
            $table->string('email_address')->nullable();

            // information aboout last arrival
            $table->date('last_arrival_date')->nullable();
            $table->integer('i_94_arrival_record_number')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('status_on_form_i_94')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('travel_document_number')->nullable();
            $table->string('country_of_issuance')->nullable();
            $table->date('expiration_date_for_passport')->nullable();

            // visa processing
            $table->enum('alien_will_apply_for_visa_abroad', ['Yes', 'No'])->nullable();
            $table->string('visa_processing_city_town')->nullable();
            $table->string('visa_processing_country')->nullable();
            $table->enum('alien_in_us', ['Yes', 'No'])->nullable();
            // 2b
            $table->string('if_now_in_the_us')->nullable();
            $table->string('foreign_street_number_name')->nullable();
            $table->enum('foreign_address_type', ['None', 'Apt', 'Ste', 'Flr'])->nullable();
            $table->string('foreign_city_town')->nullable();
            $table->string('foreign_state_province')->nullable();
            $table->string('foreign_postal_code')->nullable();
            $table->string('foreign_country')->nullable();

            // employment information
            $table->string('current_employer_name')->nullable();
            $table->string('job_title')->nullable();
            $table->enum('full_time_position', ['Yes', 'No'])->nullable();
            $table->enum('permanent_position', ['Yes', 'No'])->nullable();
            // b
            $table->string('occupation')->nullable();
            $table->decimal('annual_income', 15, 2)->nullable();
            $table->string('soc_code')->nullable();
            $table->text('nontechnical_job_description')->nullable();
            $table->integer('hours_per_week')->nullable();
            $table->enum('new_position', ['Yes', 'No'])->nullable();
            $table->decimal('wages', 15, 2)->nullable()->nullable();
            $table->string('wages_per')->nullable();
            // c
            $table->enum('worksite_type', ['Business premises', 'Employer private household', 'Own private residence', 'More than one location'])->nullable();
            $table->string('worksite_street_number_name')->nullable();
            $table->enum('work_building_type', ['None', 'Apt', 'Ste', 'Flr'])->nullable();
            $table->string('work_site_additional_details')->nullable();
            $table->string('work_city_town')->nullable();
            $table->string('work_state')->nullable();
            $table->string('work_county_township')->nullable();
            $table->string('work_zip_code')->nullable();

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
