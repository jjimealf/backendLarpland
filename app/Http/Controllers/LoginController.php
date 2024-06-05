<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $response = ["status" => "0", "message" => ""];
        $data = json_decode($request->getContent());
        $user = User::where('email', $data->email)->first();
        if ($user) {
            if (password_verify($data->password, $user->password)) {
                $response["status"] = "1";
                $response["message"] = "Login exitoso";
            } else {
                $response["message"] = "Contraseña incorrecta";
            }
        } else {
            $response["message"] = "Usuario no encontrado";
        }
        return response()->json($response);
    }
}
