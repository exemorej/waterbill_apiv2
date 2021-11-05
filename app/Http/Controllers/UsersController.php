<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function register(CreateUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken($request->email)->plainTextToken;

        $response['status'] = 'success';
        $response['data'] = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response, 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken($request->email)->plainTextToken;

            $response['status'] = 'success';
            $response['data'] = [
                'user' => Auth::user()->only(['name', 'email']),
                'token' => $token
            ];
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Email and password do not match our records';
        }

        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $response['status'] = 'success';
        $response['message'] = 'Logged out!';

        return response()->json($response, 200);
    }
}
