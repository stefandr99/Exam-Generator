@extends('layouts.app')

@section('title')
    <title>Pregatire examen</title>
@endsection

@section('content')
    <div class="container my-4">
        <h1 class="text-center"><b>Pregătiți examenul</b></h1>
        <br>
        <form class="form-group">
            <div class="d-flex">
                <div class="p-2">
                    <label for="exam-subject" class="large-text-font"><b>Materia:</b>
                        <select id="exam-subject" name="exam_course" class="form-control">
                            <option value="no-subject" selected disabled>--</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid course.
                        </div>
                    </label>
                </div>

                <div class="p-2">
                    <label for="exam-type" class="large-text-font"><b>Tipul examenului:</b>
                        <select id="exam-type" name="exam_type" class="form-control">
                            <option value="no-type" selected disabled>--</option>
                            <option value="Parțial">Parțial</option>
                            <option value="Examen">Examen</option>
                            <option value="Restanță">Restanță</option>
                        </select>
                    </label>
                </div>

                <div class="ml-auto p-2">
                    <label for="exam-date" class="large-text-font">
                        <b>Data și ora examenului:</b>
                        <input id="exam-date" name="exam_date" class="form-control" type="datetime-local" value="{{$tomorrow . "T08:00:00"}}">
                    </label>
                </div>

                <div class="p-2">
                    <label for="exam-duration" class="large-text-font"><b>Durata examenului:</b>
                        <div class="inline-elements">
                            <input id="exam-duration-hours" name="exam_hours" type="text" class="form-control hour-exam-column" size="5" placeholder="Ore">

                            <input id="exam-duration-minutes" name="exam_minutes" type="text" class="form-control min-exam-column" size="5" placeholder="Minute">
                        </div>
                    </label>
                </div>
            </div>

            <!-- <EXERCITII> -->
            <div class="large-text-font">
                <b>Exerciții:</b>
                <br>
                <div class="first-margin-left-exam-exercises">
                    Exercițiul 1.
                    <br>
                    <div class="second-margin-left-exam-exercises">
                        <label for="text-exercise-0" class="large-text-font">Enuntul exercitiului:
                            <textarea id="text-exercise-0" name="text_exercise_0" class="form-control" rows="3" cols="100" placeholder="Enunt">
                            </textarea>
                        </label>
                        <br>
                        <label for="exercise-0-option" class="large-text-font">Variante de raspuns:
                            <div class="third-margin-left-exam-exercises">
                                <div class="inline-elements">
                                    1.&nbsp;&nbsp;
                                        <input id="exercise-0-option-0" name="exercise_0_option_0" type="text" size="100" class="form-control" placeholder="Varianta de raspuns">&nbsp;
                                    <label>
                                        <input id="exercise-0-option-0-true" value="true" name="exercise_0_options_nr_0" type="radio" checked>
                                    </label>&nbsp;Corect &nbsp;&nbsp;
                                    <label>
                                        <input id="exercise-0-option-0-false" value="false" name="exercise_0_options_nr_0" type="radio">
                                    </label>&nbsp;Gresit
                                </div>

                                @for($op = 1; $op < 50; $op++)
                                    <div hidden id="exercise-0-hidden-option-{{$op}}" class="inline-elements">
                                        {{$op + 1}}.&nbsp;&nbsp;
                                        <input id="exercise-0-option-{{$op}}" name="exercise_0_option_{{$op}}" type="text" size="100" class="form-control" placeholder="Varianta de raspuns">
                                        &nbsp;<label>
                                            <input id="exercise-0-option-{{$op}}-true" value="true" name="exercise_0_options_nr_{{$op}}" type="radio" checked>
                                        </label>&nbsp;Corect &nbsp;
                                        &nbsp;<label>
                                            <input id="exercise-0-option-{{$op}}-false" value="false" name="exercise_0_options_nr_{{$op}}" type="radio">
                                        </label>&nbsp;Gresit
                                    </div>
                                @endfor


                                <button type="button" class="btn btn-outline-info btn-sm" onclick="addExerciseOption(0)">Adăugați încă o varianta</button>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeExerciseOption(0)">Stergeți ultima varianta</button>
                                <br><br>
                                <small>Numarul de variante de raspuns generate:</small>
                                <label>
                                    <input id="number-of-options-exercise-0" name="number_of_options_exercise_0" type="text" class="form-control nr-of-ops-per-ex" size="1" placeholder="Nr" onchange="$('#collapseExerciseCorrectness').collapse();">
                                </label>
                                <div class="collapse" id="collapseExerciseCorrectness">
                                    <div class="card card-body" style="width: 8.1rem; height: 5.4rem;">
                                        <div class="row" style=" padding-bottom: 30px;">
                                            <small>
                                                <label for="correct-options-ex-0">
                                                    Corecte: &nbsp;
                                                    <input id="correct-options-ex-0" name="correct_options_ex_0" type="text" class="form-control col correct-wrong-options" value="0">
                                                </label>
                                                <label for="wrong-options-ex-0">
                                                    Gresite:&nbsp;
                                                    <input id="wrong-options-ex-0" name="wrong_options_ex_0" type="text" class="form-control col correct-wrong-options" value="0">
                                                </label>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <label>
                                    <input id="shuffle-exercise-0-options" name="shuffle_0" type="checkbox">
                                </label> &nbsp;<small>Amesteca variantele de raspuns</small>
                            </div>

                        </label>
                        <label for="points-exercise-0" class="large-text-font">Puncte:
                            <input id="points-exercise-0" name="points_ex_0" type="text" class="form-control" placeholder="Puncte">
                        </label>
                    </div>

                    @for($ex = 1; $ex < 1; $ex++)
                        <div hidden id="exam-exercise-{{$ex}}">
                            Exercițiul {{$ex + 1}}.
                            <br>
                            <div class="second-margin-left-exam-exercises">
                                <label for="text-exercise-{{$ex}}" class="large-text-font">Enuntul exercitiului:
                                    <textarea id="text-exercise-{{$ex}}" name="text_exercise_{{$ex}}" class="form-control" rows="3" cols="100" placeholder="Enunt">
                                </textarea>
                                </label>
                                <br>
                                <label for="exercise-{{$ex}}-option" class="large-text-font">Variante de raspuns:
                                    <div class="third-margin-left-exam-exercises">
                                        <div class="inline-elements">
                                            1.&nbsp;&nbsp;
                                            <input id="exercise-{{$ex}}-option-0" name="exercise_{{$ex}}-option_0" type="text" size="100" class="form-control" placeholder="Varianta de raspuns">
                                            &nbsp;<label>
                                                <input id="exercise-{{$ex}}-option-0-true" value="true" name="exercise_{{$ex}}_options_nr_0" type="radio" checked>
                                            </label>&nbsp;Corect &nbsp;
                                            &nbsp;<label>
                                                <input id="exercise-{{$ex}}-option-0-false" value="false" name="exercise_{{$ex}}_options_nr_0" type="radio">
                                            </label>&nbsp;Gresit
                                        </div>

                                        @for($op = 1; $op < 1; $op++)
                                            <div hidden id="exercise-{{$ex}}-hidden-option-{{$op}}" class="inline-elements">
                                                {{$op + 1}}.&nbsp;&nbsp;
                                                <input id="exercise-{{$ex}}-option-{{$op}}" name="exercise_{{$ex}}_option_{{$op}}" type="text" size="100" class="form-control" placeholder="Varianta de raspuns">
                                                &nbsp;<label>
                                                    <input id="exercise-{{$ex}}-option-{{$op}}-true" value="true" name="exercise-{{$ex}}-options-nr-{{$op}}" type="radio" checked>
                                                </label>&nbsp;Corect &nbsp;
                                                &nbsp;<label>
                                                    <input id="exercise-{{$ex}}-option-{{$op}}-false" value="false" name="exercise-{{$ex}}-options-nr-{{$op}}" type="radio">
                                                </label>&nbsp;Gresit
                                            </div>
                                        @endfor

                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="addExerciseOption({{$ex}})">Adăugați încă o varianta</button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeExerciseOption({{$ex}})">Stergeți ultima varianta</button>

                                        <small>Numarul de variante de raspuns generate:</small>
                                        <label>
                                            <input id="number-of-options-exercise-{{$ex}}" name="number_of_options_exercise_{{$ex}}" type="text" class="form-control nr-of-ops-per-ex" size="1" placeholder="Nr">
                                        </label>

                                        <label>
                                            <input id="shuffle-exercise-{{$ex}}-options" name="shuffle_{{$ex}}" type="checkbox">
                                        </label> &nbsp;<small>Amesteca variantele de raspuns</small>
                                    </div>
                                </label>
                                <label for="points-exercise-{{$ex}}" class="large-text-font">Puncte:
                                    <input id="points-exercise-{{$ex}}" name="points_exercise_{{$ex}}" type="text" class="form-control" placeholder="Puncte">
                                </label>
                            </div>
                        </div>
                    @endfor

                    <button type="button" class="btn btn-outline-primary" onclick="addExercise()">Adăugați încă un exercitiu</button>
                    <button type="button" class="btn btn-outline-danger" onclick="removeExercise()">Stergeți ultimal exercitiu</button>

                </div>
            </div>

            <!-- </EXERCITII> -->

            <br>
            <label for="exam-minimum" class="large-text-font"><b>Punctajul minim:</b>
                <input id="exam-minimum" name="exam_minimum" type="text" class="form-control" placeholder="Punctaj minim">
            </label>
            <br>
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
        </form>

    </div>

    <script type="application/javascript">
        window.onload = function() {
            setNumberOfAnyExamExercises();
        }
    </script>

@endsection

