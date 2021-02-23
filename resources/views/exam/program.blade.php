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
                        {{ $exam->minutes }} minute,
                        <b>Număr de exerciții</b>: {{ $exam->number_of_exercises }},
                        <b>Punctaj</b>: {{$exam->total_points}}
                    </p>
                    <a href="{{ route('generate_exam', $exam->exam_id) }}" class="btn btn-primary">Începe</a>
                </div>
                <div class="card-footer text-muted">
                    <h4><b>Data:</b> {{ date_format(date_create($exam->date), 'd-m-Y') }} <b>Ora:</b> {{ date_format(date_create($exam->date), 'H:i') }}</h4>
                </div>
            </div>
            <br>
        @endforeach
    </div>

@endsection
