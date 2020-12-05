<?php

namespace App\Providers;

use App\Channels\DatabaseChannel;
use App\TalihoNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\DatabaseNotification as IlluminateDatabaseNotification;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance(IlluminateDatabaseChannel::class, new DatabaseChannel());
        $this->app->instance(IlluminateDatabaseNotification::class, new TalihoNotification());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });

        Inertia::share([
            'auth' => function () {
                return [
                    'user' => Auth::check() ? [
                        'id' => Auth::user()->id,
                        'organization' => [
                            'id' => Auth::user()->organization->id,
                            'name' => Auth::user()->organization->name
                        ],
                        'name' => Auth::user()->name,
                        'can' => Auth::user()->can
                    ] : null
                ];
            },
            'flash' => function () {
                return [
                    'success' => Session::get('success'),
                    'error' => Session::get('error'),
                ];
            },
            'errors' => function () {
                return Session::get('errors')
                    ? Session::get('errors')->getBag('default')->getMessages()
                    : (object)[];
            }
        ]);
    }
}
