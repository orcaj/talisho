<?php

namespace App\Providers;

use App\Events\ConstructionDirectiveCreated;
use App\Events\DailyLogCreated;
use App\Events\DocumentationCreated;
use App\Events\DocumentationReassigned;
use App\Events\DocumentationResponded;
use App\Events\DocumentationSubmitted;
use App\Events\IncidentReportCreated;
use App\Events\ProjectCreated;
use App\Events\RfiCreated;
use App\Events\RfiResponseCreated;
use App\Events\UserProfileFinalized;
use App\Listeners\GenerateProjectQrCodes;
use App\Listeners\NotifyAdminUserRegistered;
use App\Listeners\NotifyProjectManagerUserFinalizedRegistration;
use App\Listeners\SendConstructionDirectiveCreatedNotification;
use App\Listeners\SendDailyLogCreatedNotification;
use App\Listeners\SendDocumentationCreatedNotification;
use App\Listeners\SendDocumentationReassignedNotification;
use App\Listeners\SendDocumentationRespondedNotification;
use App\Listeners\SendDocumentationSubmittedNotification;
use App\Listeners\SendIncidentReportCreatedNotification;
use App\Listeners\SendProjectCreatedNotification;
use App\Listeners\SendRfiCreatedNotification;
use App\Listeners\SendRfiRespondedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserProfileFinalized::class => [
            NotifyAdminUserRegistered::class,
            NotifyProjectManagerUserFinalizedRegistration::class
        ],
        ProjectCreated::class => [
            GenerateProjectQrCodes::class,
            SendProjectCreatedNotification::class
        ],
        DocumentationCreated::class => [
            SendDocumentationCreatedNotification::class
        ],
        DocumentationSubmitted::class => [
            SendDocumentationSubmittedNotification::class
        ],
        DocumentationResponded::class => [
            SendDocumentationRespondedNotification::class
        ],
        DocumentationReassigned::class => [
            SendDocumentationReassignedNotification::class
        ],
        RfiCreated::class => [
            SendRfiCreatedNotification::class
        ],
        RfiResponseCreated::class => [
            SendRfiRespondedNotification::class
        ],
        ConstructionDirectiveCreated::class => [
            SendConstructionDirectiveCreatedNotification::class
        ],
        DailyLogCreated::class => [
            SendDailyLogCreatedNotification::class
        ],
        IncidentReportCreated::class => [
            SendIncidentReportCreatedNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
