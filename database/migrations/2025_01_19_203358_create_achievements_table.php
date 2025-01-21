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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->boolean('nationally_internationally_recognized_awards')->default(false);
            $table->unsignedBigInteger('case_id');
            $table->longText('award_1_name')->nullable();
            $table->longText('award_1_recipient')->nullable();
            $table->longText('award_1_institution')->nullable();
            $table->longText('who_is_eligible_to_compete_1')->nullable();
            $table->integer('number_of_competitors_winners_1')->nullable();
            $table->longText('selection_criteria_1')->nullable();
            $table->longText('who_are_judges_1')->nullable();

            $table->longText('award_2_name')->nullable();
            $table->longText('award_2_recipient')->nullable();
            $table->longText('award_2_institution')->nullable();
            $table->longText('who_is_eligible_to_compete_2')->nullable();
            $table->integer('number_of_competitors_winners_2')->nullable();
            $table->longText('selection_criteria_2')->nullable();
            $table->longText('who_are_judges_2')->nullable();

            $table->longText('award_3_name')->nullable();
            $table->longText('award_3_recipient')->nullable();
            $table->longText('award_3_institution')->nullable();
            $table->longText('who_is_eligible_to_compete_3')->nullable();
            $table->integer('number_of_competitors_winners_3')->nullable();
            $table->longText('selection_criteria_3')->nullable();
            $table->longText('who_are_judges_3')->nullable();

            $table->boolean('peer_review')->default(false);
            $table->longText('name_of_organization_reviewed')->nullable();
            $table->longText('number_of_reviews_completed')->nullable();

            $table->boolean('phd_committee')->default(false);
            $table->boolean('grant_review')->default(false);

            $table->boolean('leadership_roles')->default(false);
            $table->longText('name_of_leadership_roles')->nullable();
            $table->longText('name_of_organization_in_leadership')->nullable();
            $table->date('date_of_service')->nullable();
            $table->longText('summary_of_organization_reputation')->nullable();
            $table->longText('summary_of_role_and_responsibilities')->nullable();

            $table->boolean('notable_memberships')->default(false);
            $table->longText('name_of_organization_in_membership')->nullable();
            $table->longText('level_of_membership')->nullable();
            $table->longText('requirements_for_membership')->nullable();
            $table->longText('who_judges_membership_eligibility')->nullable();

            $table->boolean('notable_media_coverage')->default(false);
            $table->longText('title_of_article')->nullable();
            $table->date('date_published')->nullable();
            $table->string('author')->nullable();
            $table->string('magazine_newspaper_website')->nullable();
            $table->string('circulation')->nullable();
            $table->longText('summary_of_article_focus')->nullable();
            $table->longText('relevance_to_original_work')->nullable();

            $table->boolean('invitations')->default(false);
            $table->string('conference_title_1')->nullable();
            $table->date('conference_month_year_1')->nullable();
            $table->text('details_of_invitation_1')->nullable();
            $table->string('conference_title_2')->nullable();
            $table->date('conference_month_year_2')->nullable();
            $table->text('details_of_invitation_2')->nullable();
            $table->string('conference_title_3')->nullable();
            $table->date('conference_month_year_3')->nullable();
            $table->text('details_of_invitation_3')->nullable();

            $table->boolean('filing_eb1a')->default(false);
            $table->string('total_combined_salary')->nullable();
            $table->boolean('no_achievement_for_eb1a')->default(false);
            $table->string('field_for_filing')->nullable();
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
        Schema::dropIfExists('achievements');
    }
};
