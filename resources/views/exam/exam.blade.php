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
        <br>
        @for($index = 0; $index < count($exercises); $index++)
            <h2><b>Exercitiul {{ $index }} ({{$exercises[$index]['points']}} puncte)</b></h2>
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
            <button type="button" class="btn btn-primary r_relationship" onclick="checkTest('{{ $info->number_of_exercises }}', '{{ json_encode($optionsNumber) }}', '{{ $examId }}')">
                Verifica partialul
            </button>
        </div>
    </div>


    </form>
@endsection
