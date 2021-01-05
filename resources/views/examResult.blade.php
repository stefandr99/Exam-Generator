@extends('layouts.app')

@section('content')
    <form>
        <div class="container my-4">
            <h1 style="text-align: center"><b>Partial baze de date, <?php echo strftime("%A, %e %B, %Y");?> - {{ $points }}</b></h1>
            <br>
            <h2><b>Exercitiul 1 (3 puncte)</b></h2><h4> Sa se stabileasca care dintre urmatoarele dependente sunt satisfacute de catre relatia <i>r</i> data tabelar:</h4>
            <div class="container px-lg-5">
                <div class="row mx-lg-n3">
                    <div class="col py-3 px-lg-4 bg-light r_relationship">
                        <table class="table">
                            <thead>
                            <tr>
                                @foreach($exercise1['attributes'] as $attr)
                                    <th scope="col">{{ $attr }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @for($row = 1; $row <= $exercise1['relation']['counter']; $row++)
                                <tr>
                                    @for($i = 0; $i < 5; $i++)
                                        <td>{{ $exercise1['relation']['table'][$row][$i] }}</td>
                                    @endfor
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                    <div class="col py-5 px-lg-5 bg-light">
                        @for($option = 1; $option <= $exercise1['options']['counter']; $option++)
                            <div class="form-check dependencies_options">
                                <input class="form-check-input" type="checkbox" value="" id="ex1option{{$option}}"  style="transform: scale(1.5)">
                                <label class="form-check-label" for="ex1option{{$option}}">
                                    {{ chr(96 + $option) }}) {{ $exercise1['options']['solution'][$option]['option'] }}
                                </label>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <h2><b>Exercitiul 2 (3 puncte)</b></h2>
            <h4>
                {{ $exercise2['problem_text1'] }}
                <br>
                <br>
                <div class="px-lg-5">
                    <ul class="list-group">
                        @for($i = 1; $i <= $exercise2['sentences']['counter']; $i++)
                            <li>{{$exercise2['sentences'][$i]}}</li>
                        @endfor
                    </ul>
                </div>
                <br>
                {!! $exercise2['problem_text2'] !!}
            </h4>
            <div class="px-lg-5">
                @for($option = 1; $option <= $exercise2['options']['counter']; $option++)
                    <div class="form-check dependencies_options">
                        <input class="form-check-input" type="checkbox" value="" id="ex2option{{$option}}" style="transform: scale(1.5)">
                        <label class="form-check-label" for="ex2option{{$option}}">
                            {{ chr(96 + $option) }}) {{ $exercise2['options']['solution'][$option]['option'] }}
                        </label>
                    </div>
                @endfor
            </div>
            <br>

            <h2><b>Exercitiul 3 (3 puncte)</b></h2>
            <h4>
                Care sunt adevarate pentru schema de relatie {{ $exercise3['relationship'] }} si <br>&Sigma; = {
                @for($i = 1; $i < $exercise3['sigma']['counter']; $i++)
                    {{ $exercise3['sigma']['dependencies'][$i]['leftside'] }} -> {{ $exercise3['sigma']['dependencies'][$i]['rightside'] }},
                @endfor
                {{ $exercise3['sigma']['dependencies'][$i]['leftside'] }} -> {{ $exercise3['sigma']['dependencies'][$i]['rightside'] }} }
            </h4>
            <div class="px-lg-5">
                @for($option = 1; $option <= $exercise3['options']['counter']; $option++)
                    <div class="form-check dependencies_options">
                        <input class="form-check-input" type="checkbox" value="" id="ex3option{{$option}}" style="transform: scale(1.5)">
                        <label class="form-check-label" for="ex3option{{$option}}">
                            {{ chr(96 + $option) }}) {{ $exercise3['options']['solution'][$option]['attr'] }}<sup>+</sup> = {{ $exercise3['options']['solution'][$option]['attr+'] }}
                        </label>
                    </div>
                @endfor
            </div>
            <br>

            <h2><b>Exercitiul 4 (3 puncte)</b></h2>
            <h4>
                {!! $exercise4['exercise'] !!}
            </h4>
            <div class="px-lg-5">
                @for($option = 1; $option <= $exercise4['options']['counter']; $option++)
                    <div class="form-check dependencies_options">
                        <input class="form-check-input" type="checkbox" value="" id="ex4option{{$option}}" style="transform: scale(1.5)">
                        <label class="form-check-label" for="ex4option{{$option}}">
                            {{ chr(96 + $option) }}) {{ $exercise4['options']['solution'][$option]['option'] }}
                        </label>
                    </div>
                @endfor
            </div>
        </div>


    </form>
@endsection
