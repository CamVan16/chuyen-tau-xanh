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
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'min_price_order' => 'required|numeric',
            'percent' => 'required|integer|min:1|max:100',
            'max_price_discount' => 'required|numeric',
            'type' => 'required|integer|in:0,1,2',
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after:from_date',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        return Voucher::create($request->all());
    }

    public function show($id)
    {
        $voucher = Voucher::findOrFail($id);
        return response()->json($voucher);
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
        $vouchers = Voucher::where('from_date', '<=', now())
            ->where('to_date', '>=', now())
            ->get();

        return view('pages.voucher', compact('vouchers'));
    }
}
