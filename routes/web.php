<?php

use App\Http\Controllers\AcceptableFileTypesController;
use App\Http\Controllers\AcknowledgeIncidentReportController;
use App\Http\Controllers\AddAdditionalDocumentsToSubmittalsController;
use App\Http\Controllers\AssociatedDocumentListController;
use App\Http\Controllers\CommissioningDocsQrController;
use App\Http\Controllers\ConstructionDirectiveAjaxShowController;
use App\Http\Controllers\CustomSpecificationGeneralRequirementsController;
use App\Http\Controllers\CustomSpecificationSubmittalsController;
use App\Http\Controllers\CustomSpecificationTabCxLeedController;
use App\Http\Controllers\DailyLogOffDaysController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\GroundConditionTypesController;
use App\Http\Controllers\IncidentReportTypesController;
use App\Http\Controllers\ManualDocumentationApprovalController;
use App\Http\Controllers\OtherDocumentStatusController;
use App\Http\Controllers\ProjectCommunicationIndexController;
use App\Http\Controllers\ProjectDisciplineAvailableDailyLogDatesController;
use App\Http\Controllers\ProjectDisciplineChangeLeadController;
use App\Http\Controllers\ProjectDisciplineChangeSwitchController;
use App\Http\Controllers\ProjectLiabilityIndexController;
use App\Http\Controllers\QrCodeInternalController;
use App\Http\Controllers\RfiAjaxShowController;
use App\Http\Controllers\ShowConstructionDirectiveController;
use App\Http\Controllers\ShowDailyLogController;
use App\Http\Controllers\ShowIncidentReportController;
use App\Http\Controllers\StoreConstructionDirectiveController;
use App\Http\Controllers\ShowRfiController;
use App\Http\Controllers\StoreDailyLogCommentController;
use App\Http\Controllers\StoreDailyLogController;
use App\Http\Controllers\StoreDocumentationSubmissionController;
use App\Http\Controllers\CreateGeneralRequirementsDocumentsController;
use App\Http\Controllers\ImportGeneralRequirementsDocumentsController;
use App\Http\Controllers\CreateSubmittalController;
use App\Http\Controllers\ImportSubmittalController;
use App\Http\Controllers\CreateTabDocumentController;
use App\Http\Controllers\ImportTabDocumentController;
use App\Http\Controllers\CSIController;
use App\Http\Controllers\CSIGeneralRequirementsSearchController;
use App\Http\Controllers\CSISubmittalsSearchController;
use App\Http\Controllers\CSITabSearchController;
use App\Http\Controllers\DocumentationAjaxController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationUserController;
use App\Http\Controllers\OrganizationProjectController;
use App\Http\Controllers\OrganizationUserSearchController;
use App\Http\Controllers\ProjectDisciplineGuestController;
use App\Http\Controllers\ProjectDocumentationIndexController;
use App\Http\Controllers\ProjectTeamController;
use App\Http\Controllers\ProjectDesignDocumentController;
use App\Http\Controllers\ProjectNotificationsController;
use App\Http\Controllers\ShowGeneralRequirementsController;
use App\Http\Controllers\ShowSubmittalsController;
use App\Http\Controllers\ShowTabCxLeedController;
use App\Http\Controllers\StoreDocumentationSubmissionResponseController;
use App\Http\Controllers\StoreIncidentReportController;
use App\Http\Controllers\StoreRfiController;
use App\Http\Controllers\StoreRfiResponseController;
use App\Http\Controllers\SubmittalQRCodeController;
use App\Http\Controllers\SubmittalStatusController;
use App\Http\Controllers\UpdateDocumentationAssignmentController;
use App\Http\Controllers\UpdateDocumentationDueDateController;
use App\Http\Controllers\UpdateMultipleDocumentationDueDatesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSearchController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\DesignDocumentZipDownloadController;
use Spatie\WelcomeNotification\WelcomesNewUsers;
use App\Http\Controllers\Auth\WelcomeNotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', WelcomeController::class);

Route::get('/test', 'App\Http\Controllers\UserController@test');


// subscrition
Route::post('/cancel_subscription', 'App\Http\Controllers\UserController@cancel_subscription');
Route::post('/retry_subscription', 'App\Http\Controllers\UserController@retry_subscription');



Route::get('/qr-codes/{qrcode}', \App\Http\Controllers\QrCodeExternalController::class);

Route::namespace('App\Http\Controllers')->group(function () {
    Auth::routes();
    Auth::routes(['verify' => true]);
});

Route::group(['middleware' => [WelcomesNewUsers::class,]], function () {
    Route::get('welcome/{user}', [WelcomeNotificationController::class, 'showWelcomeForm'])->name('welcome');
    Route::post('welcome/{user}', [WelcomeNotificationController::class, 'setProfile']);
});

