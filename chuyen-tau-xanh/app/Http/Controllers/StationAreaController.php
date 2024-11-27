<?php

namespace App\Http\Controllers;

use App\Models\StationArea;
use Illuminate\Http\Request;

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
}
