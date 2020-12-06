<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){

        $events =  Event::all();

        return view('welcome', [
            'events' => $events
        ]);
    }

    public function create(){
        return view('events.create');
    }

    public function store(Request $request){

        $event = new Event();

        $event->title = $request->title;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;

        if ($event->title == "" || $event->city == "" || $event->private == "" || $event->description == "") {
            return redirect('/events/create')->with('msg', 'Favor preencher os campos');
        }

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }

    public function login(){
        return view('events.login');
    }

    public function register(){
        return view('events.register');
    }
}
