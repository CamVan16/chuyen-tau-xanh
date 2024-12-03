<?php

namespace App\Http\Controllers;

use App\Models\RouteStation;
use Illuminate\Http\Request;

class RouteStationController extends Controller
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

        $routes = RouteStation::select(
            'rs1.route_id',
            'r.train_mark',
            'rs1.date_index as departure_date_index',
            'rs2.date_index as arrival_date_index',
            'rs1.departure_time as departure_time',
            'rs2.arrival_time AS arrival_time'
            )
        ->from('route_stations as rs1')
        ->join('routes as r', 'rs1.route_id', '=', 'r.id')
        ->join('route_stations as rs2', 'rs1.route_id', '=', 'rs2.route_id')
        ->where('rs1.station_name', $stationA)
        ->where('rs2.station_name', $stationB)
        ->whereColumn('rs1.km', '<', 'rs2.km')
        ->get();

        $returnRoutes = [];
        if ($ticketType === 'round-trip') {
            $returnRoutes = RouteStation::select(
                'rs1.route_id',
                'r.train_mark',
                'rs1.date_index as departure_date_index',
                'rs2.date_index as arrival_date_index',
                'rs1.departure_time as departure_time',
                'rs2.arrival_time AS arrival_time'
                )
            ->from('route_stations as rs1')
            ->join('routes as r', 'rs1.route_id', '=', 'r.id')
            ->join('route_stations as rs2', 'rs1.route_id', '=', 'rs2.route_id')
            ->where('rs1.station_name', $stationB)
            ->where('rs2.station_name', $stationA)
            ->whereColumn('rs1.km', '<', 'rs2.km')
            ->get();
        }
        return view('pages.reservation', compact('routes', 'returnRoutes'));
    }
}
