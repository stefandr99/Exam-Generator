@extends('layouts.app')

@section('content')
    <div class="container my-4">
        @foreach($exams as $exam)
            <div class="card text-center">
                <div class="card-header">
                    <h4>{{$exam->type}}</h4>
                </div>
                <div class="card-body">
                    <h2 class="card-title">{{$exam->course_name}}</h2>
                    <h5 class="card-text"><b>Profesor</b>: {{ $exam->teacher_name }}</h5>
                    <p class="card-text"><b>Durata</b>:
                        @if($exam->hours == 1)
                            {{ $exam->hours }} oră și
                        @elseif($exam->hours > 1)
                            {{ $exam->hours }} ore și
                        @endif
                        {{ $exam->minutes }} minute.
                        <b>Număr de exerciții</b>: {{ $exam->number_of_exercises }}.
                        <b>Punctaj</b>: {{$exam->total_points}}.
                    </p>
                    <a href="#" class="btn btn-primary disabled">Începe</a>
                </div>
                <div class="card-footer text-muted">
                    <h4>Data: {{ $exam->date }}</h4>
                </div>
            </div>
            <br>
        @endforeach
    </div>

@endsection
