<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\RouteStation;
use App\Models\Train;
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

    public function showDetails($id, $gaDi, $gaDen, $ngay)
    {
        dd($id, $gaDi, $gaDen, $ngay);
        $route = Route::with(['routeStations'])->findOrFail($id);

        foreach ($route->routeStations as $station) {
            $departureTime = Carbon::parse($station->departure_time);
            $arrivalTime = Carbon::parse($station->arrival_time);

            if ($arrivalTime < $departureTime) {
                $arrivalTime->addDay();
            }

            $station->adjusted_arrival_time = $arrivalTime;
        }

        return view('pages.station_train_detail', compact('route', 'gaDi', 'gaDen', 'ngay'));
    }
}
