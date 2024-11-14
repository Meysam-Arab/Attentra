<!-- View stored in resources/views/greeting.blade.php -->
@extends('layouts.app')
@section('content')
    <p>This is my body content.</p>
	 <h1>Hello, {{ $name }}</h1>
	 <p>The current UNIX timestamp is {{ time() }}.</p>
@endsection
