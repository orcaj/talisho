<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function index()
    {
        return Redirect::route('organizations.projects.index', ['organization' => Auth::user()->organization->id]);
    }
}
