@extends('layouts.app')

@section('title')
    <title>Pregatire examen</title>
@endsection

@section('content')
    <div class="container my-4">
        <h1 class="text-center"><b>Pregătiți examenul</b></h1>
        <br>
        <form class="form-group" action="{{route('schedule_any_exam')}}" method="POST">
            @csrf

            <div class="row">
                <div class="col-sm-5 mx-auto">

                    <div class="card mt-3 tab-card text-center">
                        <div class="card-header tab-card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="examSubjectInfo" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="subject-tab" data-toggle="tab" href="#subject" role="tab" aria-controls="Materia" aria-selected="true">Materia</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="type-tab" data-toggle="tab" href="#type" role="tab" aria-controls="TipulExamenului" aria-selected="false">Tipul</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="subjectTypeContent">
                            <div class="tab-pane fade show active p-3" id="subject" role="tabpanel" aria-labelledby="subject-tab">
                                <h5 class="card-title">Materia examenului</h5>
                                <p class="card-text">Va rugam sa selectati materia pentru acest examen.</p>

                                <select id="exam-subject" name="exam_course" class="form-control custom-select @error('exam_course') is-invalid @enderror" style="width: 70%">
                                    <option disabled selected value="">--</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ (old("exam_course") == $course->id ? "selected":"") }}>{{ $course->name }}</option>
                                    @endforeach

                                </select>

                            </div>
                            <div class="tab-pane fade p-3" id="type" role="tabpanel" aria-labelledby="type-tab">
                                <h5 class="card-title">Tipul Examenului</h5>
                                <p class="card-text">Va rugam sa selectati ce fel de examen va fi acesta.</p>

                                <select id="exam-type" name="exam_type" class="form-control custom-select align-content-center @error('exam_type') is-invalid @enderror" style="width: 50%;">
                                    <option selected disabled value="">--</option>
                                    @foreach(array('Parțial', 'Examen', 'Restanță') as $type)
                                        <option value="{{$type}}" {{ (old("exam_type") == $type ? "selected":"") }}>{{$type}}</option>
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
                                    <a class="nav-link" id="hours-mins-tab" data-toggle="tab" href="#hoursAndMins" role="tab" aria-controls="OreMinute" aria-selected="false">Ore si minute</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="dateTimeContent">
                            <div class="tab-pane fade show active p-3" id="dateTime" role="tabpanel" aria-labelledby="date-tab">
                                <h5 class="card-title">Data sustinerii examenului</h5>
                                <p class="card-text">Va rugam sa alegeti data sustinerii examenului.</p>

                                <input id="exam-date" name="exam_date" class="form-control mx-auto @error('exam_date') is-invalid @enderror" type="datetime-local" value="{{$tomorrow . "T08:00:00"}}" style="width: 60%;">

                                @error('exam_date')
                                    <span class="invalid-tooltip">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>
                            <div class="tab-pane fade p-3" id="hoursAndMins" role="tabpanel" aria-labelledby="hours-mins-tab">
                                <h5 class="card-title">Durata examenului</h5>
                                <p class="card-text">Va rugam sa introduceti durata examenului in ore si minute.</p>

                                <div class="form-row">
                                    <div class="col-4 mx-auto">
                                        <input id="exam-duration-hours" name="exam_hours" type="text" class="form-control @error('exam_hours') is-invalid @enderror" placeholder="Ore" value="{{ old('exam_hours') }}" autofocus>
                                        @error('exam_hours')
                                            <div class="invalid-tooltip">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-4 mx-auto">
                                        <input id="exam-duration-minutes" name="exam_minutes" type="text" class="form-control @error('exam_minutes') is-invalid @enderror" placeholder="Minute" value="{{ old('exam_minutes') }}" autofocus>
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
                    <input hidden id="number_of_exercises" name="number_of_exercises" value="0">
                </label>
                    <div class="col-12 mx-auto">
                        <div class="card mt-3 tab-card">
                            <div class="card-header tab-card-header">
                                <ul class="nav nav-tabs card-header-tabs" id="exercisesTab" role="tablist">
                                    <li class="nav-item" id="exercise_0_tab">
                                        <a class="nav-link" id="exercise_0_title" data-toggle="tab" href="#exercise_0" role="tab" aria-controls="Exercise1" aria-selected="true">Exercitiul 1 &nbsp;<span class="close mt-1" onclick="deleteTab(this.parentNode.parentNode.id);">&times;</span></a>
                                    </li>
                                    <li class="nav-item" id="add_exercise_tab">
                                        <a class="nav-link" id="add_exercise_button" aria-selected="false" onclick="addNewExercise();"><i class="fas fa-plus-circle"></i></a>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content" id="exercisesContent">
                                <div class="tab-pane fade show active p-3" id="exercise_0" role="tabpanel" aria-labelledby="ex-0-tab">
                                    <label class="card-text text-uppercase font-weight-bold">Enunt:</label>
                                    <textarea id="text-exercise-0" name="text_exercise_0" class="form-control @error('text_exercise_0') is-invalid @enderror" rows="3" cols="100" placeholder="Enunt">
                                        {{old('text_exercise_0')}}
                                    </textarea>
                                    @error('text_exercise_0')
                                        <div class="invalid-tooltip invalid-tooltip-upper">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <input hidden id="number_of_options_exercise_0" name="number_of_options_exercise_0" value="0">
                                    <label class="card-text text-uppercase font-weight-bold">Variante de raspuns:</label>
                                    <div id="div_exercise_0_options">
                                        <div id="div_exercise_0_option_0">
                                            <div class="inline-elements">
                                                <label for="exercise-0-option-0">1.&nbsp;&nbsp;</label>
                                                <input id="exercise-0-option-0" name="exercise_0_option_0" type="text" class="form-control @error('exercise_0_option_0') is-invalid @enderror" value="{{old('exercise_0_option_0')}}" placeholder="Varianta de raspuns">&nbsp;

                                                <label>
                                                    <input id="exercise-0-option-0-true" value="true" name="exercise_0_option_0_answer" type="radio" checked>
                                                </label>
                                                <p>&nbsp;Corect&nbsp;&nbsp;</p>
                                                <label>
                                                    <input id="exercise-0-option-0-false" value="false" name="exercise_0_option_0_answer" type="radio">
                                                </label>
                                                <p>&nbsp;Gresit</p>
                                            </div>
                                        </div>
                                    </div>

                                    <button id="add_option_0" type="button" class="btn btn-outline-info btn-sm" onclick="addOption(0)">Adăugați încă o varianta</button>
                                    <button id="delete_option_0" type="button" class="btn btn-outline-danger btn-sm" onclick="removeOption(0)">Stergeți ultima varianta</button>

                                    <br>
                                    <small>Numarul de variante de raspuns generate:</small>
                                    <label for="number-of-options-exercise-0">
                                        <input id="number-of-options-exercise-0" name="number_of_generated_options_0" type="text" class="form-control nr-of-ops-per-ex @error('number_of_generated_options_0') is-invalid @enderror" size="1" placeholder="Nr" onchange="$('#collapseExerciseCorrectness_0').collapse();">
                                    </label>

                                    <div class="collapse" id="collapseExerciseCorrectness_0">
                                        <div class="card card-body" style="width: 8.1rem; height: 5.4rem;">
                                            <div class="row" style="padding-bottom: 30px;">
                                                <small>
                                                    <div class="position-relative">
                                                        <label for="correct-options-ex-0">
                                                            Corecte: &nbsp;
                                                            <input id="correct-options-ex-0" name="correct_options_ex_0" type="text" class="form-control col correct-wrong-options @error('correct_options_ex_0') is-invalid @enderror" value="0">
                                                        </label>
                                                        <label for="wrong-options-ex-0">
                                                            Gresite:&nbsp;
                                                            <input id="wrong-options-ex-0" name="wrong_options_ex_0" type="text" class="form-control col correct-wrong-options @error('wrong_options_ex_0') is-invalid @enderror" value="0">
                                                        </label>
                                                    </div>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <br>

                                    <label for="points-exercise-0" class="card-text text-uppercase font-weight-bold">Puncte:
                                        <input id="points-exercise-0" name="points_exercise_0" type="text" class="form-control @error('points_exercise_0') is-invalid @enderror" placeholder="Puncte">
                                        @error('points_exercise_0')
                                            <div class="invalid-tooltip invalid-tooltip-upper">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </label>
                                </div>
                            </div>

                            <div class="card-footer">
                                <label for="exam-minimum" class="card-text text-uppercase font-weight-bold">Punctaj minim:
                                    <input id="exam-minimum" name="exam_minimum" type="text" class="form-control @error('exam_minimum') is-invalid @enderror" placeholder="Punctaj minim">
                                    @error('exam_minimum')
                                        <div class="invalid-tooltip invalid-tooltip-upper">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </label>
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
                <input id="examPenalty1" value="points" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty1" aria-expanded="false" aria-controls="collapsePenalty1" onclick="onRadioPenaltyCollapse();">
            </label>
            Depunctare
            <div class="collapse" id="collapsePenalty1">
                <div class="card card-body" style="width: 8rem; height: 4.4rem">
                    <div class="row">
                        Puncte: &nbsp;
                        <label for="points-penalization" class="large-text-font">
                            <input id="points-penalization" name="points_penalization" type="text" class="form-control exam-penalty-input" value="0">
                        </label>
                    </div>
                </div>
            </div>
            <br>
            <label>
                <input id="examPenalty2" value="time" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty2" aria-expanded="false" aria-controls="collapsePenalty2" onclick="onRadioPenaltyCollapse();">
            </label>
            Scăderea din timpul rămas
            <div class="collapse" id="collapsePenalty2">
                <div class="card card-body" style="width: 9rem;">
                    <div class="row">
                        <label for="minutes-penalization">
                            Minute: &nbsp;<input id="minutes-penalization" name="minutes_penalization" type="text" class="form-control exam-penalty-input col" value="0">
                        </label>
                        <div style="width: 10px"></div>
                        <label for="seconds-penalization">
                            Secunde: &nbsp;<input id="seconds-penalization" name="seconds_penalization" type="text" class="form-control exam-penalty-input col" value="0">
                        </label>
                    </div>
                </div>
            </div>
            <br>

            <label>
                <input id="examPenalty3" value="limitations" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty3" aria-expanded="false" aria-controls="collapsePenalty3" onclick="onRadioPenaltyCollapse();">
            </label>
            Permite încalcarea regulii cu limită
            <div class="collapse" id="collapsePenalty3">
                <div class="card card-body" style="width: 16rem;">
                    <div class="row">
                        De maxim: &nbsp;
                        <label for="rule-limit" class="large-text-font">
                            <input id="rule-limit" name="rule_limit" type="text" class="form-control exam-penalty-input" value="0">
                        </label>
                        &nbsp;ori
                    </div>
                    <label for="rule-warnings" class="check-rule">
                        <input id="rule-warnings" name="rule_warnings" class="form-check-input warn-penalty-checkbox" type="checkbox">
                        &nbsp;<small>avertizează la fiecare abatere</small>
                    </label>
                    <div class="row">
                        Sancțiune la depașirea limitei:
                    </div>

                    <label>
                        <input id="examPenaltyLimit1" value="points" name="examPenaltyLimit" type="radio" data-toggle="collapse" data-target="#collapsePenaltyLimit1" aria-expanded="false" aria-controls="collapsePenaltyLimit1" onclick="onRadioPenaltyLimitCollapse();">
                        Depunctare
                    </label>
                    <div class="collapse" id="collapsePenaltyLimit1">
                        <div class="card card-body" style="width: 8rem; height: 4.4rem">
                            <div class="row">
                                Puncte: &nbsp;
                                <label for="limit-points-penalization" class="large-text-font">
                                    <input id="limit-points-penalization" name="limit_points_penalization" type="text" class="form-control exam-penalty-input" value="0">
                                </label>
                            </div>
                        </div>
                    </div>

                    <label>
                        <input id="examPenaltyLimit2" value="time" name="examPenaltyLimit" type="radio" data-toggle="collapse" data-target="#collapsePenaltyLimit2" aria-expanded="false" aria-controls="collapsePenaltyLimit1" onclick="onRadioPenaltyLimitCollapse();">
                        Scăderea din timpul rămas
                    </label>
                    <div class="collapse" id="collapsePenaltyLimit2">
                        <div class="card card-body" style="width: 9rem;">
                            <div class="row">
                                <label for="limit-minutes-penalization">
                                    Minute: &nbsp;
                                    <input id="limit-minutes-penalization" name="limit_minutes_penalization" type="text" class="form-control exam-penalty-input col" value="0">
                                </label>
                                <div style="width: 10px"></div>
                                <label for="limit-seconds-penalization">
                                    Secunde: &nbsp;
                                    <input id="limit-seconds-penalization" name="limit_seconds_penalization" type="text" class="form-control exam-penalty-input col" value="0">
                                </label>
                            </div>
                        </div>
                    </div>

                    <label>
                        <input id="examPenaltyLimit3" value="end" name="examPenaltyLimit" type="radio" onclick="onRadioPenaltyLimitCollapse();">
                        Incheierea examenului pentru studentul în cauză
                    </label>


                </div>
            </div>
            <br>

            <label>
                <input id="examPenalty4" value="end" name="examPenalty" type="radio" onclick="onRadioPenaltyCollapse();">
            </label>
            Incheierea examenului pentru studentul în cauză
            <br>

            <label>
                <input id="examPenalty5" value="without" name="examPenalty" type="radio" checked onclick="onRadioPenaltyCollapse();">
            </label>
            Fără penalizare
            <br>

            <div class="r_relationship">
                <button type="submit" class="btn btn-success btn-lg btn-block">Programați examenul</button>
            </div>
            </div>
        </form>

    </div>

    <script type="application/javascript">
        window.onload = function() {
            setNumberOfAnyExamExercises();
        }
    </script>

@endsection

