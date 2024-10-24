<?php

namespace App\Http\Controllers;

use App\Models\StationArea;
use Illuminate\Http\Request;

class StationAreaController extends Controller
{
    // Lấy danh sách tất cả station areas
    public function index()
    {
        return StationArea::all();
    }

    // Tạo một station area mới
    public function store(Request $request)
    {
        $request->validate([
            'stationCode' => 'required|string',
            'stationName' => 'required|string',
            'km' => 'required|integer',
        ]);

        return StationArea::create($request->all());
    }

    // Lấy thông tin một station area
    public function show($id)
    {
        return StationArea::findOrFail($id);
    }

    // Cập nhật thông tin một station area
    public function update(Request $request, $id)
    {
        $stationArea = StationArea::findOrFail($id);
        $stationArea->update($request->all());

        return $stationArea;
    }

    // Xóa một station area
    public function destroy($id)
    {
        $stationArea = StationArea::findOrFail($id);
        $stationArea->delete();

        return response()->noContent();
    }
}
