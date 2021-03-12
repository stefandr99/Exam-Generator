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
                Timp rămas: <span id="time">{{ $info->hours }}:{{ $info->minutes }}</span>
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

    </form>
@endsection

<script>
    window.onload = function () {
        var examTime = '{{$examTime}}',
            display = document.querySelector('#time');
        startTimer(examTime, display);
    };


    window.addEventListener('blur', sendAnswers);
    function sendAnswers() {
        document.getElementById("submitExam").click();
    }

</script>
