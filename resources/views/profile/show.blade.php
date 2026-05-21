@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<div class="container" style="padding: 100px 0;">
    <h1>User Profile</h1>
    <p>User: {{ Auth::user()->name }}</p>
    <p>Email: {{ Auth::user()->email }}</p>
    <p>Phone: {{ Auth::user()->phone }}</p>
</div>
@endsection
