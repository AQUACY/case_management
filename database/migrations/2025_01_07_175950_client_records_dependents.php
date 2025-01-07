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
        Schema::create('client_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->string('petition_types'); // Assuming it's a JSON or text field for multiple entries
            $table->string('petition_category')->nullable();
            $table->string('filing_plan_eb1')->nullable();
            $table->string('filing_plan_eb2')->nullable();
            $table->string('previous_visa_filing')->nullable();
            $table->string('i485_filing_plan')->nullable();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('title')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('country_of_birth')->nullable();
            $table->string('country_of_citizenship')->nullable();
            $table->boolean('in_us')->nullable();
            $table->string('visa_status')->nullable();
            $table->date('ds2019_expiration')->nullable();
            $table->date('visa_expiration')->nullable();
            $table->date('passport_expiration')->nullable();
            $table->boolean('no_passport_applied')->nullable();
            $table->date('admit_until_date')->nullable();
            $table->boolean('applying_new_visa')->nullable();
            $table->string('visa_type')->nullable();
            $table->date('latest_entry_date')->nullable();
            $table->string('latest_visa_status')->nullable();
            $table->boolean('j_visa_status')->nullable();
            $table->boolean('communist_party_member')->nullable();
            $table->string('employer_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('proposed_employment_field')->nullable();
            $table->string('company_name')->nullable();
            $table->longText('job_description')->nullable();
            $table->boolean('full_time')->nullable();
            $table->boolean('permanent_position')->nullable();
            $table->string('worksite_city')->nullable();
            $table->string('worksite_state')->nullable();
            $table->string('paper_publication_year')->nullable();
            $table->boolean('asylum_applied')->nullable();
            $table->string('street_address')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('referral_source')->nullable();
            $table->string('social_media_source')->nullable();
            $table->boolean('has_dependents')->nullable();
            $table->enum('marital_status', ['married', 'single'])->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
        });

         // Create 'dependents' table
         Schema::create('dependents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_record_id');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('relation')->nullable();
            $table->string('country_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('passport_expiration')->nullable();
            $table->boolean('no_passport_applied')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('address')->nullable();
            $table->string('visa_processing_option')->nullable();
            $table->string('processing_country')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('client_record_id')->references('id')->on('client_records')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependents');
        Schema::dropIfExists('client_records');
    }
};
