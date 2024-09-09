@extends('office::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('office.name') !!}</p>
@endsection
