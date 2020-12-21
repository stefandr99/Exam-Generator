@extends('layouts.app')

@section('content')
    <ul class="list-group">
        @foreach ($users as $user)
            <li class="list-group-item">{{ $user->name}} {{$user->role == 1 ? 'Administrator' : ($user->role == 2 ? 'Profesor' : 'Student')}}</li>
        @endforeach
    </ul>
@endsection
