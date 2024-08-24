@extends('case::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('case.name') !!}</p>
@endsection
