<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('extraordinary_abilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->boolean('has_awards')->default(false);
            $table->boolean('has_memberships')->default(false);
            $table->boolean('has_media_coverage')->default(false);
            $table->boolean('has_speaking_engagements')->default(false);
            $table->boolean('has_leadership_roles')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('extraordinary_abilities');
    }
};
