@extends('layouts.app')

@section('title')
    <title>Program</title>
@endsection

@section('content')
    <div class="container my-4">
        <div class="row">

            @foreach($exams as $exam)
                <div class="col-sm-6">
                    <div class="card text-center">
                        <div class="card-header">
                            <h4>{{$exam->type}}</h4>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title">{{$exam->course_name}}</h2>
                            <h5 class="card-text"><b>Catedra</b>:
                                @foreach($teachers[$exam->exam_id] as $teacher)
                                    {{ $teacher->name }},
                                @endforeach
                            </h5>
                            <h5 class="card-text"><b>Durata</b>:
                                @if($exam->hours == 1)
                                    {{ $exam->hours }} oră și
                                @elseif($exam->hours > 1)
                                    {{ $exam->hours }} ore și
                                @endif
                                {{ $exam->minutes }} minute,
                                <b>Număr de exerciții</b>: {{ $exam->number_of_exercises }},
                                <br>
                                <b>Punctaj</b>: {{$exam->total_points}},
                                <b>Punctaj minim necesar</b>: {{$exam->minimum_points}}
                            </h5>
                            @if(new DateTime($exam->date) > $presentDate)
                                <button type="button" class="btn btn-primary" onclick="window.location='{{ route('generate_exam', $exam->exam_id) }}'" disabled>
                                    Începe
                                </button>
                            @else
                                <button type="button" class="btn btn-primary" onclick="window.location='{{ route('generate_exam', $exam->exam_id) }}'">
                                    Începe
                                </button>
                            @endif
                        </div>
                        <div class="card-footer text-muted">
                            <h4><b>Data:</b> {{ date_format(date_create($exam->date), 'd-m-Y') }} <b>Ora:</b> {{ date_format(date_create($exam->date), 'H:i') }}</h4>
                        </div>
                    </div>

                    <br>
                </div>
            @endforeach

        </div>

    </div>

@endsection
