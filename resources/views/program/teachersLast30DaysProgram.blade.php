@extends('layouts.app')

@section('title')
    <title>Examenele din ultimele 30 zile</title>
@endsection

@section('content')
    <div class="container my-4">
        @if(count($exams) == 0)
            <br>
            <br>
            <div class="text-center">
                <h1>Nu ati avut niciun examen in ultimele 30 de zile.</h1>
                <br>
                <button type="button" class="btn btn-info btn-lg"  onclick="window.location='{{ route('prepare_exam') }}'">
                    Pregatiți un examen
                </button>
            </div>
        @else
            <div class="row">
                @foreach($exams as $exam)
                    <div class="col-sm-5 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <h3>
                                    {{$exam->course_name}}
                                    <span class="badge rounded-pill bg-info text-white float-right">
                                        {{$exam->type}}
                                    </span>
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="float-left px-4">
                                    <i class="fas fa-calendar-alt fa-5x" data-toggle="collapse" href="#examDateCollapse-{{$exam->exam_id}}" style="cursor: pointer"></i>
                                </div>

                                <div class="float-right px-4">
                                    <i class="fas fa-stopwatch fa-5x" data-toggle="collapse" href="#examTimeCollapse-{{$exam->exam_id}}" style="cursor: pointer"></i>
                                </div>

                                <div id="acc-{{$exam->exam_id}}">
                                    <div class="text-center my-3">
                                        <div class="collapse text-center show" id="examDateCollapse-{{$exam->exam_id}}" data-parent="#acc-{{$exam->exam_id}}">
                                            <div class="card rounded border text-center">
                                                <div class="card-body program-info-text-m" style="padding: 0;">
                                                    <i class="far fa-calendar-alt"> {{ date_format(date_create($exam->starts_at), 'd-m-Y') }}</i>
                                                    <br>
                                                    <i class="far fa-clock"> {{ date_format(date_create($exam->starts_at), 'H:i') }}</i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center my-3">
                                        <div class="collapse text-center" id="examTimeCollapse-{{$exam->exam_id}}" data-parent="#acc-{{$exam->exam_id}}">
                                            <div class="card rounded border text-center">
                                                <div class="card-body program-time-text" style="padding: 0;">
                                                    <i class="fas fa-stopwatch"></i>
                                                    @if($exam->hours == 1)
                                                        {{ $exam->hours }} ora
                                                    @elseif($exam->hours > 1)
                                                        {{ $exam->hours }} ore
                                                    @endif
                                                    {{ $exam->minutes }} minute
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body" style="margin-top: -30px;">
                                <div class="row">
                                    <div class="m-auto">
                                        <div class="card rounded border text-center" >
                                            <div class="card-body">
                                                <div class="row">
                                                    <table>
                                                        <tr>
                                                            <td class="program-numbers" id="program-nr-exercises">{{ $exam->number_of_exercises }}</td>
                                                            <td class="program-info-text-l" style="width: 50%;">Exerciții</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mx-auto">
                                        <div class="card rounded border text-center" >
                                            <div class="card-body">
                                                <div class="row">
                                                    <table>
                                                        <tr>
                                                            <td class="program-numbers" id="program-max-points">{{$exam->total_points}}</td>
                                                            <td class="program-info-text-m" style="width: 50%;">Punctaj maxim</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <table>
                                                        <tr>
                                                            <td class="program-numbers" id="program-min-points">{{$exam->minimum_points}}</td>
                                                            <td class="program-info-text-m" style="width: 50%;">Punctaj minim</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-center">
                                <button type="button" class="btn btn-primary" onclick="window.location='{{ route('show_exam_stats', $exam->exam_id) }}'">
                                    Vizualizare statistici
                                </button>

                            </div>
                        </div>

                        <br>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
