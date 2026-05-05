<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function send()
    {
        Mail::raw('Halo Rio, ini email notifikasi dari sistem mebel kamu', function ($message) {
            $message->to('emailtujuan@gmail.com')
                    ->subject('Notifikasi Email');
        });

        return "Email berhasil dikirim!";
    }
}