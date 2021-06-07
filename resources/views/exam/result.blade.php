@extends('layouts.app')

@section('title')
    <title>Rezultat</title>
@endsection

@section('content')
    <form>
        <div class="container my-4">
            <h1 class="text-center">
                <b>
                    {{ $info->type }} {{ $info->course_name }}
                    <br>
                    {{ date_format(date_create($info->starts_at), 'l, d-m-Y, H:i') }}
                </b>
            </h1>
            <h1 id="min-points">Puncte necesare pentru a promova: {{ $info->minimum_points }} puncte</h1>
            @if($info->obtained_points < $info->minimum_points)
                <h1 id="partial-failed"><b>Rezultat: {{ $info->obtained_points }} puncte</b></h1>
            @else
                <h1 id="partial-passed"><b>Rezultat: {{ $info->obtained_points }} puncte</b></h1>
            @endif
            <br>

            @for($index = 0; $index < $info->number_of_exercises; $index++)
                <div class="card-container">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-count">{{ $index + 1 }}</div>
                            @foreach($info->exercises['exercises'][$index]["content"] as $field)
                                @if(str_starts_with($field, 'text'))
                                    @include('examTemplates.text', ['text' => $info->exercises['exercises'][$index]['text'][intval($field[4])]])
                                @else
                                    @switch($field)
                                        @case("list")
                                        @include('examTemplates.list', ['list' => $info->exercises['exercises'][$index]['list']])
                                        @break
                                        @case("options")
                                        @include('correctedExamTemplates.correctedOptions', ['options' => $info->exercises['exercises'][$index]['options'],
                                                                                'number' => $index,
                                                                                'studentAnswers' => $info->student_answers,
                                                                                'results' => $info->results])
                                        @break
                                        @case("optionsAndTable")
                                        @include('correctedExamTemplates.correctedOptionsAndTable', ['options' => $info->exercises['exercises'][$index]['options'],
                                                                                        'table' => $info->exercises['exercises'][$index]['table'],
                                                                                        'number' => $index,
                                                                                        'studentAnswers' => $info->student_answers,
                                                                                        'results' => $info->results])
                                        @break
                                        @case("table")
                                        @include('examTemplates.table', ['table' => $info->exercises['exercises'][$index]['table']])
                                    @endswitch
                                @endif
                            @endforeach
                        </div>
                        <div class="card-footer">
                            {{$info->exercises['exercises'][$index]['points']}} puncte
                        </div>
                    </div>
                </div>
                <br>
            @endfor
        </div>


    </form>
@endsection
