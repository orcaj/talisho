<?php

namespace App\Http\Controllers;

use App\Organization;
use App\PaymentInfo;
use Illuminate\Http\Request;
use App\User;

class WelcomeController extends Controller
{

    public function __invoke(Request $request)
    {
        return redirect('login');
    }

    public function account_status()
    {
        $endpoint_secret = config('services.stripe.live_hook');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json([
                'message' => 'Invalid payload',
            ], 200);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json([
                'message' => 'Invalid signature',
            ], 200);
        }

        if ($event->type == "charge.succeeded") {

            $intent = $event->data->object;
            $customer_id=$intent->customer;
            $pay_info=PaymentInfo::where('customer_id', $customer_id)->first();
            $user_id=$pay_info->user_id;
            $user=User::Find($user_id);
            Organization::find($user->organization_id)->update([
                'account_type'=>'PAID'
            ]);
          
        }
    }
}
