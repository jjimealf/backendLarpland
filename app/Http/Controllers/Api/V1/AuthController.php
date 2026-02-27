<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\AuthLoginRequest;
use App\Http\Requests\V1\AuthRegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->error(
                'invalid_credentials',
                'Invalid email or password.',
                401
            );
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success([
            'user' => UserResource::make($user)->resolve(),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful.');
    }

    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol' => 0,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success([
            'user' => UserResource::make($user)->resolve(),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Registration successful.', 201);
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success(
            UserResource::make($request->user()),
            'Current user fetched.'
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->success(null, 'Logout successful.');
    }
}
