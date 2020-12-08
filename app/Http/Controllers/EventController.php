<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {

        $search = request('search');

        if($search) {

            $events = Event::where([
                ['title', 'like', '%' . $search . '%']
            ])->get();

        } else {
            $events = Event::all();
        }

        return view('welcome', [
            'events' => $events,
            'search' => $search
        ]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {

        $event = new Event();

        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;
        

        // IMAGE UPLOADE
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now'));

            $requestImage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;
        }


        if ($event->title == "" || $event->city == "" || $event->private == "" || $event->description == "") {
            return redirect('/events/create')->with('msg', 'Favor preencher os campos');
        }

        if (!$event->save()) {
            return redirect('/events/create')->with('msg', 'Erro ao enviar formulário');
        }

        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        return view('events.show', ['event' => $event]);
    }

    public function login()
    {
        return view('events.login');
    }

    public function register()
    {
        return view('events.register');
    }
}
