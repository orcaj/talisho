<?php


namespace App\Services;


use App\User;
use App\Company;
use Illuminate\Http\Request;

class UserService
{
    public function update(Request $request, User $user)
    {

        $user->update($request->validated());

        // Sort of redundant here but otherwise password is saved as plain text

        if ($request->input('password')) {
            $user->password = bcrypt($request->validated()['password']);
            $user->save();
        }

        if(empty($user -> company -> id)){
            $company = Company::create($this->companyRequestMapping($request, $user));

            $user -> company_id  = $company -> id;
            $user -> save();
        }
        else{
            $company = $user -> company ->update($this->companyRequestMapping($request, $user));
        }

        return $user;
    }

    protected function companyRequestMapping($request, user $user): array {
        return [
            'name' => $request -> getCompanyName(),
            'address_1' => $request -> getCompanyAddress1(),
            'address_2' => $request -> getCompanyAddress2(),
            'city' => $request -> getCompanyCity(),
            'state' => $request -> getCompanyState(),
            'zip' => $request -> getCompanyZip(),
        ];
    }
}
