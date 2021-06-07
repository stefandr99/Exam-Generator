@extends('layouts.app')

@section('title')
    <title>{{ $info->type }}</title>
@endsection

@section('content')

    <form action="{{route('correct_exam')}}" method="POST">
        @csrf

        <div class="container my-4">
            <h1 class="text-center">
                <b>
                    {{ $info->type }} {{ $info->course_name }}
                    <br>
                    {{ date_format(date_create($info->starts_at), 'l, d-m-Y, H:i') }}
                </b>
            </h1>
            <div class="col-6 mx-auto sticky-top">
                <div class="card card-body timer-card text-center">
                    <div class="countdown">
                        <div class="bloc-time hours" data-init-value="{{$examTime[0]}}">
                            <span class="count-title">Hours</span>

                            <div class="figure hours hours-1">
                                <span class="top">{{floor($examTime[0] / 10)}}</span>
                                <span class="top-back">
                                    <span>{{floor($examTime[0] / 10)}}</span>
                                </span>
                                <span class="bottom">{{floor($info->hours / 10)}}</span>
                                <span class="bottom-back">
                                    <span>{{floor($examTime[0] / 10)}}</span>
                                </span>
                            </div>

                            <div class="figure hours hours-2">
                                <span class="top">{{$examTime[0] % 10}}</span>
                                <span class="top-back">
                                    <span>{{$examTime[0] % 10}}</span>
                                </span>
                                <span class="bottom">{{$examTime[0] % 10}}</span>
                                <span class="bottom-back">
                                    <span>{{$examTime[0] % 10}}</span>
                                </span>
                            </div>
                        </div>

                        <div class="bloc-time min" data-init-value="{{$examTime[1]}}">
                            <span class="count-title">Minutes</span>

                            <div class="figure min min-1">
                                <span class="top">{{floor($examTime[1] / 10)}}</span>
                                <span class="top-back">
                                    <span>{{floor($examTime[1] / 10)}}</span>
                                </span>
                                <span class="bottom">{{floor($examTime[1] / 10)}}</span>
                                <span class="bottom-back">
                                    <span>{{floor($examTime[1] / 10)}}</span>
                                </span>
                            </div>

                            <div class="figure min min-2">
                                <span class="top">{{$examTime[1] % 10}}</span>
                                <span class="top-back">
                                    <span>{{$examTime[1] % 10}}</span>
                                </span>
                                <span class="bottom">{{$examTime[1] % 10}}</span>
                                <span class="bottom-back">
                                    <span>{{$examTime[1] % 10}}</span>
                                </span>
                            </div>
                        </div>

                        <div class="bloc-time sec" data-init-value="{{$examTime[2]}}">
                            <span class="count-title">Seconds</span>

                            <div class="figure sec sec-1">
                                <span class="top">{{floor($examTime[2] / 10)}}</span>
                                <span class="top-back">
                                    <span>{{floor($examTime[2] / 10)}}</span>
                                </span>
                                <span class="bottom">{{floor($examTime[2] / 10)}}</span>
                                <span class="bottom-back">
                                    <span>{{floor($examTime[2] / 10)}}</span>
                                </span>
                            </div>

                            <div class="figure sec sec-2">
                                <span class="top">{{$examTime[2] % 10}}</span>
                                <span class="top-back">
                                    <span>{{$examTime[2] % 10}}</span>
                                </span>
                                <span class="bottom">{{$examTime[2] % 10}}</span>
                                <span class="bottom-back">
                                    <span>{{$examTime[2] % 10}}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            @for($index = 0; $index < $exercises['counter']; $index++)
                <div class="card-container">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-count">{{ $index + 1 }}</div>
                            @foreach($exercises['exercises'][$index]["content"] as $field)
                                @if(str_starts_with($field, 'text'))
                                    @include('examTemplates.text', ['text' => $exercises['exercises'][$index]['text'][intval($field[4])]])
                                @else
                                    @switch($field)
                                        @case("list")
                                        @include('examTemplates.list', ['list' => $exercises['exercises'][$index]['list']])
                                        @break
                                        @case("options")
                                        @include('examTemplates.options', ['options' => $exercises['exercises'][$index]['options'],
                                                                                'number' => $index])
                                        @break
                                        @case("optionsAndTable")
                                        @include('examTemplates.optionsAndTable', ['options' => $exercises['exercises'][$index]['options'],
                                                                                        'table' => $exercises['exercises'][$index]['table'],
                                                                                        'number' => $index])
                                        @break
                                        @case("table")
                                        @include('examTemplates.table', ['table' => $exercises['exercises'][$index]['table']])
                                    @endswitch
                                @endif
                            @endforeach
                        </div>
                        <div class="card-footer">
                            {{$exercises['exercises'][$index]['points']}} puncte
                        </div>
                    </div>
                </div>
                <br>
            @endfor
            <input class="form-check-input" id="is_forced" name="forced" type="hidden" value="0">

            <div class="text-center">
                <button id="submitExam" class="submit-exam-button" type="submit" style="outline: none;">
                    <div class="button_content">
                        <p class="button_text">Trimite răspunsurile</p>
                    </div>
                </button>
            </div>
        </div>

        <div class="modal fade" id="fraudTheExam" tabindex="-1" role="dialog" aria-labelledby="fraudTheExamCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fraudTheExamLongTitle">Tentativa de frauda</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Nu incerca sa copiezi, concentreaza-te pe subiectul tau.
                        <br>
                        Acesta este un avertisment, daca se repeta vom fi nevoiti sa te sanctionam.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Am inteles</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

<script>
    window.onload = function () {
        Countdown.init();
    };

    window.addEventListener('blur', function () {
        Countdown.makePenalization('{{json_encode($penalization)}}');
    });
</script>
