<?php

// app/Http/Controllers/AnnouncementController.php
namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Log;
use Exception;

class AnnouncementController extends Controller
{
    // Post a new announcement
    public function create(Request $request)
    {
        try{
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // Notify subscribed users via email
        $subscribedUsers = User::where('subscribed_to_announcements', true)->get();
        foreach ($subscribedUsers as $user) {
            Mail::raw($announcement->content, function ($message) use ($user, $announcement) {
                $message->to($user->email)
                        ->subject('New Announcement: ' . $announcement->title);
            });
        }

        return response()->json([
            'message' => 'Announcement created successfully.',
            'announcement' => $announcement,
        ], 201);
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
    }
    }

    // Get all announcements
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return response()->json($announcements);
    }

    public function updateSubscription(Request $request, $id)
    {
        $request->validate([
            'subscribed_to_announcements' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->update(['subscribed_to_announcements' => $request->subscribed_to_announcements]);

        return response()->json([
            'message' => $user->subscribed_to_announcements
                        ? 'Successfully subscribed to announcements.'
                        : 'Successfully unsubscribed from announcements.',
        ], 200);
    }

     // Update an existing announcement
     public function update(Request $request, $id)
     {
         $request->validate([
             'title' => 'required|string|max:255',
             'content' => 'required|string',
         ]);

         $announcement = Announcement::findOrFail($id);
         $announcement->update([
             'title' => $request->title,
             'content' => $request->content,
         ]);

         return response()->json([
             'message' => 'Announcement updated successfully.',
             'announcement' => $announcement,
         ], 200);
     }

     // Delete an announcement
     public function destroy($id)
     {
         $announcement = Announcement::findOrFail($id);
         $announcement->delete();

         return response()->json([
             'message' => 'Announcement deleted successfully.',
         ], 200);
     }
}
