<?php

namespace App\Http\Controllers;

class ServerController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }

    public function show(int $id)
    {
        return view('pages.dashboard');
    }
}
