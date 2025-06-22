<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;  // Import Request class
use Illuminate\Support\Facades\Validator;  // Import Validator class

class PostController extends Controller
{
    // Fetch posts for a given user
    public function index($userId) {
        return Post::where('user_id', $userId)->get();
    }

    // Store a new post
    public function store(Request $request) {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate image
            'user_id' => 'required|integer|exists:users,id', // Validate user_id
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Handle file upload
        $file = $request->file('image');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/posts'), $filename);

        // Create new post record
        $post = Post::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'location' => $request->location,
            'date' => $request->date,
            'image_path' => 'uploads/posts/'.$filename,
        ]);

        // Return the created post as a response
        return response()->json($post, 201);  // Return status 201 (Created)
    }

    // Delete a post
    public function destroy($id) {
        $post = Post::findOrFail($id);
        $post->delete();

        // Return success message
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
