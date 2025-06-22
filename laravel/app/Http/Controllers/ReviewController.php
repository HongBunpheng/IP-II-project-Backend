<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Hotel;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'reviewable_type' => 'required|in:hotel,restaurant',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        // Translate 'hotel' or 'restaurant' to full model class
        $model = match ($validated['reviewable_type']) {
            'hotel' => Hotel::class,
            'restaurant' => Restaurant::class,
        };

        // Create the review
        $review = Review::create([
            'account_id' => $validated['account_id'],
            'reviewable_type' => $model,
            'reviewable_id' => $validated['reviewable_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return response()->json($review, 201);
    }

    public function index(Request $request)
    {
        $type = $request->query('type'); // 'hotel' or 'restaurant'
        $id = $request->query('id');

        if (!$type || !$id) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        if (!in_array($type, ['hotel', 'restaurant'])) {
            return response()->json(['error' => 'Invalid reviewable type'], 400);
        }

        $model = match ($type) {
            'hotel' => Hotel::class,
            'restaurant' => Restaurant::class,
        };

        $reviews = Review::where('reviewable_type', $model)
            ->where('reviewable_id', $id)
            ->with('account')
            ->latest()
            ->get();

        return response()->json($reviews);
    }
}
