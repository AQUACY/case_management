<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Mail\MessageNotificationMail;
use App\Mail\MessageResponseMail;
use App\Models\Message;
use App\Models\MessageCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;
use Log;

class MessageController extends Controller
{
    public function createMessageCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $category = MessageCategory::create(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }

    public function deleteMessageCategory($id)
    {
        $category = MessageCategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }

     /**
     * Store a newly created message in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        // Validate request data
        $request->validate([
            'category_id' => 'required|exists:message_categories,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        // Create the message
        $message = Message::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // Assign a case manager (Logic to assign case manager can vary)
        // For simplicity, let's assign the first available case manager
        $caseManager = User::whereHas('roles', function ($query) {
            $query->where('name', 'Case Manager');
        })->first();

        if (!$caseManager) {
            throw new Exception('No case manager found');
        }

        if ($caseManager) {
            $message->case_manager_id = $caseManager->id;
            $message->save();

            // Send email notification to the case manager
            $platformUrl = config('app.url'); // Ensure 'app.url' is set in .env

            Mail::to($caseManager->email)->send(new MessageNotificationMail($message, $platformUrl));
        }


        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully and case manager notified.',
            'data' => $message,
        ], 201);
    }

    /**
     * Respond to an existing message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $messageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondToMessage(Request $request, $messageId)
    {
        // Validate the response
        $request->validate([
            'response' => 'required|string',
        ]);

        // Find the message
        $message = Message::findOrFail($messageId);

        // Ensure the authenticated user is a case manager and is assigned to this message
        if ($request->user()->id !== $message->case_manager_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to respond to this message.',
            ], 403);
        }

        // Update the message with the response
        $message->response = $request->response;
        $message->status = 'answered';
        $message->save();

        // Send email notification to the user
        $platformUrl = config('app.url'); // Ensure 'app.url' is set in .env
        Mail::to($message->user->email)->send(new MessageResponseMail($message, $platformUrl));

        return response()->json([
            'success' => true,
            'message' => 'Response saved successfully and user notified.',
            'data' => $message,
        ], 200);
    }

    /**
     * Rate a response from a case manager.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $messageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function rateResponse(Request $request, $messageId)
    {
        // Validate the rating
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Find the message
        $message = Message::findOrFail($messageId);

        // Ensure the authenticated user is the one who created the message
        if ($request->user()->id !== $message->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to rate this response.',
            ], 403);
        }

        // Update the rating
        $message->rating = $request->rating;
        $message->save();

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully.',
        ], 200);
    }

}