@extends('layouts.main')

@section('title', 'Events Laravel')

@section('content')
<h1>Título</h1>
@if(10>5)
    <p>true</p>
@endif

@for($i = 0; $i < count($array); $i++)
    <p>{{ $array[$i] }}</p>
@endfor

@endsection
