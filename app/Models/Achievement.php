<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'nationally_internationally_recognized_awards',
        'award_1_name', 'award_1_recipient', 'award_1_institution', 'who_is_eligible_to_compete_1', 'number_of_competitors_winners_1', 'selection_criteria_1', 'who_are_judges_1',
        'award_2_name', 'award_2_recipient', 'award_2_institution', 'who_is_eligible_to_compete_2', 'number_of_competitors_winners_2', 'selection_criteria_2', 'who_are_judges_2',
        'award_3_name', 'award_3_recipient', 'award_3_institution', 'who_is_eligible_to_compete_3', 'number_of_competitors_winners_3', 'selection_criteria_3', 'who_are_judges_3',
        'peer_review',
        'name_of_organization_reviewed', 'number_of_reviews_completed',
        'phd_committee', 'grant_review',
        'leadership_roles', 'name_of_leadership_roles', 'name_of_organization_in_leadership', 'date_of_service', 'summary_of_organization_reputation', 'summary_of_role_and_responsibilities',
        'notable_memberships', 'name_of_organization_in_membership', 'level_of_membership', 'requirements_for_membership', 'who_judges_membership_eligibility',
        'notable_media_coverage', 'title_of_article', 'date_published', 'author', 'magazine_newspaper_website', 'circulation', 'summary_of_article_focus', 'relevance_to_original_work',
        'invitations', 'conference_title_1', 'conference_month_year_1', 'details_of_invitation_1', 'conference_title_2', 'conference_month_year_2', 'details_of_invitation_2', 'conference_title_3', 'conference_month_year_3', 'details_of_invitation_3',
        'filing_eb1a', 'total_combined_salary', 'no_achievement_for_eb1a', 'field_for_filing'
    ];
}
