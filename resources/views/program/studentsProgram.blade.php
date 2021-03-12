@extends('layouts.app')

@section('title')
    <title>Program</title>
@endsection

@section('content')
    <div class="container my-4">
        @if(count($exams) == 0)
            <br>
            <br>
            <div class="text-center">
                <h1>Nu aveți programat niciun examen momentan.</h1>
                <br>
                <button type="button" class="btn btn-info btn-lg"  onclick="window.location='{{ route('home') }}'">
                    Inapoi la pagina principala
                </button>
            </div>
        @else
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
                                        @if($teacher->name != $teachers[$exam->exam_id][count($teachers[$exam->exam_id]) - 1]->name)
                                            {{ $teacher->name }},
                                        @else
                                            {{ $teacher->name }}
                                        @endif
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
                                @if(new DateTime($exam->starts_at) > $presentDate)
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
                                <h4><b>Data:</b> {{ date_format(date_create($exam->starts_at), 'd-m-Y') }} <b>Ora:</b> {{ date_format(date_create($exam->starts_at), 'H:i') }}</h4>
                            </div>
                        </div>

                        <br>
                    </div>
                @endforeach

            </div>
        @endif
    </div>

@endsection
