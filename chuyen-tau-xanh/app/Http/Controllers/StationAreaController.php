<?php

namespace App\Http\Controllers;

use App\Models\StationArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function searchTrains(Request $request)
    {
        $request->validate([
            'gaDi' => 'required|string',
            'gaDen' => 'required|string',
            'ngay' => 'required|date',
        ]);

        $gaDi = $request->input('gaDi');
        $gaDen = $request->input('gaDen');
        $ngay = $request->input('ngay');

        $trains = DB::table('route_stations as rs1')
            ->join('route_stations as rs2', 'rs1.route_id', '=', 'rs2.route_id')
            ->join('routes', 'rs1.route_id', '=', 'routes.id')
            ->where('rs1.station_code', '=', $gaDi)
            ->where('rs2.station_code', '=', $gaDen)
            ->where('rs1.date_index', '<=', 'rs2.date_index')
            ->select(
                'routes.train_mark',
                'rs1.station_name as ga_di',
                'rs2.station_name as ga_den',
                'rs1.departure_time as gio_di',
                'rs2.arrival_time as gio_den',
                DB::raw("TIMEDIFF(rs2.arrival_time, rs1.departure_time) as thoi_gian_hanh_trinh")
            )
            ->get();

        return view('pages.trains', [
            'trains' => $trains,
            'gaDi' => $gaDi,
            'gaDen' => $gaDen,
            'ngay' => $ngay,
        ]);
    }
}
