<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Mail\GuestCredentialsMail;
use Illuminate\Support\Facades\Mail;
use Log;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Mail\NewUserCredentials;

class AuthController extends BaseController
{
    /**
     * Get list of users with their case counts, filtered by role if specified
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUsers(Request $request)
    {
        try {
            $query = User::with('roles')
                ->withCount('cases');

            // Filter by role if provided in query params
            if ($request->has('role')) {
                $query->whereHas('roles', function($q) use ($request) {
                    $q->where('name', $request->role);
                });
            }

            $users = $query->get()->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'case_count' => $user->cases_count
                ];
            });

            return $this->sendResponse($users, 'Users retrieved successfully');

        } catch (Exception $e) {
            Log::error('Error retrieving users: ' . $e->getMessage());
            return $this->sendError('Error retrieving users', ['error' => $e->getMessage()]);
        }
    }
    // register function
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role' => 'required|exists:roles,name',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Prevent administrator role creation
            if (strtolower($request->role) === 'administrator') {
                return response()->json([
                    'error' => 'Administrator accounts cannot be created through registration'
                ], 403);
            }

            // Get role ID first
            $role = Role::where('name', $request->role)->first();

            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }

            // Create user with role_id
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => $role->id
            ]);

            // Also attach role to pivot table
            $user->roles()->attach($role->id);

            // Send credentials email to all new users
            try {
                Mail::to($user->email)->send(new NewUserCredentials($user, $request->password));
            } catch (\Exception $e) {
                \Log::error('Failed to send credentials email: ' . $e->getMessage());
                // Continue execution even if email fails
            }

            // Additional guest-specific email if needed
            if ($request->role === 'client') {
                $this->sendGuestCredentials($user, $request->password);
            }

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully. Login credentials have been sent to the registered email.',
                'user' => $user
            ]);

        } catch (Exception $e) {
            // Log error and return response
            return response()->json([
                'success' => false,
                'message' => 'Error saving record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

//    login function
   public function login() {
try{
    $credentials = request(['email', 'password']);

    if(! $token = Auth::attempt($credentials)){
        return $this->sendError('Unauthorized.',['error' => 'Unauthorized']);
    }

    $success = $this->respondWithToken($token);

    return $this->sendResponse($success, 'User Login Successfully');
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
    }
   }


    //    profile function
    public function profile() {
        try{
        $success = auth()->user();

        return $this->sendResponse($success, 'Profile Fetched Successfully');
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
    }

//    refresh function
    public function refresh() {
        try{        $success = $this->respondWithToken(auth()->refresh());

        return $this->sendResponse($success, 'Token Fetched Successfully');
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
    }

//    logout function
   public function logout() {
    try{
    $success = auth()->logout();
    return $this->sendResponse($success, 'Logged out Successfully');
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
}

//    forgot password

public function forgotPassword(Request $request)
{
    try{
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );
    return $status === Password::RESET_LINK_SENT
        ? $this->sendResponse([], __($status))
        : $this->sendError('Error', __($status));
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
    }

// reset password
public function resetPassword(Request $request)
{
    try{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) use ($request) {
            $user->forceFill([
                'password' => Hash::make($request->password)
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? $this->sendResponse([], __($status))
        : $this->sendError('Error', __($status));
    }catch (Exception $e) {
        // Log error and return response
        return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
    }

// update account
public function updateAccount(Request $request)
{
    try{
    $user = auth()->user();

    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
    ]);

    $user->update($validated);

    return $this->sendResponse($user, 'Account updated successfully.');
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
}


// change password
public function updatePassword(Request $request)
{
    try {
        // Validate the request
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8',
            'new_password_confirmation' => 'required|same:new_password'
        ], [
            'new_password_confirmation.same' => 'The new password confirmation does not match.',
            'new_password.min' => 'The new password must be at least 8 characters.',
        ]);

        // Get the authenticated user
        $user = auth()->user();

        // Check if the current password matches the stored password
        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password is incorrect.',
                'errors' => ['current_password' => 'Current password is incorrect.']
            ], 422);
        }

        // Update the user's password
        $user->update([
            'password' => Hash::make($validatedData['new_password'])
        ]);

        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully.'
        ], 200);
    } catch (ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (Exception $e) {
        // Log error and return response
        return response()->json([
            'status' => 'error',
            'message' => 'Error updating password',
            'error' => $e->getMessage()
        ], 500);
    }
}




// delete account
public function deleteAccount()
{
    $user = auth()->user();
    $user->delete();

    return $this->sendResponse([], 'Account deleted successfully.');
}

// delete user account as admin
public function deleteUserAsAdmin(Request $request)
{
    try {
        // Validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Check if authenticated user is admin
        if (!auth()->user()->hasRole('Administrator')) {
            return $this->sendError('Unauthorized.', ['error' => 'Only administrators can delete user accounts']);
        }

        // Find and delete user
        $user = User::find($request->user_id);

        // Prevent admin from deleting their own account through this endpoint
        if ($user->id === auth()->id()) {
            return $this->sendError('Invalid Operation.', ['error' => 'Cannot delete your own account through this endpoint']);
        }

        $user->delete();

        return $this->sendResponse([], 'User account deleted successfully.');

    } catch (Exception $e) {
        Log::error('Error deleting user account: ' . $e->getMessage());
        return $this->sendError('Error deleting user account', ['error' => $e->getMessage()]);
    }
}

// update user as admin
public function updateUserAsAdmin(Request $request)
{
    try {
        // Validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $request->user_id,
            'role' => 'sometimes|exists:roles,name',
            'subscribed_to_announcements' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Check if authenticated user is admin
        if (!auth()->user()->hasRole('Administrator')) {
            return $this->sendError('Unauthorized.', ['error' => 'Only administrators can update user accounts']);
        }

        // Find user
        $user = User::find($request->user_id);

        // Update user fields if provided
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('subscribed_to_announcements')) {
            $user->subscribed_to_announcements = $request->subscribed_to_announcements;
        }

        // Update role if provided
        if ($request->has('role')) {
            $role = Role::where('name', $request->role)->first();
            $user->roles()->sync([$role->id]);
        }

        $user->save();

        return $this->sendResponse([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'subscribed_to_announcements' => $user->subscribed_to_announcements
            ]
        ], 'User updated successfully.');

    } catch (Exception $e) {
        Log::error('Error updating user account: ' . $e->getMessage());
        return $this->sendError('Error updating user account', ['error' => $e->getMessage()]);
    }
}


// respond with user token
   protected function respondWithToken($token) {
    return [
        'access_token' => $token,
        'token' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60,
    ];
   }

// send email to the client
private function sendGuestCredentials(User $user, $password)
{
    // $details = [
    //     'name' => $user->name,
    //     'email' => $user->email,
    //     'password' => $password,
    // ];
    Mail::to($user->email)->send(new GuestCredentialsMail($user, $password));
}

}
