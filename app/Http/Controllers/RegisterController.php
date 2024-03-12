<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
     //en la request recibe name, email, password, device_name
     public function register(Request $request)
     {
         //validación
         $validator = Validator::make($request->all(), [
             'name' => 'required',
             'email' => 'required|email|unique:users',
             'password' => 'required|min:8|confirmed',
         ]);

          // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed!',
                'errors' => $validator->errors()
            ], 400);
        }
 
         //insert new user
         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password),
         ]);
 
         //crea un token
         $token = $user->createToken($request->device_name ?? 'unset')->plainTextToken;
 
         //devuelve respuesta positiva
         return response()->json([
             'message' => 'Registration successful!',
             'token' => $token,
             'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role
            ]
         ], 201);
     }
}
