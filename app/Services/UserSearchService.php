<?php


namespace App\Services;


use App\Organization;
use App\User;
use Illuminate\Http\Request;

class UserSearchService
{
    const COLUMNS = [
        'id',
        'first_name',
        'last_name',
        'email'
    ];

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function searchOrganizationUsers(Organization $organization)
    {
        return $organization
            ->employees()
            ->registered()
            ->searchName($this->request->input('search'))
            ->take(10)
            ->get(static::COLUMNS);
    }

    public function searchAllUsers()
    {
        return User::registered()
            ->searchEmail($this->request->input('search'))
            ->take(10)
            ->get(static::COLUMNS);
    }
}
