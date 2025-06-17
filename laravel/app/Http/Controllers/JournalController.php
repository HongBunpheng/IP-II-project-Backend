<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;
use Illuminate\Support\Facades\Storage;

class JournalController extends Controller
{
    public function index()
    {
        return response()->json(Journal::latest()->get(), 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'location' => 'required|string',
            'mentions' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);
    
        // Calculate read time
        $wordCount = str_word_count($request->input('content'));
        $wordsPerMinute = 200; // Average reading speed
        $readTimeMinutes = ceil($wordCount / $wordsPerMinute);
        $formattedReadTime = $readTimeMinutes . ' min read';

        $journal = new Journal($request->except(['images']));
        $journal->read_time = $formattedReadTime; // Add read time to the journal

        $imagePaths = [];
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('uploads', 'public'); // stored in storage/app/public/uploads
                $imagePaths[] = 'storage/' . $path;
            }
        }

        $journal->images = json_encode($imagePaths); // Store image paths as JSON
        $journal->save();
    
        return response()->json([
            'message' => 'Journal saved successfully!',
            'data' => $journal
        ], 201);
    }

    public function show($id)
    {
        $journal = Journal::findOrFail($id);

        return response()->json($journal);
    }

    public function update(Request $request, $id)
    {
        $journal = Journal::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'location' => 'required|string',
            'mentions' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        // Calculate read time
        $wordCount = str_word_count($request->input('content'));
        $wordsPerMinute = 200; // Average reading speed
        $readTimeMinutes = ceil($wordCount / $wordsPerMinute);
        $formattedReadTime = $readTimeMinutes . ' min read';

        $journal->fill($request->except(['images']));
        $journal->read_time = $formattedReadTime;

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $img) {
                $path = $img->store('uploads', 'public');
                $imagePaths[] = 'storage/' . $path;
            }
            $journal->images = json_encode($imagePaths);
        }

        $journal->save();

        return response()->json([
            'message' => 'Journal updated successfully!',
            'data' => $journal
        ], 200);
    }

    public function destroy($id)
    {
        Journal::destroy($id);

        return response()->json(['message' => 'Journal deleted successfully!'], 200);
    }
}