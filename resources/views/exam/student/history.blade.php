@extends('layouts.app')

@section('title')
    <title>Istoric</title>
@endsection

@section('content')

    <div class="container mt-3 mb-3">
        <h1 class="text-center">Istoricul meu</h1>
        <div class="row">
            @foreach($exams as $exam)
                <div class="col-md-4 mt-3">
                    <div class="card history-cards p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                                @if($exam->course_name == 'Baze de date')
                                    <div class="icon"><img src="http://localhost/examgenerator/img/database.png"></div>
                                @else
                                    <div class="icon"><img src="http://localhost/examgenerator/img/algorithms.png"></div>
                                @endif
                                <div class="ms-2 c-details ml-1">
                                    <h6 class="mb-0">{{$exam->type}}</h6> <span>{{date_format(date_create($exam->starts_at), 'd-m-Y')}}</span>
                                </div>
                            </div>
                            <div class="view-subject-item">
                                <a href="{{ route("show_exam_result", ['examId' => $exam->exam_id, 'userId' => $user_id]) }}" class="view-subject-link"><i class="far fa-file-alt"></i></a>
                            </div>
                        </div>
                        <div class="mt-5">
                            <h3 class="heading">
                                {{$exam->course_name}}
                                <br>
                                Anul {{$exam->year == 1 ? "I" : ($exam->year == 1 ? "II" : "III")}} Semestrul {{$exam->semester == 1 ? "I" : "II"}}
                            </h3>
                            <div class="mt-5">
                                @if($exam->obtained_points >= $exam->minimum_points)
                                    <div class="progress history passed-exam-bar">
                                        <div class="progress-bar" role="progressbar" style="width: {{($exam->obtained_points * 100) / $exam->total_points}}%" aria-valuenow="{{$exam->obtained_points}}" aria-valuemin="0" aria-valuemax="{{$exam->total_points}}">{{round(($exam->obtained_points * 100) / $exam->total_points, 1)}}%</div>
                                    </div>
                                @else
                                    <div class="progress history failed-exam-bar">
                                        @if($exam->obtained_points > 0)
                                            <div class="progress-bar" role="progressbar" style="width: {{($exam->obtained_points * 100) / $exam->total_points}}%" aria-valuenow="{{$exam->obtained_points}}" aria-valuemin="0" aria-valuemax="{{$exam->total_points}}">{{$exam->obtained_points}} / {{$exam->total_points}}</div>
                                        @else
                                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="{{$exam->total_points}}"></div>
                                        @endif
                                    </div>
                                @endif
                                <div class="mt-3"> <span class="text1">{{$exam->obtained_points}} puncte <span class="text2">din {{$exam->total_points}} puncte</span></span> </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
