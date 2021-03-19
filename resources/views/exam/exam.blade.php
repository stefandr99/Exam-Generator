@extends('layouts.app')

@section('title')
    <title>{{ $info->type }}</title>
@endsection

@section('content')
    <form>
    <div class="container my-4">
        <h1 class="text-center">
            <b>
                {{ $info->type }} {{ $info->course_name }}
                <br>
                {{ date_format(date_create($info->starts_at), 'l, d-m-Y, H:i') }}
                <br>
                <div class="remaining-exam-time">
                    Timp rămas: <p id="hourss"></p>:<p id="mins"></p>:<p id="secs"></p>
                </div>
            </b>
        </h1>
        <br>
        @for($index = 0; $index < count($exercises); $index++)
            <h2><b>Exercițiul {{ $index + 1 }} ({{$exercises[$index]['points']}} puncte)</b></h2>
            @foreach($exercises[$index]['exercise']["content"] as $field)
                @if(str_starts_with($field, 'text'))
                    @include('examTemplates.text', ['text' => $exercises[$index]['exercise']['text'][intval($field[4])]])
                @else
                    @switch($field)
                        @case("list")
                            @include('examTemplates.list', ['list' => $exercises[$index]['exercise']['list']])
                            @break
                        @case("options")
                            @include('examTemplates.options', ['options' => $exercises[$index]['exercise']['options'],
                                                                    'number' => $index])
                            @break
                        @case("optionsAndTable")
                            @include('examTemplates.optionsAndTable', ['options' => $exercises[$index]['exercise']['options'],
                                                                            'table' => $exercises[$index]['exercise']['table'],
                                                                            'number' => $index])
                            @break
                        @case("table")
                            @include('examTemplates.table', ['table' => $exercises[$index]['exercise']['table']])
                    @endswitch
                @endif
            @endforeach
            <br>
        @endfor
        <div class="r_relationship">
            <button id="submitExam" type="button" class="btn btn-primary r_relationship" onclick="checkTest('{{ $info->number_of_exercises }}', '{{ json_encode($optionsNumber) }}', '{{ $examId }}')">
                Trimite răspunsurile
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
                        Acesta este un avertisment, daca se repeta vom fi nevoiti sa te sanctionam
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
        var examTime = '{{$examTime}}';
        startTimer(examTime);
    };


    window.addEventListener('blur', function () {
        penalization('{{json_encode($penalization)}}');
    });
</script>
