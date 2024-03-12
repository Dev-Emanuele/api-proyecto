<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{ 
    public function login(Request $request)
    {
        if (Auth::check()) { //no funciona, devuelve false
            return response()->json(['message' => 'You are already logged in.'], 200);
        }
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //pide el correo en la bbdd
        $user = User::where('email', $request->email)->first();

        //controla si el correo existe en la bbdd, si no existe devuelve respuesta negativa
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }
        //crea un token
        $token = $user->createToken($request->device_name ?? 'unset')->plainTextToken;

        //devuelve respuesta positiva
        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        //elimina el token
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        //devuelve respuesta positiva
        return response()->json([
            'message' => 'Logout successful!'
        ], 200);
    }
}
