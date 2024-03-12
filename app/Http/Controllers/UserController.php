<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    //--------------------------------- POST ------------------------------

    public function createUser(Request $request)
    {
        $user = new User;
        $user->fill($request->all());
        $user->save();

        return response()->json(['message' => 'User created successfully'], 201);
    }

    //--------------------------------- GET --------------------------------

    public function getUser(Request $request)
    {
        return response()->json(['data' => $request->user()]);
        
    }

    public function getUserById($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json(['data' => $user]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function getAllUsers(Request $request)
    {
        try {
            // Get the columns from the request, or default to all columns
            $columns =['id', 'name', 'email', 'role'];

            // Get all users with only the specified columns
            $users = User::select($columns)->get();

            // Return the users
            return response()->json(['data' => $users], 200);
        } catch (\Exception $e) {
            // Log the exception message
            Log::error($e->getMessage());
    
            // Return a 500 error response
            return response()->json(['error' => 'An error occurred while retrieving users.'], 500);
        }
    }

    //--------------------------------- UPDATE -----------------------------

    public function updateUser(Request $request)
    {
        $user = $request->user();

        if ($user) {

            //no cambiar contraseña si llega una palabra clave
            if ($request->has('password') && $request->password === 'donotchange0') {
                $requestData = $request->except('password');
            } else {
                $requestData = $request->all();
            }
        $user->fill($requestData);
        $user->save();

        return response()->json(['message' => 'Usuario actualizado con exito'], 200);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }

    public function updateUserById(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {

            //no cambiar contraseña si llega una palabra clave
            if ($request->has('password') && $request->password === 'donotchange0') {
                $requestData = $request->except('password');
            } else {
                $requestData = $request->all();
            }

            $user->fill($requestData);
            $user->save();

            return response()->json(['message' => 'Usuario actualizado con exito'], 200);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }



    //--------------------------------- DELETE ------------------------------

    public function deleteUser(Request $request)
    {
        // Get the currently authenticated user
        $user = $request->user();

        // Delete the user
        $user->delete();

        // Return a response
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function deleteUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Delete the user
        $user->delete();

        // Return a response
        return response()->json(['message' => 'User deleted successfully'], 200);
    }    
}


