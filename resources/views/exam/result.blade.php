@extends('layouts.app')

@section('content')
    <form>
        <div class="container my-4">
            <h1 id="partial-title">
                <b>
                    {{ $info->type }} {{ $info->course_name }}
                    <br>
                    {{ date_format(date_create($info->date), 'l, d-m-Y, H:i') }}
                </b>
            </h1>
            <h1 id="min-points">Puncte necesare pentru a promova: {{ $info->minimum_points }} puncte</h1>
            @if($info->obtained_points < $info->minimum_points)
                <h1 id="partial-failed"><b>Rezultat: {{ $info->obtained_points }} puncte</b></h1>
            @else
                <h1 id="partial-passed"><b>Rezultat: {{ $info->obtained_points }} puncte</b></h1>
            @endif
            <br>

            @for($index = 0; $index < count($info->exercises); $index++)
                <h2><b>Exercitiul {{ $index }} ({{$info->exercises[$index]['points']}} puncte)</b></h2>
                @foreach($info->exercises[$index]['exercise']["content"] as $field)
                    @if(str_starts_with($field, 'text'))
                        @include('examTemplates.text', ['text' => $info->exercises[$index]['exercise']['text'][intval($field[4])]])
                    @else
                        @switch($field)
                            @case("list")
                            @include('examTemplates.list', ['list' => $info->exercises[$index]['exercise']['list']])
                            @break
                            @case("options")
                            @include('correctedExamTemplates.correctedOptions', ['options' => $info->exercises[$index]['exercise']['options'],
                                                                    'number' => $index,
                                                                    'studentAnswers' => $info->student_answers,
                                                                    'results' => $info->results])
                            @break
                            @case("optionsAndTable")
                            @include('correctedExamTemplates.correctedOptionsAndTable', ['options' => $info->exercises[$index]['exercise']['options'],
                                                                            'table' => $info->exercises[$index]['exercise']['table'],
                                                                            'number' => $index,
                                                                            'studentAnswers' => $info->student_answers,
                                                                            'results' => $info->results])
                            @break
                            @case("table")
                            @include('examTemplates.table', ['table' => $info->exercises[$index]['exercise']['table']])
                        @endswitch
                    @endif
                @endforeach
                <br>
            @endfor
        </div>


    </form>
@endsection
