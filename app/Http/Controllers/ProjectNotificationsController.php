<?php

namespace App\Http\Controllers;

use App\User;
use App\Project;
use App\TalihoNotification;
use Illuminate\Http\Request;

class ProjectNotificationsController extends Controller
{
    public function read(Request $request, User $user, Project $project)
    {
        abort_unless($request->user()->id === $user->id, 403);

        return $user->unreadNotifications()
            ->where('project_id', $project->id)->get()
            ->each->markAsRead();
    }

    public function delete(Request $request, User $user, TalihoNotification $notification)
    {
        abort_unless($request->user()->id === $user->id, 403);

        // Make sure notification is marked as read before deleting
        if ($notification->unread())
            $notification->markAsRead();

        $result = $notification->delete();

        return response()->json([ 'success' => $result ]);
    }
}
