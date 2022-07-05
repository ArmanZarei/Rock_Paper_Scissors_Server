<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(UserRegistrationRequest $request)
    {
        $user = User::create($request->validated());

        event(new Registered($user));

        return $user;
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->get('email'))->first();

        if (!Hash::check($request->get('password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Password is incorrect.'],
            ]);
        }

        $user->tokens()->delete();

        return response()->json([
            'token' => $user->createToken('test')->plainTextToken,
            'id' => $user->id,
        ]);
    }
}
