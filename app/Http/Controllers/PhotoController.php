<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;  // Make sure you import the Photo model
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    public function index($userId) {
        // Fetch photos for the given user
        $photos = Photo::where('user_id', $userId)->get();

        // Return photos as JSON
        return response()->json($photos);
    }

    public function upload(Request $request) {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|integer|exists:users,id',
            'type' => 'required|in:gallery,featured'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Handle file upload
        $file = $request->file('image');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/photos'), $filename);

        // Create new photo record in the database
        $photo = Photo::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'path' => 'uploads/photos/'.$filename,
        ]);

        // Return the newly created photo as JSON
        return response()->json($photo, 201);
    }

    public function destroy($id) {
        // Find photo by ID and delete
        $photo = Photo::findOrFail($id);
        $photo->delete();

        // Return success message
        return response()->json(['message' => 'Photo deleted'], 200);
    }
}
