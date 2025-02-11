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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_questionnaire_id')->constrained()->onDelete('cascade');
            $table->string('family_name_last_name');
            $table->string('given_name_first_name');
            $table->string('middle_name')->nullable();
            $table->date('dob');
            $table->string('birth_country');
            $table->enum('relationship', ['Spouse', 'Child']);
            $table->enum('adjustment_status', ['Yes', 'No']);
            $table->enum('visa_abroad', ['Yes', 'No']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
