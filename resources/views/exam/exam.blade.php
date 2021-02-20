@extends('layouts.app')

@section('content')
    <form>
    <div class="container my-4">
        <h1 id="partial-title"><b>Partial baze de date, <?php echo strftime("%A, %e %B, %Y");?></b></h1>
        <br>
        @for($index = 0; $index < 4; $index++)
            <h2><b>Exercitiul {{ $index + 1 }} ({{$exercises[$index]["points"]}} puncte)</b></h2>
            @foreach($exercises[$index]["content"] as $field)
                @if(str_starts_with($field, "text"))
                    @include('examTemplates.text', ['text' => $exercises[$index]["text"][intval($field[4])]])
                @else
                    @switch($field)
                        @case("list")
                            @include('examTemplates.list', ['list' => $exercises[$index]["list"]])
                            @break
                        @case("options")
                            @include('examTemplates.options', ['options' => $exercises[$index]["options"],
                                                                    'number' => $index + 1])
                            @break
                        @case("optionsAndTable")
                            @include('examTemplates.optionsAndTable', ['options' => $exercises[$index]["options"],
                                                                            'table' => $exercises[$index]["table"],
                                                                            'number' => $index + 1])
                            @break
                        @case("table")
                            @include('examTemplates.table', ['table' => $exercises[$index]["table"]])
                    @endswitch
                @endif
            @endforeach
            <br>
        @endfor
        <div class="r_relationship">
            <button type="button" class="btn btn-primary r_relationship" onclick="checkTest()">Verifica partialul</button>
        </div>
    </div>


    </form>
@endsection
