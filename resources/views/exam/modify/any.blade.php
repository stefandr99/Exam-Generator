@extends('layouts.app')

@section('title')
    <title>Modificare examen</title>
@endsection

@section('content')
    <div class="container my-4">
        <h1 class="text-center"><b>Modificați examenul la {{ $exam->course_name }}</b></h1>
        <br>
        <form class="form-group" action="{{route('update_exam')}}" method="POST">
            @method('PUT')
            @csrf

            <input id="exam-id" name="exam_id" value="{{$exam->id}}" hidden>
            <input name="exam_course" value="any" hidden>
            <div class="row">
                <div class="col-sm-5 mx-auto">
                    <div class="card mt-3 tab-card text-center">
                        <div class="card-header tab-card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="examSubjectInfo" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="type-tab" data-toggle="tab" href="#type" role="tab" aria-controls="TipulExamenului" aria-selected="false">Tipul</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="subjectTypeContent">
                            <div class="tab-pane fade show active p-3" id="type" role="tabpanel" aria-labelledby="type-tab">
                                <h5 class="card-title">Tipul Examenului</h5>
                                <p class="card-text">Vă rugăm să selectați ce fel de examen va fi acesta.</p>

                                <select id="exam-type" name="exam_type" class="form-control custom-select align-content-center @error('exam_type') is-invalid @enderror" style="width: 50%;">
                                    <option selected disabled value="">--</option>
                                    @foreach(array('Parțial', 'Examen', 'Restanță') as $type)
                                        @if($type == $exam->type)
                                            <option value="{{$type}}" selected>{{$type}}</option>
                                        @else
                                            <option value="{{$type}}">{{$type}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-sm-5 mx-auto">
                    <div class="card mt-3 tab-card text-center">
                        <div class="card-header tab-card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="timeTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="date-tab" data-toggle="tab" href="#dateTime" role="tab" aria-controls="Data" aria-selected="true">Data</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="hours-mins-tab" data-toggle="tab" href="#hoursAndMins" role="tab" aria-controls="OreMinute" aria-selected="false">Ore și minute</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="dateTimeContent">
                            <div class="tab-pane fade show active p-3" id="dateTime" role="tabpanel" aria-labelledby="date-tab">
                                <h5 class="card-title">Data susținerii examenului</h5>
                                <p class="card-text">Vă rugăm să alegeți data susținerii examenului.</p>

                                <input id="exam-date" name="exam_date" class="form-control mx-auto" type="datetime-local" value="{{explode(" ", $exam->starts_at)[0] . "T" . explode(" ", $exam->starts_at)[1]}}" style="width: 60%;">
                            </div>
                            <div class="tab-pane fade p-3" id="hoursAndMins" role="tabpanel" aria-labelledby="hours-mins-tab">
                                <h5 class="card-title">Durata examenului</h5>
                                <p class="card-text">Vă rugăm să introduceți durata examenului în ore și minute.</p>

                                <div class="form-row">
                                    <div class="col-4 mx-auto">
                                        <input id="exam-duration-hours" name="exam_hours" type="text" class="form-control @error('exam_hours') is-invalid @enderror" placeholder="Ore" value="{{$exam->hours}}" autofocus>
                                        @error('exam_hours')
                                            <div class="invalid-tooltip">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-4 mx-auto">
                                        <input id="exam-duration-minutes" name="exam_minutes" type="text" class="form-control @error('exam_minutes') is-invalid @enderror" placeholder="Minute" value="{{$exam->minutes}}" autofocus>
                                        @error('exam_minutes')
                                            <div class="invalid-tooltip">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- <EXERCITII> -->
            @include('prepareTemplates.optionAny')
            @include('prepareTemplates.exerciseAny')

            <div class="large-text-font">
                <label>
                    <input hidden id="number_of_exercises" name="number_of_exercises" value="{{ $exam->number_of_exercises - 1 }}">
                </label>
                <div class="col-12 mx-auto">
                    <div class="card mt-3 tab-card">
                        <div class="card-header tab-card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="exercisesTab" role="tablist">
                                @for($i = 0; $i < $exam->number_of_exercises; $i++)
                                    <li class="nav-item" id="exercise_{{$i}}_tab">
                                        <a class="nav-link" id="exercise_{{$i}}_title" data-toggle="tab" href="#exercise_{{$i}}" role="tab" aria-controls="Exercise{{$i + 1}}" aria-selected="true">Exercitiul {{$i + 1}} &nbsp;<span class="close mt-1" onclick="deleteTab(this.parentNode.parentNode.id);">&times;</span></a>
                                    </li>
                                @endfor
                                <li class="nav-item" id="add_exercise_tab">
                                    <a class="nav-link" id="add_exercise_button" aria-selected="false" onclick="addNewExercise();"><i class="fas fa-plus-circle"></i></a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="exercisesContent">
                            @for($i = 0; $i < $exam->number_of_exercises; $i++)
                                <div class="tab-pane fade show active p-3" id="exercise_{{$i}}" role="tabpanel" aria-labelledby="ex-{{$i}}-tab">
                                    <label class="card-text text-uppercase font-weight-bold">Enunț:</label>
                                    <textarea id="text-exercise-{{$i}}" name="text_exercise_{{$i}}" class="form-control" rows="3" cols="100" placeholder="Enunt">{{$exam->exercises['exercises'][$i]['text']}}</textarea>
                                    @error("text_exercise_{{$i}}")
                                    <div class="invalid-tooltip invalid-tooltip-upper">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                    <input hidden id="number_of_options_exercise_{{$i}}" name="number_of_options_exercise_{{$i}}" value="{{$exam->exercises['exercises'][$i]['options']['counter'] - 1}}">
                                    <label class="card-text text-uppercase font-weight-bold">Variante de răspuns:</label>
                                    <div id="div_exercise_{{$i}}_options">
                                        @for($j = 0; $j < $exam->exercises['exercises'][$i]['options']['counter']; $j++)
                                            <div id="div_exercise_{{$i}}_option_{{$j}}">
                                                <div class="inline-elements">
                                                    <label for="exercise-{{$i}}-option-{{$j}}">{{$j + 1}}.&nbsp;&nbsp;</label>
                                                    <input id="exercise-{{$i}}-option-{{$j}}" name="exercise_{{$i}}_option_{{$j}}" type="text" class="form-control" value="{{$exam->exercises['exercises'][$i]['options']['solution'][$j]['option']}}" placeholder="Varianta de raspuns">&nbsp;

                                                    <label>
                                                        <input id="exercise-{{$i}}-option-{{$j}}-true" value="true" name="exercise_{{$i}}_option_{{$j}}_answer" type="radio" {{ $exam->exercises['exercises'][$i]['options']['solution'][$j]['answer'] == true ? "checked": "" }}>
                                                    </label>
                                                    <p>&nbsp;Corect&nbsp;&nbsp;</p>
                                                    <label>
                                                        <input id="exercise-{{$i}}-option-{{$j}}-false" value="false" name="exercise_{{$i}}_option_{{$j}}_answer" type="radio" {{ $exam->exercises['exercises'][$i]['options']['solution'][$j]['answer'] == false ? "checked": "" }}>
                                                    </label>
                                                    <p>&nbsp;Greșit</p>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>

                                    <div class="float-left">
                                        <button id="add_option_{{$i}}" type="button" class="btn btn-outline-info btn-sm" onclick="addOption({{$i}})">Adăugați încă o varianta</button>
                                        <button id="delete_option_{{$i}}" type="button" class="btn btn-outline-danger btn-sm" onclick="removeOption({{$i}})">Stergeți ultima varianta</button>

                                        <br>
                                        <small>Numărul de variante de răspuns generate:</small>
                                        <label for="number-of-options-exercise-{{$i}}">
                                            <input id="number-of-options-exercise-{{$i}}" name="number_of_generated_options_{{$i}}" type="text" class="form-control nr-of-ops-per-ex" size="1" placeholder="Nr" onchange="$('#collapseExerciseCorrectness_{{$i}}').collapse();" value="{{$exam->exercises['exercises'][$i]['options']['generate']['total']}}">
                                        </label>

                                        <div class="collapse show" id="collapseExerciseCorrectness_{{$i}}">
                                            <div class="card card-body" style="width: 8.1rem; height: 5.4rem;">
                                                <div class="row" style="padding-bottom: 30px;">
                                                    <small>
                                                        <div class="position-relative">
                                                            <label for="correct-options-ex-{{$i}}">
                                                                Corecte: &nbsp;
                                                                <input id="correct-options-ex-{{$i}}" name="correct_options_ex_{{$i}}" type="text" class="form-control col correct-wrong-options" value="{{$exam->exercises['exercises'][$i]['options']['generate']['correct']}}">
                                                            </label>
                                                            <label for="wrong-options-ex-{{$i}}">
                                                                Greșite:&nbsp;
                                                                <input id="wrong-options-ex-{{$i}}" name="wrong_options_ex_{{$i}}" type="text" class="form-control col correct-wrong-options" value="{{$exam->exercises['exercises'][$i]['options']['generate']['wrong']}}">
                                                            </label>
                                                        </div>
                                                    </small>
                                                </div>
                                            </div>
                                            <br>
                                        </div>
                                    </div>

                                    <div class="float-right form-inline mt-2">
                                        <label for="points-exercise-{{$i}}" class="card-text text-uppercase font-weight-bold">Puncte: &nbsp;
                                            <input id="points-exercise-{{$i}}" name="points_exercise_{{$i}}" type="text" class="form-control" placeholder="Puncte" value="{{$exam->exercises['exercises'][$i]['points']}}">
                                        </label>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="card-footer">
                            <label for="exam-minimum" class="card-text text-uppercase font-weight-bold">Punctaj minim:
                                <input id="exam-minimum" name="exam_minimum" type="text" class="form-control @error('exam_minimum') is-invalid @enderror" placeholder="Punctaj minim" value="{{$exam->minimum_points}}">
                                @error('exam_minimum')
                                    <div class="invalid-tooltip invalid-tooltip-upper">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </label>
                        </div>

                    </div>
                </div>
            </div>


            <br>
            <p class="large-text-font"><b>Penalizare:</b>
                <br>
                <small>
                    <b>INFO: </b>Aplicați penalizarea <b>"focus on exam"</b> pentru studenți. În timpul examenului dacă un student nu mai are în
                    prim plan subiectul examenului (pagina web a aplicației), acestuia i se va aplica una din urmatoarele sancțiuni
                    (<b>Important:</b> fiecare penalizare se va executa per greșeala):
                </small>
            </p>
            <label>
                @if($exam->penalization['type'] == 'points')
                    <input id="examPenalty1" value="points" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty1" aria-expanded="false" aria-controls="collapsePenalty1" checked onclick="onRadioPenaltyCollapse();">
                @else
                    <input id="examPenalty1" value="points" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty1" aria-expanded="false" aria-controls="collapsePenalty1" onclick="onRadioPenaltyCollapse();">
                @endif
            </label>
            Depunctare
            <div class="collapse" id="collapsePenalty1">
                <div class="card card-body" style="width: 8rem; height: 4.4rem">
                    <div class="row">
                        Puncte: &nbsp;
                        <label for="points-penalization" class="large-text-font">
                            @if($exam->penalization['type'] == 'points')
                                <input id="points-penalization" name="points_penalization" type="text" class="form-control exam-penalty-input" value="{{$exam->penalization['body']['points']}}">
                            @else
                                <input id="points-penalization" name="points_penalization" type="text" class="form-control exam-penalty-input" value="0">
                            @endif
                        </label>
                    </div>
                </div>
            </div>
            <br>
            <label>
                @if($exam->penalization['type'] == 'time')
                    <input id="examPenalty2" value="time" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty2" aria-expanded="false" aria-controls="collapsePenalty2" checked onclick="onRadioPenaltyCollapse();">
                @else
                    <input id="examPenalty2" value="time" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty2" aria-expanded="false" aria-controls="collapsePenalty2" onclick="onRadioPenaltyCollapse();">
                @endif
            </label>
            Scăderea din timpul rămas
            <div class="collapse" id="collapsePenalty2">
                <div class="card card-body" style="width: 9rem;">
                    <div class="row">
                        @if($exam->penalization['type'] == 'time')
                            <label for="minutes-penalization">
                                Minute: &nbsp;<input id="minutes-penalization" name="minutes_penalization" type="text" class="form-control exam-penalty-input col" value="{{$exam->penalization['body']['minutes']}}">
                            </label>
                            <div style="width: 10px"></div>
                            <label for="seconds-penalization">
                                Secunde: &nbsp;<input id="seconds-penalization" name="seconds_penalization" type="text" class="form-control exam-penalty-input col" value="{{$exam->penalization['body']['seconds']}}">
                            </label>
                        @else
                            <label for="minutes-penalization">
                                Minute: &nbsp;<input id="minutes-penalization" name="minutes_penalization" type="text" class="form-control exam-penalty-input col" value="0">
                            </label>
                            <div style="width: 10px"></div>
                            <label for="seconds-penalization">
                                Secunde: &nbsp;<input id="seconds-penalization" name="seconds_penalization" type="text" class="form-control exam-penalty-input col" value="0">
                            </label>
                        @endif
                    </div>
                </div>
            </div>
            <br>

            <label>
                @if($exam->penalization['type'] == 'limitations')
                    <input id="examPenalty3" value="limitations" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty3" aria-expanded="false" aria-controls="collapsePenalty3" checked onclick="onRadioPenaltyCollapse();">
                @else
                    <input id="examPenalty3" value="limitations" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty3" aria-expanded="false" aria-controls="collapsePenalty3" onclick="onRadioPenaltyCollapse();">
                @endif
            </label>
            Permite încalcarea regulii cu limită
            <div class="collapse" id="collapsePenalty3">
                <div class="card card-body" style="width: 16rem;">
                    <div class="row">
                        De maxim: &nbsp;
                        <label for="rule-limit" class="large-text-font">
                            @if($exam->penalization['type'] == 'limitations')
                                <input id="rule-limit" name="rule_limit" type="text" class="form-control exam-penalty-input" value="{{$exam->penalization['body']['limit']}}">
                            @else
                                <input id="rule-limit" name="rule_limit" type="text" class="form-control exam-penalty-input" value="0">
                            @endif
                        </label>
                        &nbsp;ori
                    </div>
                    <label for="rule-warnings" class="check-rule">
                        @if($exam->penalization['type'] == 'limitations' && $exam->penalization['body']['warnings'] == true)
                            <input id="rule-warnings" name="rule_warnings" class="form-check-input warn-penalty-checkbox" type="checkbox" checked>
                        @else
                            <input id="rule-warnings" name="rule_warnings" class="form-check-input warn-penalty-checkbox" type="checkbox">
                        @endif
                        &nbsp;<small>avertizează la fiecare abatere</small>
                    </label>
                    <div class="row">
                        Sancțiune la depașirea limitei:
                    </div>

                    <label>
                        @if($exam->penalization['type'] == 'limitations' && $exam->penalization['body']['exceeded']['type'] == 'points')
                            <input id="examPenaltyLimit1" value="points" name="examPenaltyLimit" type="radio" data-toggle="collapse" data-target="#collapsePenaltyLimit1" aria-expanded="false" aria-controls="collapsePenaltyLimit1" checked onclick="onRadioPenaltyLimitCollapse();">
                        @else
                            <input id="examPenaltyLimit1" value="points" name="examPenaltyLimit" type="radio" data-toggle="collapse" data-target="#collapsePenaltyLimit1" aria-expanded="false" aria-controls="collapsePenaltyLimit1" onclick="onRadioPenaltyLimitCollapse();">
                        @endif
                        Depunctare
                    </label>
                    <div class="collapse" id="collapsePenaltyLimit1">
                        <div class="card card-body" style="width: 8rem; height: 4.4rem">
                            <div class="row">
                                Puncte: &nbsp;
                                <label for="limit-points-penalization" class="large-text-font">
                                    @if($exam->penalization['type'] == 'limitations' && $exam->penalization['body']['exceeded']['type'] == 'points')
                                        <input id="limit-points-penalization" name="limit_points_penalization" type="text" class="form-control exam-penalty-input" value="{{$exam->penalization['body']['exceeded']['points']}}">
                                    @else
                                        <input id="limit-points-penalization" name="limit_points_penalization" type="text" class="form-control exam-penalty-input" value="0">
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>

                    <label>
                        @if($exam->penalization['type'] == 'limitations' && $exam->penalization['body']['exceeded']['type'] == 'time')
                            <input id="examPenaltyLimit2" value="time" name="examPenaltyLimit" type="radio" data-toggle="collapse" data-target="#collapsePenaltyLimit2" aria-expanded="false" aria-controls="collapsePenaltyLimit1" checked onclick="onRadioPenaltyLimitCollapse();">
                        @else
                            <input id="examPenaltyLimit2" value="time" name="examPenaltyLimit" type="radio" data-toggle="collapse" data-target="#collapsePenaltyLimit2" aria-expanded="false" aria-controls="collapsePenaltyLimit1" onclick="onRadioPenaltyLimitCollapse();">
                        @endif
                        Scăderea din timpul rămas
                    </label>
                    <div class="collapse" id="collapsePenaltyLimit2">
                        <div class="card card-body" style="width: 9rem;">
                            <div class="row">
                                @if($exam->penalization['type'] == 'limitations' && $exam->penalization['body']['exceeded']['type'] == 'time')
                                    <label for="limit-minutes-penalization">
                                        Minute: &nbsp;<input id="limit-minutes-penalization" name="limit_minutes_penalization" type="text" class="form-control exam-penalty-input col" value="{{$exam->penalization['body']['exceeded']['minutes']}}">
                                    </label>
                                    <div style="width: 10px"></div>
                                    <label for="limit-seconds-penalization">
                                        Secunde: &nbsp; <input id="limit-seconds-penalization" name="limit_seconds_penalization" type="text" class="form-control exam-penalty-input col" value="{{$exam->penalization['body']['exceeded']['seconds']}}">
                                    </label>
                                @else
                                    <label for="limit-minutes-penalization">
                                        Minute: &nbsp;<input id="limit-minutes-penalization" name="limit_minutes_penalization" type="text" class="form-control exam-penalty-input col" value="0">
                                    </label>
                                    <div style="width: 10px"></div>
                                    <label for="limit-seconds-penalization">
                                        Secunde: &nbsp; <input id="limit-seconds-penalization" name="limit_seconds_penalization" type="text" class="form-control exam-penalty-input col" value="0">
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <label>
                        @if($exam->penalization['type'] == 'end')
                            <input id="examPenaltyLimit3" value="end" name="examPenaltyLimit" type="radio" checked onclick="onRadioPenaltyLimitCollapse();">
                        @else
                            <input id="examPenaltyLimit3" value="end" name="examPenaltyLimit" type="radio" onclick="onRadioPenaltyLimitCollapse();">
                        @endif
                        Incheierea examenului pentru studentul în cauză
                    </label>
                </div>
            </div>
            <br>

            <label>
                @if($exam->penalization['type'] == 'end')
                    <input id="examPenalty4" value="end" name="examPenalty" type="radio" checked onclick="onRadioPenaltyCollapse();">
                @else
                    <input id="examPenalty4" value="end" name="examPenalty" type="radio" onclick="onRadioPenaltyCollapse();">
                @endif
            </label>
            Incheierea examenului pentru studentul în cauză
            <br>

            <label>
                @if($exam->penalization['type'] == 'without')
                    <input id="examPenalty5" value="without" name="examPenalty" type="radio" checked onclick="onRadioPenaltyCollapse();">
                @else
                    <input id="examPenalty5" value="without" name="examPenalty" type="radio" onclick="onRadioPenaltyCollapse();">
                @endif
            </label>
            Fără penalizare
            <br>

            <div class="r_relationship">
                <button type="submit" class="btn btn-success btn-lg btn-block">Modificati examenul</button>
            </div>
        </form>

    </div>

    <script type="application/javascript">
        window.onload = function() {
            clickExercisesTabs();
        }
    </script>

@endsection

