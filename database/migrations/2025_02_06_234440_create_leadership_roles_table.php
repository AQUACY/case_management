<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leadership_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->string('role_position');
            $table->string('organization_name');
            $table->date('service_start_date');
            $table->date('service_end_date')->nullable();
            $table->text('organization_prestige');
            $table->text('role_summary');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leadership_roles');
    }
};
