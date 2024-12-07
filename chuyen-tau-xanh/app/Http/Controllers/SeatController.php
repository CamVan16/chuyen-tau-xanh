<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeatController extends Controller
{
    public function getSeatsByCarId(Request $request)
    {
        // dd("B");
        $carId = $request->input('car_id');
        // dd($carId);
        $seats = Seat::where('car_id', $carId)->get();
        // dd($seats->toArray());
        // return view('pages.reservation', compact('seats'));
        return response()->json($seats);
    }
}
