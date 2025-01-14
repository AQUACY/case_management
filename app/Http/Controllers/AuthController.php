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

class AuthController extends BaseController
{
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

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // Assign role
        $user->roles()->attach(Role::where('name', $request->role)->first());

        // Trigger guest email
        if ($request->role === 'guest') {
            $this->sendGuestCredentials($user, $request->password);
        }

        return response()->json(['success' => 'User registered successfully']);
    }catch (Exception $e) {
            // Log error and return response
            return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
    }
    }

//    login function
   public function login() {
try{
    $credentials = request(['email', 'password']);

    if(! $token = auth()->attempt($credentials)){
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
    try{
    // Validate the request
    $validatedData = $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    // Check if the new password matches the confirmation password (extra check)
    if ($request->new_password !== $request->new_password_confirmation) {
        return response()->json([
            'status' => 'error',
            'message' => 'New password confirmation does not match.',
            'errors' => ['new_password' => 'The new password confirmation does not match.']
        ], 422);
    }

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
    $user->update(['password' => Hash::make($validatedData['new_password'])]);

    // Return a success response
    return response()->json([
        'status' => 'success',
        'message' => 'Password updated successfully.'
    ], 200);
}catch (Exception $e) {
    // Log error and return response
    return response()->json(['message' => 'Error saving record', 'error' => $e->getMessage()], 500);
}
}




// delete account
public function deleteAccount()
{
    $user = auth()->user();
    $user->delete();

    return $this->sendResponse([], 'Account deleted successfully.');
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
