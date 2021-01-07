<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\EventList;
use App\Models\EventUser;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * INDEX AND SEARCH EVENT
     *
     * @return void
     */
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


    /**
     * VIEW CREATE FORM
     *
     * @return void
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * EVENT CREATE
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {

        $event = new Event();

        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;


        // IMAGE UPLOAD
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
     * GET VALUES FOR EDIT
     *
     * @param [type] $id
     * @return void
     */
    public function edit($id)
    {
        $user = auth()->user();

        $event = Event::findOrFail($id);

        if ($user->id != $event->user_id) {
            return redirect('/')->with('msg', 'Você não é dono do evento ' . $event->title);
        }

        return view('events.edit', ['event' => $event]);
    }

    /**
     * UPDATE EVENT
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $data = $request->all();

        // IMAGE UPLOADE
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now'));

            $requestImage->move(public_path('img/events'), $imageName);


            $data['image'] = $imageName;
        }

        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento atualizado com sucesso');
    }

    /**
     * DELETE
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso');
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

        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('events.dashboard', [
            'events' => $events,
            'eventsAsParticipant' => $eventsAsParticipant
        ]);
    }

    /**
     * SHOW EVENT BLADE
     *
     * @param [type] $id
     * @return void
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        $user = auth()->user();

        $hasUserJoined = false;

        if ($user) {
            $userEvents = $user->eventsAsParticipant->toArray();
            foreach ($userEvents as $userEvent) {
                if ($userEvent['id'] == $id) {
                    $hasUserJoined = true;
                }
            }
        }



        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view(
            'events.show',
            [
                'event' => $event,
                'eventOwner' => $eventOwner,
                'hasUserJoined' => $hasUserJoined
            ]
        );
    }


    /**
     * JOIN EVENT
     *
     * @param [type] $id
     * @return void
     */
    public function joinEvent($id)
    {
        $user = auth()->user();

        $event = Event::findOrFail($id);

        $user->eventsAsParticipant()->attach($id);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no ' . $event->title);
    }

    /**
     * LEAVE EVENT
     *
     * @param [type] $id
     * @return void
     */
    public function leaveEvent($id)
    {
        $user = auth()->user();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Você saiu com sucesso do evento: ' . $event->title);
    }
}
