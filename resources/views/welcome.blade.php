@extends('layouts.main')

@section('title', 'Events Laravel')

@section('content')

    <div id="search-container" class="col-md-12">
        <h1>Busque um evento</h1>
        <form>
            <input type="text" id="search" name="search" class="form-control" placeholder="Procurar:" />
        </form>
    </div>

    <div id="events-container" class="col-md-12">
        <h1>Próximos eventos</h1>
        <p class="subtitle">Veja os eventos dos próximos dias</p>
        <div id="cards-container" class="row">
            @foreach ($events as $event)
                <div class="col-md-3">
                    <img src="/img/placeholder.svg" alt="{{ $event->title }}">
                    <div class="card-body">
                        <p class="card-date">06/12/2020</p>
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-participants">x participantes</p>
                        <a href="#" class="btn btn-primary">Saber mais</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
