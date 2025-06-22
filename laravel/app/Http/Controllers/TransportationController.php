<?php

namespace App\Http\Controllers;

use App\Models\Transportation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransportationController extends Controller
{
    public function index()
    {
        $all = Transportation::all();

        $all->transform(function ($t) {
            $t->departure_time = Carbon::parse($t->departure_time)->format('h:i A');
            $t->arrival_time = Carbon::parse($t->arrival_time)->format('h:i A');
            return $t;
        });

        return $all;
    }

    public function store(Request $request)
    {
        $departureTime = Carbon::parse($request->input('departureTime'))->format('H:i');
        $arrivalTime = Carbon::parse($request->input('arrivalTime'))->format('H:i');

        $data = [
            'departure_time' => $departureTime,
            'arrival_time'   => $arrivalTime,
            'departure_city' => $request->input('departureCity'),
            'arrival_city'   => $request->input('arrivalCity'),
            'distance'       => $request->input('distance'),
            'travel_time'    => $request->input('travelTime'),
            'price'          => $request->input('price'),
        ];

        $transport = Transportation::create($data);

        return response()->json($transport, 201);
    }

    public function destroy($id)
    {
        $transport = Transportation::find($id);

        if (!$transport) {
            return response()->json(['message' => 'Transportation not found'], 404);
        }

        $transport->delete();

        return response()->json(['message' => 'Transportation deleted successfully']);
    }
}
