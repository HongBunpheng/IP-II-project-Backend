<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        return response()->json(Restaurant::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'promotion' => 'required',
            'location' => 'required',
            'address' => 'required',
            'contact' => 'required',
            'rating' => 'required|numeric|min:0|max:5',
            'price' => 'required|numeric',
            'details' => 'required',
            'image.*' => 'nullable|image',
            'detail_image.*' => 'nullable|image',
        ]);

        $restaurant = new Restaurant($request->except(['image', 'detail_image']));

        // Store main images
        if ($request->hasFile('image')) {
            $imagePaths = [];
            foreach ($request->file('image') as $img) {
                $imagePaths[] = $img->store('uploads', 'public');
            }
            $restaurant->image = $imagePaths;
        }

        // Store detail images
        if ($request->hasFile('detail_image')) {
            $detailImagePaths = [];
            foreach ($request->file('detail_image') as $img) {
                $detailImagePaths[] = $img->store('uploads', 'public');
            }
            $restaurant->detail_image = $detailImagePaths;
        }

        $restaurant->save();

        return response()->json(['message' => 'Restaurant saved', 'restaurant' => $restaurant], 201);
    }

    public function show(Restaurant $restaurant)
    {
        return response()->json($restaurant);
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'image.*' => 'nullable|image',
            'detail_image.*' => 'nullable|image',
        ]);

        $restaurant->fill($request->except(['image', 'detail_image']));

        if ($request->hasFile('image')) {
            $imagePaths = [];
            foreach ($request->file('image') as $img) {
                $imagePaths[] = $img->store('uploads', 'public');
            }
            $restaurant->image = $imagePaths;
        }

        if ($request->hasFile('detail_image')) {
            $detailImagePaths = [];
            foreach ($request->file('detail_image') as $img) {
                $detailImagePaths[] = $img->store('uploads', 'public');
            }
            $restaurant->detail_image = $detailImagePaths;
        }

        $restaurant->save();

        return response()->json($restaurant);
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return response()->json(null, 204);
    }
}
