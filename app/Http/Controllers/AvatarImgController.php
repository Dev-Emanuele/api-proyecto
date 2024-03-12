<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AvatarImgController extends Controller
{
    public function updateAvatar(Request $request, $id)
    {
        Log::info('Request Data:', ['request' => $request->all()]);

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,svg|max:5120', //validation
        ]);   

        if ($validator->fails()) {
            // Validation failed
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // HTTP status code 422 for unprocessable entity
        } 
 
        // Find the user by ID
        $user = User::findOrFail($id);

        if(!$user) {
            return response()->json(['message' => 'user not found']);
        }        

                // Check if the uploaded file is an SVG
        if ($request->hasFile('avatar') && $request->file('avatar')->getClientOriginalExtension() === 'svg') {
            // Generate a unique name for the SVG file
            $avatarName = $user->id . '_avatar' . time() . '.svg';

            // Move the uploaded SVG file to the desired directory
            $stored = $request->file('avatar')->move(public_path('avatars'), $avatarName);

            if (!$stored) {
                // Log an error or return a response indicating failure
                return response()->json(['message' => 'svg Avatar storage failed']);
            }

            // Now $avatarName contains the filename of the stored SVG
        } else {
            // For non-SVG files (e.g., PNG), proceed with your existing logic
            $avatarName = $user->id.'_avatar'.time().'.'.$request->avatar->getClientOriginalExtension() . "svg";
            $stored = $request->avatar->storeAs('avatars', $avatarName, 'public');

            if (!$stored) {
                // Log an error or return a response indicating failure
                return response()->json(['message' => 'png Avatar storage failed']);
            }
        }

        // Update the user's avatar column
        $user->avatar = $avatarName;
        $user->save();

        return response()->json(['message' => 'Tu Avatar ha sido actualizado con exito']); 
    }

    public function getAvatar($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Check if the user has an avatar
        if (!$user->avatar) {
            return response()->json(['message' => 'User does not have an avatar'], 404);
        }

        // Get the avatar file path
        $avatarPath = 'avatars/' . $user->avatar;

        // Check if the avatar file exists
        if (!Storage::disk('public')->exists($avatarPath)) {
            return response()->json(['message' => 'Avatar not found'], 404);
        }

        // Get the contents of the avatar file
        $avatarContents = Storage::disk('public')->get($avatarPath);

        // Determine the MIME type of the image
        $mimeType = mime_content_type(public_path($avatarPath));

        // Set response headers
        $headers = [
            'Content-Type' => $mimeType,
        ];

        // Return the image as a response
        return response($avatarContents, 200)->withHeaders($headers);
    }
}