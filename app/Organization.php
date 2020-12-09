<?php

namespace App;

use App\Enum\Role;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    // TODO switch to guarded and unguard any other models
    protected $fillable = [
        'name',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
        'country',
        'phone',
        'website',
        'employee_count_id',
        'account_type',
        'account_status',
        'primary_contact_name',
        'primary_contact_email',
        'primary_contact_phone'
    ];

    protected $appends = ['path'];

    protected $hidden = [
        // 'account_status',
    ];

    public function getPathAttribute()
    {
        return "/organizations/{$this->id}";
    }

    public function projectDefaultDisciplines()
    {
        return $this->belongsToMany(Discipline::class, 'project_default_disciplines')->using(ProjectDefaultDiscipline::class);
    }

    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'organization_disciplines');
    }

    public function employeeCount()
    {
        return $this->belongsTo(EmployeeCount::class);
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function customSpecifications()
    {
        return $this->hasMany(CustomSpecification::class);
    }

    public function getProjectManagerAttribute()
    {
        // TODO Better way of handling Project Managers for organizations, should enforce exactly one PM per org
        return $this->employees->first(function ($employee) {
                return $employee->hasRole(Role::PROJECT_MANAGER);
            }) ?? $this->employees->first(function ($employee) {
                return $employee->hasRole(Role::SUPER_ADMIN);
            });
    }
}
