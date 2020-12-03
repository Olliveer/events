@extends('layouts.main')

@section('title', 'Produto')

@section('content')

<p>Exibindo produtos</p>

@if($search != '')
<p>O user esta buscando por: {{ $search }}</p>
@endif

@endsection
