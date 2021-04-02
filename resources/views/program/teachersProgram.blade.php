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
                <button type="button" class="btn btn-info btn-lg"  onclick="window.location='{{ route('prepare_exam') }}'">
                    Pregatiți un examen
                </button>
            </div>
        @else
            <div class="row">
                @foreach($exams as $exam)
                <div class="col-sm-6">
                    <div class="card text-center">
                        <div class="card-header">
                            <h4>
                                {{$exam->type}}
                                <br>
                                @if(new DateTime($exam->ends_at) > $presentDate && new DateTime($exam->starts_at) < $presentDate)
                                    <span class="badge rounded-pill bg-info text-dark">
                                        In desfasurare...
                                    </span>
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title">{{$exam->course_name}}</h2>
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
                                <button type="button" class="btn btn-primary" onclick="window.location='{{ route('modify_exam', $exam->exam_id) }}'">
                                    Modifică
                                </button>
                            @endif
                            @if(new DateTime($exam->ends_at) > $presentDate && new DateTime($exam->starts_at) < $presentDate)
                                <span class="btn btn-success">
                                    Detalii
                                </span>
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
