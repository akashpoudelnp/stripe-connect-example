<?php

namespace App\Http\Controllers;

use App\Models\Client;

class FrontController extends Controller
{
    public function index()
    {
        $client = Client::all();

        return view('front.index', ['clients' => $client]);
    }
}
