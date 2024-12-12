<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ScheduleController;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeatController extends Controller
{
    private $scheduleController;
    
    public function __construct(ScheduleController $scheduleController) {
        $this->scheduleController = $scheduleController;
    }
    public function getSeatsByCarId(Request $request)
    {
        $carId = $request->input('car_id');
        $soldSeats = $this->scheduleController->getSoldSeats($request)->original;
        $seats = Seat::where('car_id', $carId)->get();
        $seats->each(function ($seat) use ($soldSeats) {
            if (in_array($seat->seat_index, $soldSeats)) {
                $seat->seat_status = 1; 
            }
        });
        return response()->json($seats);
    }

}
