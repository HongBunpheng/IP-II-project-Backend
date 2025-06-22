<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;
use Illuminate\Support\Facades\Storage;

class JournalController extends Controller
{
    public function index()
    {
        $journals = Journal::with('account')->latest()->get()->map(function ($journal) {
            return [
                'id' => $journal->id,
                'title' => $journal->title,
                'content' => $journal->content,
                'location' => $journal->location,
                'images' => json_decode($journal->images, true),
                'mentions' => json_decode($journal->mentions, true),
                'read_time' => $journal->read_time,
                'created_at' => $journal->created_at,
                'author_name' => optional($journal->account)->name,
                'author_avatar' => $journal->account && $journal->account->profile_picture
                    ? '' . ltrim($journal->account->profile_picture, '/')
                    : null,

            ];
        });

        return response()->json($journals, 200);
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

        $user = $request->user();

        $wordCount = str_word_count($request->input('content'));
        $readTimeMinutes = ceil($wordCount / 200);
        $formattedReadTime = $readTimeMinutes . ' min read';

        $journal = new Journal($request->except(['images']));
        $journal->account_id = $request->user()->id;
        $journal->read_time = $formattedReadTime;
        $journal->account_id = $user->id;

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('uploads', 'public');
                $imagePaths[] = 'storage/' . $path;
            }
        }

        $journal->images = json_encode($imagePaths);
        $journal->save();

        return response()->json([
            'message' => 'Journal saved successfully!',
            'data' => $journal
        ], 201);
    }

public function show($id)
{
    $journal = Journal::with('account')->find($id);

    if (!$journal) {
        return response()->json(['message' => 'Journal not found'], 404);
    }

    return response()->json([
        'id' => $journal->id,
        'title' => $journal->title,
        'content' => $journal->content,
        'location' => $journal->location,
        'images' => json_decode($journal->images, true),
        'mentions' => json_decode($journal->mentions, true),
        'read_time' => $journal->read_time,
        'created_at' => $journal->created_at,
        'account' => [
            'name' => $journal->account->name ?? 'Unknown',
            'profile_picture' => $journal->account->profile_picture
                ? asset('storage/' . $journal->account->profile_picture)
                : null
        ]
    ]);
}


    public function update(Request $request, $id)
    {
        $journal = Journal::findOrFail($id);

        // Check ownership
        if ($request->user()->id !== $journal->account_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'location' => 'required|string',
            'mentions' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        $wordCount = str_word_count($request->input('content'));
        $readTimeMinutes = ceil($wordCount / 200);
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
    try {
        $journal = Journal::findOrFail($id);
        $journal->delete();
        return response()->json(['message' => 'Deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Delete failed'], 500);
    }
}

}
