<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class CheckTicketController extends Controller
{
    // Hiển thị form kiểm tra vé
    public function showForm()
    {
        return view('pages.check-ticket');
    }

    // Xử lý kiểm tra vé
    public function checkTicket(Request $request)
    {
        // Validate dữ liệu từ form
        $request->validate([
            'ticket_id' => 'required|string|max:8',
            'train_mark' => 'nullable|string|max:10',
            'station_start' => 'nullable|string|max:25',
            'station_end' => 'nullable|string|max:25',
            'day_start' => 'nullable|date',
            'citizen_id' => 'nullable|digits:12',
        ]);

        // Truy vấn vé theo các thông tin được nhập
        $ticket = Ticket::with('schedule', 'customer')
            ->where('id', $request->ticket_id)
            ->when($request->train_mark, function ($query) use ($request) {
                $query->whereHas('schedule', function ($q) use ($request) {
                    $q->where('train_mark', $request->train_mark);
                });
            })
            ->when($request->station_start, function ($query) use ($request) {
                $query->whereHas('schedule', function ($q) use ($request) {
                    $q->where('station_start', $request->station_start);
                });
            })
            ->when($request->station_end, function ($query) use ($request) {
                $query->whereHas('schedule', function ($q) use ($request) {
                    $q->where('station_end', $request->station_end);
                });
            })
            ->when($request->day_start, function ($query) use ($request) {
                $query->whereHas('schedule', function ($q) use ($request) {
                    $q->whereDate('day_start', $request->day_start);
                });
            })
            ->when($request->citizen_id, function ($query) use ($request) {
                $query->whereHas('customer', function ($q) use ($request) {
                    $q->where('citizen_id', $request->citizen_id);
                });
            })
            ->first();

        // Nếu không tìm thấy vé
        if (!$ticket) {
            return back()->withErrors(['not_found' => 'Không tìm thấy vé với thông tin đã cung cấp.']);
        }

        // Trả về kết quả kiểm tra
        return view('pages.check-ticket-result', compact('ticket'));
    }
}
