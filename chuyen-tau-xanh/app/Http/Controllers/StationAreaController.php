<?php

namespace App\Http\Controllers;

use App\Models\StationArea;
use App\Models\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StationAreaController extends Controller
{
    public function index()
    {
        return StationArea::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'station_code' => 'required|string',
            'station_name' => 'required|string',
            'km' => 'required|integer',
        ]);

        return StationArea::create($request->all());
    }

    public function show($id)
    {
        return StationArea::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $stationArea = StationArea::findOrFail($id);
        $stationArea->update($request->all());

        return $stationArea;
    }

    public function destroy($id)
    {
        $stationArea = StationArea::findOrFail($id);
        $stationArea->delete();

        return response()->noContent();
    }

    public function showStations()
    {
        return view('pages.stations');
    }


    public function search(Request $request)
    {
        $request->validate([
            'gaDi' => 'required|string',
            'gaDen' => 'required|string',
            'ngay' => 'required|date',
        ]);

        $gaDi = $request->gaDi;
        $gaDen = $request->gaDen;
        $ngay = $request->ngay;

        $goRoutes = $this->findRoutes($gaDi, $gaDen);
        $this->findTrains($goRoutes, $ngay);

        return view('pages.stations', [
            'routes' => $goRoutes,
            'stations' => StationArea::all(),
            'ngay' => $ngay,
        ]);
    }

    private function findRoutes($gaDi, $gaDen)
    {
        return Route::select(
            'rs1.route_id as id',
            'r.train_mark',
            'rs1.date_index as departure_date_index',
            'rs2.date_index as arrival_date_index',
            'rs1.departure_time as departure_time',
            'rs2.arrival_time AS arrival_time'
        )
            ->from('routes as r')
            ->join('route_stations as rs1', 'r.id', '=', 'rs1.route_id')
            ->join('route_stations as rs2', 'rs1.route_id', '=', 'rs2.route_id')
            ->where('rs1.station_name', $gaDi)
            ->where('rs2.station_name', $gaDen)
            ->whereColumn('rs1.km', '<', 'rs2.km')
            ->with('trains')
            ->get();
    }

    private function findTrains(&$routes, $date)
    {
        $findTrainIndex = function ($x, $date) {
            $startDate = Carbon::create(2024, 1, 1);
            $currentDate = Carbon::parse($date);
            $daysPassed = $startDate->diffInDays($currentDate);
            return ($daysPassed - 1) % $x;
        };

        foreach ($routes as $route) {
            $trainCount = $route->trains->count();
            $trainIndex = $findTrainIndex($trainCount, $date);
            $train = $route->trains->firstWhere('pivot.train_index', $trainIndex);
            if ($train) {
                $route->train_id = $train->id;
                $route->cars = $train->cars;
            }
        }
    }
}
