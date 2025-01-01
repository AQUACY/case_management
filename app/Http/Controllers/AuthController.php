<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    // register function
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required',
        'c_password' => 'required|same:password',
    ]);

    if ($validator->fails()) {
        return $this->sendError('Validation Error.', $validator->errors());
    }

    $input = $request->all();
    $input['password'] = bcrypt($input['password']);
    $user = User::create($input);

    // Assign default role (e.g., 'Guest') to the user
    $defaultRole = Role::where('name', 'Guest')->first();
    if ($defaultRole) {
        $user->roles()->attach($defaultRole->id);
    }

    $success['user'] = $user;

    return $this->sendResponse($success, 'User Registered Successfully.');
}

//    login function
   public function login() {

    $credentials = request(['email', 'password']);

    if(! $token = auth()->attempt($credentials)){
        return $this->sendError('Unauthorized.',['error' => 'Unauthorized']);
    }

    $success = $this->respondWithToken($token);

    return $this->sendResponse($success, 'User Login Successfully');

   }


    //    profile function
    public function profile() {
        $success = auth()->user();

        return $this->sendResponse($success, 'Profile Fetched Successfully');
    }

//    refresh function
    public function refresh() {
        $success = $this->respondWithToken(auth()->refresh());

        return $this->sendResponse($success, 'Token Fetched Successfully');
    }

//    logout function
   public function logout() {
    $success = auth()->logout();
    return $this->sendResponse($success, 'Logged out Successfully');
   }

//    forgot password

public function forgotPassword(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? $this->sendResponse([], __($status))
        : $this->sendError('Error', __($status));
}

// reset password
public function resetPassword(Request $request)
{
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
}

// update account
public function updateAccount(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
    ]);

    $user->update($validated);

    return $this->sendResponse($user, 'Account updated successfully.');
}


// change password
public function updatePassword(Request $request)
{
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
}




// delete account
public function deleteAccount()
{
    $user = auth()->user();
    $user->delete();

    return $this->sendResponse([], 'Account deleted successfully.');
}


   protected function respondWithToken($token) {
    return [
        'access_token' => $token,
        'token' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60,
    ];
   }
}
