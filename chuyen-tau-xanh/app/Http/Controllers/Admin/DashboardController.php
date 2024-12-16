<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $totalCustomers = DB::table('customers')->count();
        $totalBookings = DB::table('bookings')->count();
        $totalTickets = DB::table('tickets')->count();
        $totalRefunds = DB::table('refunds')->count();

        $chartCustomers = new Chart;
        $chartBookings = new Chart;
        $chartTickets = new Chart;
        $chartRefunds = new Chart;
        $chartRevenue = new Chart;

        $labels = [];
        $customersData = [];
        $bookingsData = [];
        $ticketsData = [];
        $refundsData = [];
        $revenueData = [];


        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $todayRevenue = DB::table('bookings')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price') - DB::table('refunds')
            ->whereDate('created_at', Carbon::today())
            ->sum('refund_amount');

        $totalRevenue30Days = DB::table('bookings')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price') - DB::table('refunds')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('refund_amount');

        for ($i = 0; $startDate->lte($endDate); $i++) {
            $dayStart = $startDate->copy();
            $dayEnd = $startDate->copy()->endOfDay();

            $labels[] = $dayStart->format('d M');

            $customersData[] = DB::table('customers')
                ->whereDate('created_at', '=', $dayStart->toDateString())
                ->count();

            $bookingsData[] = DB::table('bookings')
                ->whereDate('created_at', '=', $dayStart->toDateString())
                ->count();

            $ticketsData[] = DB::table('tickets')
                ->whereDate('created_at', '=', $dayStart->toDateString())
                ->count();

            $refundsData[] = DB::table('refunds')
                ->whereDate('created_at', '=', $dayStart->toDateString())
                ->count();

            $revenueData[] = DB::table('bookings')
                ->whereDate('created_at', '=', $dayStart->toDateString())
                ->sum('total_price') - DB::table('refunds')
                ->whereDate('created_at', '=', $dayStart->toDateString())
                ->sum('refund_amount');


            $startDate->addDay();
        }

        $chartCustomers->labels($labels);
        $chartCustomers->dataset('Tăng trưởng khách hàng', 'line', $customersData)
            ->color('rgb(255, 99, 132)');


        $chartBookings->labels($labels);
        $chartBookings->dataset('Tăng trưởng bookings', 'line', $bookingsData)
            ->color('rgb(54, 162, 235)');


        $chartTickets->labels($labels);
        $chartTickets->dataset('Tăng trưởng vé', 'line', $ticketsData)
            ->color('rgb(75, 192, 192)');

        $chartRefunds->labels($labels);
        $chartRefunds->dataset('Tăng trưởng hoàn tiền', 'line', $refundsData)
            ->color('rgb(153, 102, 255)');

        $chartRevenue->labels($labels);
        $chartRevenue->dataset('Doanh thu', 'line', $revenueData)
            ->color('rgb(255, 159, 64)');

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalBookings',
            'totalTickets',
            'totalRefunds',
            'todayRevenue',
            'totalRevenue30Days',
            'chartCustomers',
            'chartBookings',
            'chartTickets',
            'chartRefunds',
            'chartRevenue'
        ));
    }
}
