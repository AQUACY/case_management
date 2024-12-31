<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends BaseController
{
    // register function
   public function register(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required',
        'password' => 'required',
        'c_password' => 'required|same:password',
    ]);

    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());
    }

    $input = $request->all();
    $input['password'] =  bcrypt($input['password']);
    $user = User::create($input);

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

        return $this->sendResponse($success, 'Profile Fetched Successfully');
    }

//    logout function
   public function logout() {
    $success = auth()->logout();
    return $this->sendResponse($success, 'Logged out Successfully');
   }


   protected function respondWithToken($token) {
    return [
        'access_token' => $token,
        'token' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60,
    ];
   }
}
