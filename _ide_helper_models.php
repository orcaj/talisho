<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Submittal
 *
 * @property int $id
 * @property int $csi_id
 * @property int $project_discipline_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\CSI $csi
 * @property-read \App\Documentation $documentation
 * @property-read \App\ProjectDiscipline $projectDiscipline
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submittal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submittal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submittal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submittal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submittal whereCsiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submittal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submittal whereProjectDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Submittal whereUpdatedAt($value)
 */
	class Submittal extends \Eloquent {}
}

namespace App{
/**
 * App\ProjectDefaultDiscipline
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDefaultDiscipline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDefaultDiscipline newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDefaultDiscipline query()
 */
	class ProjectDefaultDiscipline extends \Eloquent {}
}

namespace App{
/**
 * App\Experience
 *
 * @property int $id
 * @property string $label
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Experience newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Experience newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Experience query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Experience whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Experience whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Experience whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Experience whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Experience whereUpdatedAt($value)
 */
	class Experience extends \Eloquent {}
}

namespace App{
/**
 * App\UserLicense
 *
 * @property int $id
 * @property int $user_id
 * @property string $license
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLicense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLicense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLicense query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLicense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLicense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLicense whereLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLicense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserLicense whereUserId($value)
 */
	class UserLicense extends \Eloquent {}
}

namespace App{
/**
 * App\CSI
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $csi_division_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\CSIDivision $division
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSI newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSI newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSI query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSI searchByNameOrCode($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSI whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSI whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSI whereCsiDivisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSI whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSI whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSI whereUpdatedAt($value)
 */
	class CSI extends \Eloquent {}
}

namespace App{
/**
 * App\ConstructionDirective
 *
 * @property int $id
 * @property int $project_discipline_id
 * @property string $identifier
 * @property string $subject
 * @property string|null $drawing_number
 * @property string $directive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $guests
 * @property-read int|null $guests_count
 * @property-read \App\ProjectDiscipline $projectDiscipline
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective whereDirective($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective whereDrawingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective whereProjectDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ConstructionDirective whereUpdatedAt($value)
 */
	class ConstructionDirective extends \Eloquent {}
}

namespace App{
/**
 * App\Documentation
 *
 * @property int $id
 * @property int $assigned_user_id
 * @property int $lead_user_id
 * @property string $entity_type
 * @property int $entity_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $identifier
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\User $assigned
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 * @property-read mixed $base_file_path
 * @property-read mixed $has_messages
 * @property-read mixed $is_approved
 * @property-read mixed $is_due_this_week
 * @property-read mixed $is_outstanding
 * @property-read mixed $is_under_review
 * @property-read mixed $latest_submission_due_date
 * @property-read mixed $messaging_status
 * @property-read \App\User $lead
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DocumentationSubmission[] $submissions
 * @property-read int|null $submissions_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Documentation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation whereAssignedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation whereLeadUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Documentation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Documentation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Documentation withoutTrashed()
 */
	class Documentation extends \Eloquent {}
}

namespace App{
/**
 * App\DocumentationSubmission
 *
 * @property int $id
 * @property int $documentation_id
 * @property \Illuminate\Support\Carbon $due_date
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $to_user_id
 * @property int|null $from_user_id
 * @property-read \App\Documentation $documentation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\DocumentationResponse $response
 * @property-read \App\User|null $sentFrom
 * @property-read \App\User|null $sentTo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission whereDocumentationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission whereFromUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission whereToUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationSubmission whereUpdatedAt($value)
 */
	class DocumentationSubmission extends \Eloquent {}
}

namespace App{
/**
 * App\File
 *
 * @property int $id
 * @property string $fileable_type
 * @property int $fileable_id
 * @property string $path
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $fileable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereFileableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereFileableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereUpdatedAt($value)
 */
	class File extends \Eloquent {}
}

