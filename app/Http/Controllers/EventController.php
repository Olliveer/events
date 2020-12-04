<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $array = [1, 2, 3, 4, 5];

        return view('welcome', [
            'array' => $array
        ]);
    }

    public function create(){
        return view('events.create');
    }
}
