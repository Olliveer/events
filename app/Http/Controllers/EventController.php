<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {

        $search = request('search');

        if ($search) {

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

            if ($requestImage == "null") {
                $event->image = "null";
            } else {
                $event->image = $imageName;
            }
        }

        $user = auth()->user();
        $event->user_id = $user->id;


        if ($event->title == "" || $event->city == "" || $event->private == "" || $event->description == "") {
            return redirect('/events/create')->with('msg', 'Favor preencher os campos');
        }

        if (!$event->save()) {
            return redirect('/events/create')->with('msg', 'Erro ao enviar formulário');
        }

        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }

    /**
     * DASHBOARD 
     *
     * @return void
     */
    public function dashboard()
    {
        $user = auth()->user();

        $events = $user->events;

        return view('events.dashboard', ['events' => $events]);
    }

    /**
     * Show event blade
     *
     * @param [type] $id
     * @return void
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner]);
    }

    /**
     * Delete
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg','Evento excluído com sucesso');
    }
}