Route::middleware(['auth'])->group(function () {

    // AJAX Routes

    Route::get('/users/search', UserSearchController::class);
    Route::get('/organizations/{organization}/users/search', OrganizationUserSearchController::class);

    Route::get('/csi-search/general-requirements', CSIGeneralRequirementsSearchController::class)->name('csi-search.general-requirements');
    Route::get('/csi-search/submittals', CSISubmittalsSearchController::class)->name('csi-search.submittals');
    Route::get('/csi-search/all', CSITabSearchController::class)->name('csi-search.tab');

    Route::get('/associated-documents', AssociatedDocumentListController::class)->name('associated-documents');
    Route::post('documentation/{documentation}/associated-documents', AddAdditionalDocumentsToSubmittalsController::class)->name('add-associated-documents');

    Route::get('/organizations/{organization}/custom-specifications/general-requirements', CustomSpecificationGeneralRequirementsController::class)->name('custom-specifications.general-requirements');
    Route::get('/organizations/{organization}/custom-specifications/submittals', CustomSpecificationSubmittalsController::class)->name('custom-specifications.submittals');
    Route::get('/organizations/{organization}/custom-specifications/tab-cx-leed', CustomSpecificationTabCxLeedController::class)->name('custom-specifications.tab-cx-leed');

    Route::get('documentation/{documentation}', [DocumentationAjaxController::class, 'show'])->name('documentation.ajax');

    Route::put('documentations/re-assign/multiple', UpdateDocumentationAssignmentController::class)->name('documentation.re-assign.multiple');
    Route::put('documentations/due-date/multiple', UpdateMultipleDocumentationDueDatesController::class)->name('documentation.due-date.multiple');

    Route::delete('documentation/{documentation}', [DocumentationAjaxController::class, 'destroy'])->name('documentation.ajax.delete');

    Route::get('rfi/{rfi}', RfiAjaxShowController::class)->name('rfi.ajax');
    Route::get('construction-directive/{construction_directive}', ConstructionDirectiveAjaxShowController::class)->name('cd.ajax');

    Route::get('statuses/submittals',SubmittalStatusController::class)->name('statuses.submittals');
    Route::get('statuses/other-documents', OtherDocumentStatusController::class)->name('statuses.other-documents');

    Route::get('files/{file}', FileDownloadController::class)->name('download-file');
    Route::get('design-documents/{design_document}', DesignDocumentZipDownloadController::class)->name('download-zip');

    Route::get('acceptable-file-types', AcceptableFileTypesController::class)->name('acceptable-file-types');
    Route::get('incident-report-types', IncidentReportTypesController::class)->name('incident-report-types');
    Route::get('ground-condition-types', GroundConditionTypesController::class)->name('ground-condition-types');
    Route::get('project-discipline/{project_discipline}/available-daily-log-dates', ProjectDisciplineAvailableDailyLogDatesController::class)->name('project-discipline.available-daily-log-dates');

    Route::post('/notifications/{user}/read/{project}', [ProjectNotificationsController::class, 'read'])->name('read-notifications');
    Route::post('/notifications/{user}/delete/{notification}', [ProjectNotificationsController::class, 'delete'])->name('delete-notification');

    // Resource Routes

    Route::get('organizations/{organization}/users', OrganizationUserController::class)->name('organizations.users.index');

    Route::resource('organizations', OrganizationController::class)
        ->only(['edit', 'update'])
        ->names([
            'edit' => 'organizations.edit',
            'update' => 'organizations.update'
        ]);

    Route::resource('users', UserController::class)
        ->except(['index', 'show'])
        ->names([
            'create' => 'users.create',
            'store' => 'users.store',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.delete',
        ]);

    Route::resource('organizations.projects', OrganizationProjectController::class)
        ->except('delete')
        ->names([
            'index' => 'organizations.projects.index',
            'create' => 'organizations.projects.create',
            'store' => 'organizations.projects.store',
            'edit' => 'organizations.projects.edit',
            'update' => 'organizations.projects.update',
        ]);


    // Project Routes

    // Paywall Protected
    Route::middleware(['check-project-access'])->group(function () {
        // Communication

        Route::get('/organizations/{organization}/projects/{project}/communication/rfis', ShowRfiController::class)->name('organizations.projects.communication.rfis');
        Route::get('/organizations/{organization}/projects/{project}/communication/construction-directives', ShowConstructionDirectiveController::class)->name('organizations.projects.communication.construction-directives');

        // Liability
        Route::get('organizations/{organization}/projects/{project}/liability/daily-logs', ShowDailyLogController::class)->name('organizations.projects.liability.daily-logs');
        Route::get('organizations/{organization}/projects/{project}/liability/incident-reports', ShowIncidentReportController::class)->name('organizations.projects.liability.incident-reports');
    });

    // Documentation

    Route::get('organizations/{organization}/projects/{project}/documentation', ProjectDocumentationIndexController::class)
        ->name('organizations.projects.documentation.index');
    Route::get('organizations/{organization}/projects/{project}/documentation/general-requirements', ShowGeneralRequirementsController::class)
        ->name('organizations.projects.documentation.show.general-requirement');
    Route::get('organizations/{organization}/projects/{project}/documentation/submittals', ShowSubmittalsController::class)
        ->name('organizations.projects.documentation.show.submittal');
    Route::get('organizations/{organization}/projects/{project}/documentation/tab-cx-leed', ShowTabCxLeedController::class)
        ->name('organizations.projects.documentation.show.tab-cx-leed');


    Route::post('/project-discipline/{project_discipline}/user/{user}/documentation/store', CreateGeneralRequirementsDocumentsController::class)->name('documentation.general.store');
    Route::post('/project-discipline/{project_discipline}/user/{user}/submittal/store', CreateSubmittalController::class)->name('documentation.submittal.store');
    Route::post('/project-discipline/{project_discipline}/user/{user}/tab/store', CreateTabDocumentController::class)->name('documentation.tab.store');

    Route::post('/project-discipline/{project_discipline}/documentation/import', ImportGeneralRequirementsDocumentsController::class)->name('documentation.general.import');
    Route::post('/project-discipline/{project_discipline}/submittal/import', ImportSubmittalController::class)->name('documentation.submittal.import');
    Route::post('/project-discipline/{project_discipline}/tab/import', ImportTabDocumentController::class)->name('documentation.tab.import');

    Route::post('documentation/{documentation}/submissions', StoreDocumentationSubmissionController::class)->name('documentation-submissions.store');
    Route::post('documentation-submissions/{documentation_submission}/responses', StoreDocumentationSubmissionResponseController::class)->name('documentation-responses.store');

    Route::post('documentation/{documentation}/manual-approval', ManualDocumentationApprovalController::class)->name('documentation.manual-approval');

    // Communication

    Route::get('organizations/{organization}/projects/{project}/communication', ProjectCommunicationIndexController::class)->name('organizations.projects.communication.index');


    Route::post('/project-discipline/{project_discipline}/rfis', StoreRfiController::class)->name('communication.rfi.store');
    Route::post('/project-discipline/{project_discipline}/rfi/{rfi}/response', StoreRfiResponseController::class)->name('communication.rfi.response.store');

    Route::post('project-discipline/{project_discipline}/construction-directives', StoreConstructionDirectiveController::class)->name('communication.construction-directive.store');

    // Liability

    Route::get('organizations/{organization}/projects/{project}/liability', ProjectLiabilityIndexController::class)->name('organizations.projects.liability.index');

    Route::post('project-discipline/{project_discipline}/daily-logs', StoreDailyLogController::class)->name('liability.daily-logs.store');
    Route::post('project-discipline/{project_discipline}/daily-logs/{daily_log}/comment', StoreDailyLogCommentController::class)->name('liability.daily-logs.comments.store');

    Route::post('project-discipline/{project_discipline}/daily-log-off-day', [DailyLogOffDaysController::class, 'store'])->name('liability.daily-logs.off-day.store');
    Route::delete('project-discipline/{project_discipline}/daily-log-off-day/{daily_log_off_day}', [DailyLogOffDaysController::class, 'destroy'])->name('liability.daily-logs.off-day.delete');

    Route::post('project-discipline/{project_discipline}/incident-reports', StoreIncidentReportController::class)->name('liability.incident-report.store');

    Route::put('project-discipline/{project_discipline}/incident-reports/{incident_report}', AcknowledgeIncidentReportController::class)->name('liability.incident-report.update');

    // Design Documents
     Route::get('organizations/{organization}/projects/{project}/design-documents', [ProjectDesignDocumentController::class, 'index'])
         ->name('organizations.projects.design-documents.index');

     Route::post('organizations/{organization}/projects/{project}/design-documents', [ProjectDesignDocumentController::class, 'store'])
         ->name('organizations.projects.design-documents.store');
     Route::put('organizations/{organization}/projects/{project}/design-documents/{design_document}', [ProjectDesignDocumentController::class, 'update'])
         ->name('organizations.projects.design-documents.update');
     Route::delete('organizations/{organization}/projects/{project}/design-documents/{design_document}', [ProjectDesignDocumentController::class, 'destroy'])
         ->name('organizations.projects.design-documents.delete');

    // QR pages
    Route::get('projects/{project}/csi/{csi}', CSIController::class)->name('projects.csi');
    Route::get('projects/{project}/qr-code/submittals', SubmittalQRCodeController::class)->name('qr-code.submittal');
    Route::get('projects/{project}/qr-code/commissioning', CommissioningDocsQrController::class)->name('qr-code.commissioning');
    Route::get('/qr-codes/{qrcode}/{project?}', QrCodeInternalController::class);


    Route::get('organizations/{organization}/projects/{project}/team', ProjectTeamController::class)->name('organizations.projects.team.index');

    Route::post('project-disciplines/{project_discipline}/users/{user}', [ProjectDisciplineGuestController::class, 'create'])->name('project-discipline.users.create');


    Route::put('project-disciplines/{project_discipline}/users/{user}', [ProjectDisciplineGuestController::class, 'update'])->name('project-discipline.users.update');

    Route::post('project-disciplines/{project_discipline}/users/{user}/resend', [ProjectDisciplineGuestController::class, 'resend'])->name('project-discipline.users.resend');

    Route::put('project-disciplines/{project_discipline}/lead', ProjectDisciplineChangeLeadController::class)->name('project-discipline.change-lead');

    Route::put('project-disciplines/{project_discipline}/switch', ProjectDisciplineChangeSwitchController::class)->name('project-discipline.change-switch');


    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

