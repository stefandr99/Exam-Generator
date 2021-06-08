@extends('layouts.app')

@section('title')
    <title>Program</title>
@endsection

@section('content')

    <div class="container">
        @if(count($exams) == 0)
            <br>
            <br>
            <div class="text-center">
                <h1>Nu aveți programat niciun examen momentan.</h1>
                <br>
                <button type="button" class="btn btn-info btn-lg"  onclick="window.location='{{ route('home') }}'">
                    Înapoi la pagina principală
                </button>
            </div>
        @else
            <h1 class="text-center mt-3 mb-5">Examenele mele</h1>
            <div class="row">
                @foreach($exams as $exam)
                    <div class="col-lg-6">
                        <div class="card student-program-card card-margin">
                            <div class="card-header no-border">
                                <h5 class="card-title">{{$exam->type}}</h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="st-program">
                                    <div class="st-program-title-wrapper">
                                        @php
                                            $number = rand(1, 4);
                                            switch ($number) {
                                                case 1:
                                                    echo("<div class='st-program-date-primary'>");
                                                    break;
                                                case 2:
                                                    echo("<div class='st-program-date-success'>");
                                                    break;
                                                case 3:
                                                    echo("<div class='st-program-date-warning'>");
                                                    break;
                                                case 4:
                                                    echo("<div class='st-program-date-danger'>");
                                                    break;
                                            }
                                        @endphp
                                        <span class="st-program-date-day">{{ date_format(date_create($exam->starts_at), 'd') }}</span>
                                        <span class="st-program-date-month">{{ date_format(date_create($exam->starts_at), 'M') }}</span>
                                        <span class="st-program-date-month">{{ date_format(date_create($exam->starts_at), 'y') }}</span>
                                    </div>
                                    <div class="st-program-meeting-info">
                                        <span class="st-program-pro-title">{{$exam->course_name}}</span>
                                        <span class="st-program-meeting-time">De la {{date_format(date_create($exam->starts_at), 'H:i')}} la {{date_format(date_create($exam->ends_at), 'H:i')}}</span>
                                    </div>
                                </div>
                                <div class="row my-2">
                                    <div class="media-body text-center st-program-meeting-points">
                                        <h3>{{ $exam->number_of_exercises }}</h3>
                                        <span>{{ $exam->number_of_exercises == 1 ? "Exercitiu" : "Exercitii"}}</span>
                                    </div>
                                    <div class="media-body text-center">
                                        <h3>{{$exam->total_points}}</h3>
                                        <span>Punctaj maxim</span>
                                    </div>
                                    <div class="media-body text-center">
                                        <h3>{{$exam->minimum_points}}</h3>
                                        <span>Punctaj minim</span>
                                    </div>
                                </div>
                                <div class="st-program-meeting-action">
                                    @if(new DateTime($exam->starts_at) <= $presentDate)
                                        <button class="custom-btn-start-exam btn-start-exam" onclick="window.location='{{ route('generate_exam', $exam->exam_id) }}'">Începe</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endforeach
    </div>
    @endif
    </div>


@endsection
