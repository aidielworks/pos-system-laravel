<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
            return $this->errorResponse('Validation error!', 422, ['errors'=>$validator->errors()->all()]);
        }
        $request['password']=Hash::make($request['password']);
        $user = User::create($request->toArray());

        return $this->successResponse('You successfully register. Please login.', $user->toArray());
    }

    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
        {
            return $this->errorResponse('Validation error!', 422, ['errors'=>$validator->errors()->all()]);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $data = ['token' => $token];
                return $this->successResponse('You successfully login.', $data);
            } else {
                return $this->errorResponse('Password mismatch!', 422);
            }
        } else {
            return $this->errorResponse('User does not exist!', 422);
        }
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        return $this->successResponse('You have been successfully logged out!');
    }
}
