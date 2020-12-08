<?php

namespace App;

use App\Enum\Permission;
use App\Enum\RegistrationStatus;
use App\Enum\Role;
use App\Notifications\UserInvite;
use App\Traits\TalihoNotifiable;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\WelcomeNotification\ReceivesWelcomeNotification;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    use TalihoNotifiable, SoftDeletes, ReceivesWelcomeNotification, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'organization_id',
        'mobile_phone',
        'is_first',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'deleted_at' => 'datetime'
    ];

    protected $appends = ['status', 'isRegistered', 'can', 'name', 'isExpired'];

    public function getIsRegisteredAttribute()
    {
        return is_null($this->welcome_valid_until);
    }

    public function getIsExpiredAttribute()
    {
        if (is_null($this->welcome_valid_until)){
            return false;
        }
        else{
            if(Carbon::parse($this->welcome_valid_until)->gt(now())){
                return false;
            }
            return true;
        }
    }

    public function getStatusAttribute()
    {
        return $this->isRegistered ? RegistrationStatus::REGISTERED : RegistrationStatus::INVITE_SENT;
    }

    public function getPathAttribute()
    {
        return "/users/{$this->id}";
    }

    public function getCanAttribute()
    {
        return [
            'view' => [
                'projects' => [
                    'disciplines' => [
                        'comments' => $this->can(Permission::VIEW_PROJECT_DISCIPLINE_COMMENTS)
                    ]
                ],
                'disciplines' => $this->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES),
                'documentation' => $this->can(Permission::VIEW_ALL_DOCUMENTATION),
                'admin' => $this->hasRole(Role::SUPER_ADMIN),
                'users' => $this->can(Permission::VIEW_EMPLOYEES) || $this->isALead()
            ],
            'create' => [
                'projects' => $this->can(Permission::CREATE_PROJECTS),
                'users' => $this->can(Permission::CREATE_EMPLOYEES) || $this->isALead(),
                'designDocuments' => $this->can(Permission::CREATE_DESIGN_DOCUMENTS),
                'approvals' => $this->can(Permission::AUTO_APPROVE_DOCUMENTS)
            ],
            'edit' => [
                'projects' => $this->can(Permission::EDIT_PROJECTS),
                'organizations' => $this->can(Permission::EDIT_ORGANIZATIONS)
            ]
        ];
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class) ->withDefault();
    }

    public function teams()
    {
        return $this->belongsToMany(ProjectDiscipline::class, 'project_discipline_guests');
    }

    public function last_team()
    {
        return  $this->belongsToMany(ProjectDiscipline::class, 'project_discipline_guests')->orderBy('id', 'DESC')->first();
    }

    public function leadDisciplines()
    {
        return $this->hasMany(ProjectDiscipline::class, 'user_id');
    }

    public function isALead()
    {
        return $this->leadDisciplines()->exists();
    }

    public function sendSetPasswordEmail(User $sentBy = null, Carbon $expiresAt = null)
    {
        $this->notify(new UserInvite($expiresAt ?? now()->addDays(config('auth.welcome_email_timeout')), $sentBy));
    }


    public function documentation()
    {
        return $this->hasMany(Documentation::class, 'assigned_user_id');
    }

    public function leadDocumentation()
    {
        return $this->hasMany(Documentation::class, 'lead_user_id');
    }

    public function constructionDirectives()
    {
        return $this->belongsToMany(ConstructionDirective::class, 'construction_directive_guest', 'guest_user_id', 'construction_directive_id');
    }

    public function rfis()
    {
        return $this->hasMany(Rfi::class, 'guest_user_id');
    }

    public function incidentReports()
    {
        return $this->hasMany(IncidentReport::class, 'assigned_to_user_id');
    }

    public function belongsToTeamInProject(Project $project)
    {
        return $this->teams->contains('project_id', $project->id);
    }

    public function leadsDisciplineInProject(Project $project)
    {
        return $project->disciplines->contains('user_id', $this->id);
    }




    public function scopeRegistered($query)
    {
        return $query->whereNull('welcome_valid_until');
    }

    public function scopeSearchName($query, $name)
    {
        return $query->where(function ($query) use ($name) {
           $query->where('first_name', 'like', "%{$name}%")
                ->orWhere('last_name', 'like', "%{$name}%");
        });
    }

    public function scopeSearchEmail($query, $email)
    {
        return $query->where('email', 'like', "%{$email}%");
    }

    public function isAuthorizedToViewFile(File $file)
    {
        return ($this->can(Permission::VIEW_ALL_PROJECT_FILES) && $file->fileable->isAssociatedWithOrganization($this->organization))
            || $file->fileable->isAssociatedWithUser($this);
    }

    public function isProjectManagerForOrganization(Organization $organization)
    {
        return $this->hasRole(Role::PROJECT_MANAGER) && $organization->employees->contains($this);
    }
}
