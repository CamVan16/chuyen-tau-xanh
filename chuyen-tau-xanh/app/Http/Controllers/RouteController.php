<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\RouteStation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class RouteController extends Controller
{
    public function index()
    {
        return view('pages.find-ticket');
    }

    public function search(Request $request)
    {
        $stationA = $request->input('stationA');
        $stationB = $request->input('stationB');
        $ticketType = $request->input('ticketType');
        $departureDate = $request->input('departureDate');
        $returnDate = $request->input('returnDate');

        $goRoutes = $this->findRoutes($stationA, $stationB);
        $this->findTrains($goRoutes, $departureDate);
        // dd($goRoutes);

        $returnRoutes = [];
        if ($ticketType === 'round-trip') {
            $returnRoutes = $this->findRoutes($stationB, $stationA);
            $this->findTrains($returnRoutes, $returnDate);
        }

        return view('pages.reservation', compact('goRoutes', 'returnRoutes'));
    }

    private function findRoutes($stationA, $stationB)
    {
        return Route::select(
            'rs1.route_id as id',
            'rs1.station_name as departure_station',
            'rs2.station_name as arrival_station',
            'r.train_mark',
            'rs1.date_index as departure_date_index',
            'rs2.date_index as arrival_date_index',
            'rs1.departure_time as departure_time',
            'rs2.arrival_time AS arrival_time',
            DB::raw('rs2.km - rs1.km as ratio')
            )
        ->from('routes as r')
        ->join('route_stations as rs1', 'r.id', '=', 'rs1.route_id')
        ->join('route_stations as rs2', 'rs1.route_id', '=', 'rs2.route_id')
        ->where('rs1.station_name', $stationA)
        ->where('rs2.station_name', $stationB)
        ->whereColumn('rs1.km', '<', 'rs2.km')
        ->with('trains')
        ->get();
    }

    private function findTrains(&$routes, $date)
    {
        $findTrainIndex = function ($x, $date, $daysToReduce) {
            $startDate = Carbon::create(2024, 1, 1);
            $currentDate = Carbon::parse($date);
            $daysPassed = $startDate->diffInDays($currentDate);
            return ($daysPassed - 1 - $daysToReduce) % $x;
        };

        foreach ($routes as $route) {
            $departureDateObj = Carbon::parse($date);
            $daysToAdd = $route->arrival_date_index - $route->departure_date_index;
            $arrivalDateObj = $departureDateObj->copy()->addDays($daysToAdd);
                            // echo $departureDateObj;
            $trainCount = $route->trains->count();
            $trainIndex = $findTrainIndex($trainCount, $date, $route->departure_date_index);
            $train = $route->trains->firstWhere('pivot.train_index', $trainIndex);
            if ($train) {
                $route->train_id = $train->id;
                $route->departure_date = $date;
                $route->arrival_date = $arrivalDateObj->format('Y-m-d');
                $route->cars = $train->cars;
                $route->seat_types = $train->seat_types()->select('seat_type_code', 'seat_type_name', 'price')->get();
                $route->ratio = round($this->getMaxKmRoute($route->id) / ($route->ratio), 1);
                // dd(getMaxKmRoute(1001));
            }
        }
    }
    public function getMaxKmRoute($routeId)
    {
        $maxKmRoute = RouteStation::where('route_id', $routeId)->max('km');
        return $maxKmRoute;
    }
}
