<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '0',
                'rol' => '0',
                'message' => 'Datos de acceso invalidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => '0',
                'rol' => '0',
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => '1',
            'message' => 'Login exitoso',
            'rol' => (string) $user->rol,
            'userId' => $user->id,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:25',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'rol' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '0',
                'rol' => '0',
                'message' => 'Datos de registro invalidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol ?? 0,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => '1',
            'message' => 'Registro exitoso',
            'rol' => (string) $user->rol,
            'userId' => $user->id,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
