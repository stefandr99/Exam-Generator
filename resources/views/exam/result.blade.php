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
            {{ // DE FACUT: un template pentru corectarea si afisarea optiunilor fiecarui exercitiu!!! }}
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
                            <div class="form-check dependencies-options">
                                @if($studentAnswers[1][$option])
                                    <input class="form-check-input" type="checkbox" value="" checked onclick="return false;">
                                @else
                                    <input class="form-check-input" type="checkbox" value="" onclick="return false;">
                                @endif
                                <label class="form-check-label">
                                    @if($results[1][$option])
                                        <span id="correct-answer">{{ $exercise1['options']['solution'][$option]['option'] }}</span>
                                        ✅
                                        @if($studentAnswers[1][$option])
                                            <span id="correct-answer-text"><b>*Corect bifat!*</b></span>
                                        @else
                                            <span id="correct-answer-text"><b>*Da, acesta era un raspuns gresit!*</b></span>
                                        @endif
                                    @else
                                        <span id="wrong-answer">{{ $exercise1['options']['solution'][$option]['option'] }}</span>
                                        ❌
                                        @if($studentAnswers[1][$option])
                                            <span id="wrong-answer-text"><b>Gresit bifat!</b></span>
                                        @else
                                            <span id="wrong-answer-text"><b>*Acest raspuns trebuia bifat!*</b></span>
                                        @endif
                                    @endif
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
                    <div class="form-check dependencies-options">
                        @if($studentAnswers[2][$option])
                            <input class="form-check-input" type="checkbox" value="" checked onclick="return false;">
                        @else
                            <input class="form-check-input" type="checkbox" value="" onclick="return false;">
                        @endif
                        <label class="form-check-label">
                            @if($results[2][$option])
                                <span id="correct-answer">{{ $exercise2['options']['solution'][$option]['option'] }}</span>
                                ✅
                                @if($studentAnswers[2][$option])
                                    <span id="correct-answer-text"><b>*Corect bifat!*</b></span>
                                @else
                                    <span id="correct-answer-text"><b>*Da, acesta era un raspuns gresit!*</b></span>
                                @endif
                            @else
                                <span id="wrong-answer">{{ $exercise2['options']['solution'][$option]['option'] }}</span>
                                ❌
                                @if($studentAnswers[2][$option])
                                    <span id="wrong-answer-text"><b>Gresit bifat!</b></span>
                                @else
                                    <span id="wrong-answer-text"><b>*Acest raspuns trebuia bifat!*</b></span>
                                @endif
                            @endif
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
                    <div class="form-check dependencies-options">
                        @if($studentAnswers[3][$option])
                            <input class="form-check-input" type="checkbox" value="" checked onclick="return false;">
                        @else
                            <input class="form-check-input" type="checkbox" value="" onclick="return false;">
                        @endif
                        <label class="form-check-label">
                            @if($results[3][$option])
                                <span id="correct-answer">{{ $exercise3['options']['solution'][$option]['attr'] }}<sup>+</sup> = {{ $exercise3['options']['solution'][$option]['attr+'] }}</span>
                                ✅
                                @if($studentAnswers[3][$option])
                                    <span id="correct-answer-text"><b>*Corect bifat!*</b></span>
                                @else
                                    <span id="correct-answer-text"><b>*Da, acesta era un raspuns gresit!*</b></span>
                                    (Raspunsul corect: {{ $exercise3['options']['solution'][$option]['attr'] }}<sup>+</sup> = {{ $exercise3['options']['solution'][$option]['real_attr+'] }})
                                @endif
                            @else
                                <span id="wrong-answer">{{ $exercise3['options']['solution'][$option]['attr'] }}<sup>+</sup> = {{ $exercise3['options']['solution'][$option]['attr+'] }}</span>
                                ❌
                                @if($studentAnswers[3][$option])
                                    <span id="wrong-answer-text"><b>Gresit bifat!</b></span>
                                    (Raspunsul corect: {{ $exercise3['options']['solution'][$option]['attr'] }}<sup>+</sup> = {{ $exercise3['options']['solution'][$option]['real_attr+'] }})
                                @else
                                    <span id="wrong-answer-text"><b>*Acest raspuns trebuia bifat!*</b></span>
                                @endif
                            @endif
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
                    <div class="form-check dependencies-options">
                        @if($studentAnswers[4][$option])
                            <input class="form-check-input" type="checkbox" value="" checked onclick="return false;">
                        @else
                            <input class="form-check-input" type="checkbox" value="" onclick="return false;">
                        @endif
                        <label class="form-check-label">
                            @if($results[4][$option])
                                <span id="correct-answer">{{ $exercise4['options']['solution'][$option]['option'] }}</span>
                                ✅
                                @if($studentAnswers[4][$option])
                                    <span id="correct-answer-text"><b>*Corect bifat!*</b></span>
                                @else
                                    <span id="correct-answer-text"><b>*Da, acesta era un raspuns gresit!*</b></span>
                                @endif
                            @else
                                <span id="wrong-answer">{{ $exercise4['options']['solution'][$option]['option'] }}</span>
                                ❌
                                @if($studentAnswers[4][$option])
                                    <span id="wrong-answer-text"><b>Gresit bifat!</b></span>
                                @else
                                    <span id="wrong-answer-text"><b>*Acest raspuns trebuia bifat!*</b></span>
                                @endif
                            @endif
                        </label>
                    </div>
                @endfor
            </div>
        </div>


    </form>
@endsection
