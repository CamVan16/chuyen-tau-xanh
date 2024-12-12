<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function getSoldSeats(Request $request)
    {
        // $carId = $request->input('car_id');
        $carName = $request->input('car_name');
        $trainMark = $request->input('train_mark');
        $departureDate = Carbon::parse($request->input('departure_date'));

        $soldSeats = Schedule::where('day_start', $departureDate)
            ->where('train_mark', $trainMark)
            ->where('car_name', $carName)
            ->whereHas('ticket', function ($query) {
                $query->where('ticket_status', 1); 
            })
            ->pluck('seat_number'); 
            
        return response()->json($soldSeats->toArray());
    }
}
