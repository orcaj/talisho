<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Organization;
use App\PaymentInfo;

use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data['formData']['userInfo'], [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    function stripe_subscription($data)
    {
        $tok_id = $data['formData']['payInfo']['token'];

        $stripe_sk = config('services.stripe.sk');
        $stripe_price_id = config('services.stripe.price_id');
        $trial_day=config('services.stripe.trial_day');

        $stripe = new \Stripe\StripeClient($stripe_sk);
        $customer = $stripe->customers->create([
            'email' => $data['formData']['userInfo']['email'],
            'name' => $data['formData']['userInfo']['first_name'] . " " . $data['formData']['userInfo']['last_name'],
            'phone' => $data['formData']['userInfo']['phone'],
            'description' => 'Taliso Monthly Subscription',
            'source' => $tok_id
        ]);

        $subscription = $stripe->subscriptions->create([
            'customer' => $customer['id'],
            'items' => [
                ['price' => $stripe_price_id],
            ],
            'trial_end' => strtotime(Carbon::now()->addDay($trial_day)->toDateString()),
            // 'trial_end' => 'now',
        ]);
        return ['sub_id' => $subscription['id'], 'customer_id' => $customer['id']];
    }

    function create_organization($data)
    {
        $organization = Organization::create([
            'name' => $data['companyInfo']['companyName'],
            'address_1' => $data['companyInfo']['companyAddress1'],
            'address_2' => $data['companyInfo']['companyAddress2'],
            'city' => $data['companyInfo']['city'],
            'state' => $data['companyInfo']['state'],
            'zip' => $data['companyInfo']['zipCode'],
            'country' => 'US',
            'phone' => $data['userInfo']['phone'],
            'employee_count_id' => 1,
            'account_type' => 'FREE',
            'account_status' => 'ACTIVE',
            'primary_contact_name' => $data['userInfo']['first_name'] . " " . $data['userInfo']['last_name'],
            'primary_contact_email' => $data['userInfo']['email'],
            'primary_contact_phone' => $data['userInfo']['phone']
        ]);
        return $organization->id;
    }

    protected function create(array $data)
    {
        $sub = $this->stripe_subscription($data);
        $organization_id = $this->create_organization($data['formData']);

        $userInfo = $data['formData']['userInfo'];

        $user=User::create([
            'first_name' => $userInfo['first_name'],
            'last_name' => $userInfo['last_name'],
            'email' => $userInfo['email'],
            'password' => Hash::make($userInfo['password']),
            'organization_id' => $organization_id,
            'mobile_phone' => $userInfo['phone'],
        ]);

        $payment_info=PaymentInfo::create([
            'user_id'=>$user->id,
            'sub_id' => $sub['sub_id'],
            'customer_id'=>$sub['customer_id'],
            'status' => 'current'
        ]);

        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $stripe_pk = config('services.stripe.pk');
        return Inertia::render('Auth/Register', [
            'stripe_pk' => $stripe_pk
        ]);
    }
}
