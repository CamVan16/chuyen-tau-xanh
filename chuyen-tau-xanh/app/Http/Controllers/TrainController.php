<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\RouteStation;
use App\Models\TrainRoute;
use App\Models\SeatType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrainController extends Controller
{
    public function index()
    {
        // $train = Train::find(12001);
        // $cars = $train->cars;
        // dd($cars->toArray());
    }

    public function showDetails($id, Request $request)
    {
        $route = Route::with(['routeStations'])->findOrFail($id);
        $routeStations = $route->routeStations->sortBy('km');
        $trainIds = TrainRoute::where('route_id', $id)->pluck('train_id');

        // Lấy các loại ghế cho tất cả các chuyến tàu trong train_ids
        $seatTypes = SeatType::whereIn('train_id', $trainIds)->with('seats')->get();
        return view('pages.station_train_detail', [
            'route' => $route,
            'routeStations' => $routeStations,
            'seatTypes' => $seatTypes,
            'gaDi' => $request->gaDi,
            'gaDen' => $request->gaDen,
            'ngay' => $request->ngay,
        ]);
    }
}
