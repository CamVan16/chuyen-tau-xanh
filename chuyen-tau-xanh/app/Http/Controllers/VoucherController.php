<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        return Voucher::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'min_price_order' => 'required|double',
            'percent' => 'required|integer',
            'max_price_discount' => 'required|double',
            'type' => 'required|integer',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'quantity' => 'required|quantity',
            'description' => 'string'
        ]);

        return Voucher::create($request->all());
    }

    public function show($id)
    {
        return Voucher::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $Voucher = Voucher::findOrFail($id);
        $Voucher->update($request->all());

        return $Voucher;
    }

    public function destroy($id)
    {
        $Voucher = Voucher::findOrFail($id);
        $Voucher->delete();

        return response()->noContent();
    }

    public function showVouchers()
    {
        // return view('vouchers');
    }
}