namespace App{
/**
 * App\IncidentReport
 *
 * @property int $id
 * @property int $project_discipline_id
 * @property int $reported_by_user_id
 * @property \Illuminate\Support\Carbon $incident_date
 * @property array $information
 * @property string $identifier
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read mixed $incident_date_with_day_of_week
 * @property-read mixed $is_illness_report
 * @property-read mixed $is_injury_report
 * @property-read mixed $is_near_miss_report
 * @property-read \App\ProjectDiscipline $projectDiscipline
 * @property-read \App\User $reportedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport whereIncidentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport whereInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport whereProjectDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport whereReportedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IncidentReport whereUpdatedAt($value)
 */
	class IncidentReport extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $email
 * @property string|null $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $organization_id
 * @property string|null $job_title
 * @property string|null $phone
 * @property int|null $experience_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $welcome_valid_until
 * @property-read \App\Experience|null $experience
 * @property-read mixed $can
 * @property-read mixed $is_registered
 * @property-read mixed $name
 * @property-read mixed $path
 * @property-read mixed $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserLicense[] $licenses
 * @property-read int|null $licenses_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProjectDiscipline[] $teams
 * @property-read int|null $teams_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User registered()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User searchEmail($email)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User searchName($name)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereExperienceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereWelcomeValidUntil($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\DocumentationResponse
 *
 * @property int $id
 * @property string $status
 * @property string $comment
 * @property int $documentation_submission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $to_user_id
 * @property int|null $from_user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read mixed $approves_document
 * @property-read \App\User|null $sentFrom
 * @property-read \App\User|null $sentTo
 * @property-read \App\DocumentationSubmission $submission
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse whereDocumentationSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse whereFromUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse whereToUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DocumentationResponse whereUpdatedAt($value)
 */
	class DocumentationResponse extends \Eloquent {}
}

namespace App{
/**
 * App\DailyLogComment
 *
 * @property int $id
 * @property int $daily_log_id
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $comment_by_user_id
 * @property-read \App\User $commentedBy
 * @property-read \App\DailyLog $dailyLog
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogComment whereCommentByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogComment whereDailyLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogComment whereUpdatedAt($value)
 */
	class DailyLogComment extends \Eloquent {}
}

namespace App{
/**
 * App\Discipline
 *
 * @property int $id
 * @property string $label
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $abbreviation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Organization[] $organizations
 * @property-read int|null $organizations_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discipline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discipline newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discipline query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discipline whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discipline whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discipline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discipline whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discipline whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Discipline whereUpdatedAt($value)
 */
	class Discipline extends \Eloquent {}
}

namespace App{
/**
 * App\Project
 *
 * @property int $id
 * @property string $name
 * @property string $address_1
 * @property string|null $address_2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $country
 * @property string $client_name
 * @property string $description
 * @property int $organization_id
 * @property string|null $closed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProjectDiscipline[] $disciplines
 * @property-read int|null $disciplines_count
 * @property-read mixed $path
 * @property-read \App\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OtherDocument[] $otherDocuments
 * @property-read int|null $other_documents_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project active($bool = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project closed($bool = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project filterByInputs(\Illuminate\Http\Request $request)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereClientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereZip($value)
 */
	class Project extends \Eloquent {}
}

namespace App{
/**
 * App\OtherDocument
 *
 * @property int $id
 * @property int $csi_id
 * @property int|null $submittal_id
 * @property int $project_discipline_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $document_type
 * @property-read \App\CSI $csi
 * @property-read \App\Documentation $documentation
 * @property-read \App\ProjectDiscipline $projectDiscipline
 * @property-read \App\Submittal|null $submittal
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OtherDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OtherDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OtherDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OtherDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OtherDocument whereCsiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OtherDocument whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OtherDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OtherDocument whereProjectDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OtherDocument whereSubmittalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OtherDocument whereUpdatedAt($value)
 */
	class OtherDocument extends \Eloquent {}
}

namespace App{
/**
 * App\CSIDivision
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIDivision generalRequirements()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIDivision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIDivision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIDivision query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIDivision submittals()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIDivision whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIDivision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIDivision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIDivision whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIDivision whereUpdatedAt($value)
 */
	class CSIDivision extends \Eloquent {}
}

namespace App{
/**
 * App\DailyLog
 *
 * @property int $id
 * @property int $project_discipline_id
 * @property int $reported_by_user_id
 * @property \Illuminate\Support\Carbon $log_date
 * @property array $information
 * @property string $identifier
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DailyLogComment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read mixed $log_date_with_day_of_week
 * @property-read mixed $was_safety_meeting
 * @property-read mixed $was_weather_delay
 * @property-read mixed $were_accidents
 * @property-read mixed $were_injuries
 * @property-read mixed $were_near_misses
 * @property-read \App\ProjectDiscipline $projectDiscipline
 * @property-read \App\User $reportedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog whereInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog whereLogDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog whereProjectDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog whereReportedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLog whereUpdatedAt($value)
 */
	class DailyLog extends \Eloquent {}
}

namespace App{
/**
 * App\ProjectDiscipline
 *
 * @property int $id
 * @property int $project_id
 * @property int $discipline_id
 * @property int $user_id
 * @property string|null $comment
 * @property string|null $commented_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ConstructionDirective[] $constructionDirectives
 * @property-read int|null $construction_directives_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DailyLogOffDay[] $dailyLogOffDays
 * @property-read int|null $daily_log_off_days_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DailyLog[] $dailyLogs
 * @property-read int|null $daily_logs_count
 * @property-read \App\Discipline $discipline
 * @property-read mixed $construction_directive_base_path
 * @property-read mixed $daily_log_base_path
 * @property-read mixed $incident_report_base_path
 * @property-read mixed $rfi_base_path
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\IncidentReport[] $incidentReports
 * @property-read int|null $incident_reports_count
 * @property-read \App\User $lead
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OtherDocument[] $otherDocuments
 * @property-read int|null $other_documents_count
 * @property-read \App\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Rfi[] $rfis
 * @property-read int|null $rfis_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Submittal[] $submittals
 * @property-read int|null $submittals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $team
 * @property-read int|null $team_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDiscipline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDiscipline newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDiscipline query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDiscipline whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDiscipline whereCommentedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDiscipline whereDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDiscipline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDiscipline whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectDiscipline whereUserId($value)
 */
	class ProjectDiscipline extends \Eloquent {}
}

namespace App{
/**
 * App\CSIQuickList
 *
 * @property int $id
 * @property int $csi_id
 * @property string $quick_list
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\CSI $csi
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList associatedDocuments()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList generalRequirements()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList tab()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList whereCsiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList whereQuickList($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CSIQuickList whereUpdatedAt($value)
 */
	class CSIQuickList extends \Eloquent {}
}

namespace App{
/**
 * App\DailyLogOffDay
 *
 * @property int $id
 * @property int $project_discipline_id
 * @property \Illuminate\Support\Carbon $off_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\ProjectDiscipline $projectDiscipline
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogOffDay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogOffDay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogOffDay query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogOffDay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogOffDay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogOffDay whereOffDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogOffDay whereProjectDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DailyLogOffDay whereUpdatedAt($value)
 */
	class DailyLogOffDay extends \Eloquent {}
}

namespace App{
/**
 * App\EmployeeCount
 *
 * @property int $id
 * @property string $label
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmployeeCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmployeeCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmployeeCount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmployeeCount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmployeeCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmployeeCount whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmployeeCount whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EmployeeCount whereUpdatedAt($value)
 */
	class EmployeeCount extends \Eloquent {}
}

namespace App{
/**
 * App\Organization
 *
 * @property int $id
 * @property string $name
 * @property string $address_1
 * @property string|null $address_2
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $phone
 * @property string|null $website
 * @property string $account_type
 * @property string $account_status
 * @property int $employee_count_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $zip
 * @property string $primary_contact_name
 * @property string $primary_contact_email
 * @property string $primary_contact_phone
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Discipline[] $disciplines
 * @property-read int|null $disciplines_count
 * @property-read \App\EmployeeCount $employeeCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $employees
 * @property-read int|null $employees_count
 * @property-read mixed $path
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Discipline[] $projectDefaultDisciplines
 * @property-read int|null $project_default_disciplines_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Project[] $projects
 * @property-read int|null $projects_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAccountStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereEmployeeCountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization wherePrimaryContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization wherePrimaryContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization wherePrimaryContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereZip($value)
 */
	class Organization extends \Eloquent {}
}

namespace App{
/**
 * App\Rfi
 *
 * @property int $id
 * @property int $project_discipline_id
 * @property int $guest_user_id
 * @property string $subject
 * @property \Illuminate\Support\Carbon $due_date
 * @property string $question
 * @property string $identifier
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read mixed $is_finalized
 * @property-read mixed $is_late
 * @property-read mixed $is_under_review
 * @property-read \App\ProjectDiscipline $projectDiscipline
 * @property-read \App\User $requestedBy
 * @property-read \App\RfiResponse $response
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi whereGuestUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi whereProjectDisciplineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rfi whereUpdatedAt($value)
 */
	class Rfi extends \Eloquent {}
}

namespace App{
/**
 * App\RfiResponse
 *
 * @property int $id
 * @property int $rfi_id
 * @property string $response
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\Rfi $rfi
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RfiResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RfiResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RfiResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RfiResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RfiResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RfiResponse whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RfiResponse whereRfiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RfiResponse whereUpdatedAt($value)
 */
	class RfiResponse extends \Eloquent {}
}

