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
                    <br>
                    <div class="remaining-exam-time">
                        Timp rămas: <p id="hourss"></p>:<p id="mins"></p>:<p id="secs"></p>
                    </div>
                </b>
            </h1>
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
                <button class="submit-exam-button" type="submit" style="outline: none;">
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
        var examTime = '{{$examTime}}';
        startTimer(examTime);
    };


    window.addEventListener('blur', function () {
        penalization('{{json_encode($penalization)}}');
    });
</script>
