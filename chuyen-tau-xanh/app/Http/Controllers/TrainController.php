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

        // Lấy train_id đầu tiên
        $trainRoute = TrainRoute::where('route_id', $id)->first();
        $trainId = $trainRoute ? $trainRoute->train_id : null;

        if (!$trainId) {
            return back()->withErrors('Không tìm thấy train_id nào cho route_id này.');
        }

        // Lấy các loại ghế dựa trên train_id
        $seatTypes = SeatType::where('train_id', $trainId)->with('seats')->get();

        // Kiểm tra $seatTypes có phải là một Collection
        if (!is_countable($seatTypes)) {
            return back()->withErrors('Lỗi khi lấy danh sách loại ghế.');
        }

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
