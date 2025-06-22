<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Place::query();

        if ($request->has('province')) {
            $query->where('province', $request->province);
        }

        if ($request->has('budget')) {
            $query->where('price', '<=', $request->budget);
        }

        $places = $query->get()->map(function ($place) {
            $place->images = array_map(function ($img) {
                return $img[0] === '/' ? $img : '/' . $img;
            }, $place->images);
            return $place;
        });

        return response()->json($places);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($data['images']) && is_array($data['images'])) {
            $data['images'] = array_map(function ($img) {
                return $img[0] === '/' ? $img : '/' . $img;
            }, $data['images']);
            // $data['images'] = json_encode($data['images']);
        }
        $place = Place::create($data);
        return response()->json($place);
    }
}
