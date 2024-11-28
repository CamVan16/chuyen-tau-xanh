<?php
namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Validate dữ liệu từ form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        Mail::to('7radiante@gmail.com')->send(new ContactEmail($validated));

        return redirect()->back()->with('success', 'Thông tin liên hệ của bạn đã được gửi!');
    }
}
