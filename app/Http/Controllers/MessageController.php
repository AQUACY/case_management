<?php

namespace App\Http\Controllers;

use App\Mail\MessageNotificationMail;
use App\Mail\MessageResponseMail;
use App\Models\Message;
use App\Models\MessageCategory;
use App\Models\User;
use App\Models\Cases;
use App\Models\MessageConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;
use Log;
use Spatie\Permission\Models\Role;
use App\Events\NewMessageEvent;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function createMessageCategory(Request $request)
    {
        try{
        $request->validate(['name' => 'required|string|max:255']);

        $category = MessageCategory::create(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
    }
    }

    // get all message categories
    public function getMessageCategories()
    {
        try{
        $categories = MessageCategory::all();
        return response()->json(['success' => true, 'data' => $categories], 200);
    }catch (Exception $e) {
        return response()->json(['message' => 'Error fetching categories', 'error' => $e->getMessage()], 500);
    }
    }

    public function deleteMessageCategory($id)
    {
        try{
        $category = MessageCategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
    }

    public function updateMessageCategory($id, Request $request)
    {
        try{
        $category = MessageCategory::findOrFail($id);
        $category->update($request->all());
        return response()->json(['message' => 'Category updated successfully'], 200);
    }catch (Exception $e) {
        return response()->json(['message' => 'Error updating record', 'error' => $e->getMessage()], 500);
    }
    }

     /**
     * Store a newly created message in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $caseId)
{
    try{
    // Validate request data
    $request->validate([
        'category_id' => 'required|exists:message_categories,id',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    // Ensure that the case exists by case_id
    $case = Cases::findOrFail($caseId);

    // Create the message
    $message = Message::create([
        'user_id' => $request->user()->id,
        'case_id' => $caseId,  // Associate the message with the case
        'category_id' => $request->category_id,
        'subject' => $request->subject,
        'message' => $request->message,
        'status' => 'pending',
    ]);

    // Assign a case manager (Logic to assign case manager can vary)
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
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
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
        try {
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
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
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

// case manager sending message to user(client)
    public function sendMessageToUser(Request $request)
{
    try {
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'category_id' => 'required|exists:message_categories,id',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    $message = Message::create([
        'user_id' => $request->user_id,
        'category_id' => $request->category_id,
        'subject' => $request->subject,
        'message' => $request->message,
        'status' => 'pending',
        'sender_type' => 'Case Manager',
        'case_manager_id' => Auth::id(),
    ]);

    // Notify the user
    $platformUrl = config('app.url'); // Ensure 'app.url' is set in .env
    Mail::to($message->user->email)->send(new MessageNotificationMail($message, $platformUrl));

    // Broadcast the new message event for real-time notification
    broadcast(new NewMessageEvent($message))->toOthers();

    return response()->json([
        'success' => true,
        'message' => 'Message sent to user successfully.',
        'data' => $message,
    ], 201);
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
}

// user responding to case manager message
public function respondToCaseMessage(Request $request, $messageId)
{
    try{
    $request->validate([
        'response' => 'required|string',
    ]);

    $message = Message::findOrFail($messageId);
    $message->response = $request->response;
    $message->status = 'answered';
    $message->save();


    // Notify the case manager
    $platformUrl = config('app.url'); // Ensure 'app.url' is set in .env
    Mail::to($message->caseManager->email)->send(new MessageResponseMail($message, $platformUrl));

    return response()->json([
        'success' => true,
        'message' => 'Response sent successfully.',
        'data' => $message,
    ]);
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
}
public function getMessagesByCaseId($caseId)
{
    try {
        $messages = Message::where('case_id', $caseId)
                         ->with(['user', 'caseManager', 'category'])
                         ->orderBy('created_at', 'desc')
                         ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error fetching messages',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function replyToMessage(Request $request, $messageId)
{
    try {
        $request->validate([
            'content' => 'required|string',
        ]);

        $message = Message::with(['user', 'caseManager'])->findOrFail($messageId);

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Determine sender type
        $senderType = 'user';
        if ($user->hasRole('Case Manager') || $user->hasRole('Administrator')) {
            $senderType = 'case_manager';

            // For case managers, verify if they are assigned to this message
            if ($user->hasRole('Case Manager') && $message->case_manager_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to reply to this message'
                ], 403);
            }
            // Admins can reply to any message
        } else {
            // Verify if the user owns this message
            if ($message->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to reply to this message'
                ], 403);
            }
        }

        // Create the reply
        $reply = MessageConversation::create([
            'message_id' => $messageId,
            'content' => $request->content,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'is_read' => false
        ]);

        // Load the sender relationship for the broadcast
        $reply->load('sender:id,name');

        // Broadcast the new message event for real-time chat
        broadcast(new NewMessageEvent($reply))->toOthers();

        return response()->json([
            'message' => 'Reply sent successfully',
            'data' => $reply
        ]);

    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error sending reply',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getMessageConversation($messageId)
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $message = Message::with([
            'user:id,name,email',
            'caseManager:id,name,email',
            'category:id,name'
        ])->findOrFail($messageId);

        // Check if user is authorized to view this conversation
        $isAdmin = $user->hasRole('Administrator');
        $isCaseManager = $user->hasRole('Case Manager');

        // Regular users can only view their own conversations
        if (!$isAdmin && !$isCaseManager && $user->id !== $message->user_id) {
            return response()->json([
                'message' => 'You are not authorized to view this conversation'
            ], 403);
        }

        // Case managers can only view conversations they're assigned to
        if ($isCaseManager && !$isAdmin && $user->id !== $message->case_manager_id) {
            return response()->json([
                'message' => 'You are not authorized to view this conversation'
            ], 403);
        }

        // Get all replies for this message
        $conversation = MessageConversation::where('message_id', $messageId)
            ->with('sender:id,name')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read for the current user
        MessageConversation::where('message_id', $messageId)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'message' => $message,
            'conversation' => $conversation
        ]);

    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error retrieving conversation',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getUnreadMessageCount()
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $isAdmin = $user->hasRole('Administrator');
        $isCaseManager = $user->hasRole('Case Manager');

        // For regular users
        if (!$isCaseManager && !$isAdmin) {
            $count = MessageConversation::whereHas('message', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('sender_type', 'case_manager')
              ->where('is_read', false)
              ->count();
        }
        // For case managers
        elseif ($isCaseManager && !$isAdmin) {
            $count = MessageConversation::whereHas('message', function ($q) use ($user) {
                $q->where('case_manager_id', $user->id);
            })->where('sender_type', 'user')
              ->where('is_read', false)
              ->count();
        }
        // For admins - can see all unread messages
        else {
            $count = MessageConversation::where('is_read', false)
                ->where('sender_id', '!=', $user->id)
                ->count();
        }

        return response()->json([
            'unread_count' => $count
        ]);

    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error getting unread count',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function markAsRead($messageId)
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $message = Message::with(['user', 'caseManager'])->findOrFail($messageId);

        // Check if user is authorized to access this message
        $isAdmin = $user->hasRole('Administrator');
        $isCaseManager = $user->hasRole('Case Manager');

        // Regular users can only access their own messages
        if (!$isAdmin && !$isCaseManager && $user->id !== $message->user_id) {
            return response()->json([
                'message' => 'You are not authorized to access this message'
            ], 403);
        }

        // Case managers can only access messages they're assigned to
        if ($isCaseManager && !$isAdmin && $user->id !== $message->case_manager_id) {
            return response()->json([
                'message' => 'You are not authorized to access this message'
            ], 403);
        }

        // Mark all unread messages in the conversation as read
        MessageConversation::where('message_id', $messageId)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'message' => 'Messages marked as read successfully'
        ]);

    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error marking messages as read',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get all active conversations for the authenticated user
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function getActiveConversations()
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $isAdmin = $user->hasRole('Administrator');
        $isCaseManager = $user->hasRole('Case Manager');

        // Query to get all conversations
        $query = Message::with([
            'user:id,name,email',
            'caseManager:id,name,email',
            'category:id,name',
            'latestConversation'
        ]);

        // Filter based on user role
        if (!$isAdmin && !$isCaseManager) {
            // Regular users only see their own messages
            $query->where('user_id', $user->id);
        } elseif ($isCaseManager && !$isAdmin) {
            // Case managers only see messages assigned to them
            $query->where('case_manager_id', $user->id);
        }
        // Admins see all messages

        $conversations = $query->orderBy('updated_at', 'desc')->get();

        // Get unread count for each conversation
        foreach ($conversations as $conversation) {
            $conversation->unread_count = MessageConversation::where('message_id', $conversation->id)
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', false)
                ->count();
        }

        return response()->json([
            'success' => true,
            'conversations' => $conversations
        ]);

    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error retrieving conversations',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get real-time updates for a specific conversation
 *
 * @param int $messageId
 * @return \Illuminate\Http\JsonResponse
 */
public function getConversationUpdates($messageId)
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $message = Message::findOrFail($messageId);

        // Check authorization
        $isAdmin = $user->hasRole('Administrator');
        $isCaseManager = $user->hasRole('Case Manager');

        if (!$isAdmin && !$isCaseManager && $user->id !== $message->user_id) {
            return response()->json([
                'message' => 'You are not authorized to view this conversation'
            ], 403);
        }

        if ($isCaseManager && !$isAdmin && $user->id !== $message->case_manager_id) {
            return response()->json([
                'message' => 'You are not authorized to view this conversation'
            ], 403);
        }

        // Get new messages since the last timestamp provided
        $query = MessageConversation::where('message_id', $messageId)
            ->with('sender:id,name');

        // Check if last_timestamp parameter exists
        if (request()->has('last_timestamp') && request('last_timestamp')) {
            $lastTimestamp = request('last_timestamp');

            try {
                // Log the received timestamp for debugging
                \Log::info("Received last_timestamp: " . $lastTimestamp);

                // Try to parse the timestamp
                $timestamp = new \DateTime($lastTimestamp);
                $formattedTimestamp = $timestamp->format('Y-m-d H:i:s');

                // Log the formatted timestamp
                \Log::info("Formatted timestamp: " . $formattedTimestamp);

                // Apply the filter
                $query->where('created_at', '>', $formattedTimestamp);
            } catch (\Exception $e) {
                // Log the error but continue without the filter
                \Log::warning("Error parsing timestamp: " . $e->getMessage());
            }
        }

        $newMessages = $query->orderBy('created_at', 'asc')->get();

        // Log the query results for debugging
        \Log::info("Found " . $newMessages->count() . " new messages");

        return response()->json([
            'success' => true,
            'new_messages' => $newMessages
        ]);

    } catch (Exception $e) {
        \Log::error("Error in getConversationUpdates: " . $e->getMessage());
        return response()->json([
            'message' => 'Error retrieving conversation updates',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Mark a specific message as read in real-time
 *
 * @param int $conversationId
 * @return \Illuminate\Http\JsonResponse
 */
public function markConversationMessageAsRead($conversationId)
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $conversationMessage = MessageConversation::findOrFail($conversationId);
        $message = Message::findOrFail($conversationMessage->message_id);

        // Check authorization
        $isAdmin = $user->hasRole('Administrator');
        $isCaseManager = $user->hasRole('Case Manager');

        if (!$isAdmin && !$isCaseManager && $user->id !== $message->user_id) {
            return response()->json([
                'message' => 'You are not authorized to access this message'
            ], 403);
        }

        if ($isCaseManager && !$isAdmin && $user->id !== $message->case_manager_id) {
            return response()->json([
                'message' => 'You are not authorized to access this message'
            ], 403);
        }

        // Mark the specific message as read
        if ($conversationMessage->sender_id !== $user->id) {
            $conversationMessage->is_read = true;
            $conversationMessage->save();

            // Broadcast that the message has been read
            broadcast(new NewMessageEvent([
                'type' => 'read_receipt',
                'conversation_id' => $conversationId,
                'reader_id' => $user->id
            ]))->toOthers();
        }

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read'
        ]);

    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error marking message as read',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
