@extends('layouts.app')

@section('title')
    <title>Pregatire examen BD</title>
@endsection

@section('content')
    <div class="container my-4">
        <h1 class="text-center"><b>Pregătiți examenul la Baze de date</b></h1>
        <br>
        <form class="form-group" action="{{route('schedule_DB_exam')}}" method="POST">
            @csrf

            <div class="d-flex  justify-content-around">

                <input name="exam_course" value="{{$dbCourse->id}}" hidden>


                    <div class="position-relative">
                        <label for="exam-type" class="large-text-font"><b>Tipul examenului:</b></label><br>
                            <select id="exam-type" name="exam_type" class="form-control custom-select @error('exam_type') is-invalid @enderror" style="width: 150px; margin-top: -10px">
                                <option  disabled selected value="">--</option>
                                <option value="Parțial">Parțial</option>
                                <option value="Examen">Examen</option>
                                <option value="Restanță">Restanță</option>
                            </select>

                            @error('exam_type')
                                <span class="invalid-tooltip invalid-tooltip-upper">
                                    {{ $message }}
                                </span>
                            @enderror
                    </div>


                    <div class="position-relative">
                        <label for="exam-date" class="large-text-font">
                            <b>Data și ora examenului:</b>
                            <input id="exam-date" name="exam_date" class="form-control @error('exam_date') is-invalid @enderror" type="datetime-local" value="{{$tomorrow . "T08:00:00"}}">

                            @error('exam_date')
                                <span class="invalid-tooltip">
                                    {{ $message }}
                                </span>
                            @enderror
                        </label>
                    </div>



                <label for="exam-duration" class="large-text-font"><b>Durata examenului:</b>
                    <div class="min-and-hour-exam-parent">
                        <div class="position-relative">
                            <input id="exam-duration-hours" name="exam_hours" type="text" class="form-control hour-exam-column @error('exam_hours') is-invalid @enderror" size="5" placeholder="Ore">
                            @error('exam_hours')
                            <div class="invalid-tooltip">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="position-relative">
                            <input id="exam-duration-minutes" name="exam_minutes" type="text" class="form-control min-exam-column @error('exam_minutes') is-invalid @enderror" size="5" placeholder="Minute">
                            @error('exam_minutes')
                            <div class="invalid-tooltip" style="margin-left: 15px;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </label>
            </div>

            <!-- <EXERCITII> -->
            <div class="large-text-font">
                <b>Exerciții:</b><label>
                    <input hidden id="number_of_exercises" name="number_of_exercises" value="0">
                </label>
                <div class="first-margin-left-exam-exercises">
                    Exercițiul 1.
                    <br>
                    <div class="second-margin-left-exam-exercises">
                        <div class="position-relative">
                            <label for="exam-exercise-0" class="large-text-font">Tipul exercițiului:
                                <select id="exam-exercise-0" name="exam_exercise_0"  class="custom-select form-control @error('exam_exercise_0') is-invalid @enderror">
                                    <option value="" selected disabled>--</option>
                                    <option value="type-1">
                                        Determinarea dependețelor în funcție de o relație "r" dată tabelar
                                    </option>
                                    <option value="type-2">
                                        Determinarea dependețelor în funcție de o relație "Catalog(elev, notă, materie, datăNotare, profesor)" ce impune anumite restricții
                                    </option>
                                    <option value="type-3">
                                        Determinarea X+ în funcție de o schemă de relație "R" și o mulțime &Sigma; de dependențe funcționale
                                    </option>
                                    <option value="type-4">
                                        Determinarea cheilor candidat pentru o schemă de relație "R" și mulțimile de dependență &Sigma; și &Delta;
                                    </option>
                                </select>
                                @error('exam_exercise_0')
                                <div class="invalid-tooltip invalid-tooltip-upper">
                                    {{ $message }}
                                </div>
                                @enderror
                            </label>
                        </div>
                        <div class="position-relative">
                            <label for="exercise-0-points" class="large-text-font">Puncte:
                                <input id="exercise-0-points" name="points_exercise_0" type="text" class="form-control @error('points_exercise_0') is-invalid @enderror" placeholder="Puncte">
                                @error('points_exercise_0')
                                <div class="invalid-tooltip invalid-tooltip-upper">
                                    Va rugam adaugati punctajul exercitiului.
                                </div>
                                @enderror
                            </label>
                        </div>
                    </div>

                    <div class="extra-exercises">
                        @for($ex = 1; $ex < 100; $ex++)
                            <div hidden id="exercise-{{$ex}}">
                                Exercițiul {{ $ex + 1 }}.
                                <br>
                                <div class="second-margin-left-exam-exercises">
                                    <label for="exam-exercise-{{$ex}}" class="large-text-font">Tipul exercitiului:
                                        <select id="exam-exercise-{{$ex}}" name="exam_exercise_{{$ex}}" class="form-control">
                                            <option value="" selected disabled>--</option>
                                            <option value="type-1">
                                                Determinarea dependețelor în funcție de o relație "r" dată tabelar
                                            </option>
                                            <option value="type-2">
                                                Determinarea dependețelor în funcție de o relație "Catalog(elev, notă, materie, datăNotare, profesor)" ce impune anumite restricții
                                            </option>
                                            <option value="type-3">
                                                Determinarea X+ în funcție de o schemă de relație "R" și o mulțime &Sigma; de dependențe funcționale
                                            </option>
                                            <option value="type-4">
                                                Determinarea cheilor candidat pentru o schemă de relație "R" și mulțimile de dependență &Sigma; și &Delta;
                                            </option>
                                        </select>
                                    </label>
                                    <br>
                                    <label for="exercise-{{$ex}}-points" class="large-text-font">Puncte:
                                        <input id="exercise-{{$ex}}-points" name="points_exercise_{{$ex}}" type="text" class="form-control" placeholder="Puncte">
                                    </label>
                                </div>
                            </div>
                        @endfor
                    </div>


                    <br>
                    <button type="button" class="btn btn-outline-primary" onclick="addDBExercise()">Adăugați încă un exercițiu</button>
                    <button type="button" class="btn btn-outline-danger" onclick="removeDBExercise()">Stergeți ultimul exercițiu</button>
                </div>
            </div>
            <br>

            <div class="position-relative">
                <label for="exam-minimum" class="large-text-font"><b>Punctajul minim:</b>
                    <input id="exam-minimum" name="exam_minimum" type="text" class="form-control @error('exam_minimum') is-invalid @enderror" placeholder="Punctaj minim">
                    @error('exam_minimum')
                    <div class="invalid-tooltip invalid-tooltip-upper">
                        {{ $message }}
                    </div>
                    @enderror
                </label>
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
                        <input id="points-penalization" type="text" class="form-control exam-penalty-input" value="0">
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
                            Minute: &nbsp;<input id="minutes-penalization" type="text" class="form-control exam-penalty-input col" value="0">
                        </label>
                        <div style="width: 10px"></div>
                        <label for="seconds-penalization">
                            Secunde: &nbsp;<input id="seconds-penalization" type="text" class="form-control exam-penalty-input col" value="0">
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
                            <input id="rule-limit" type="text" class="form-control exam-penalty-input" value="0">
                        </label>
                        &nbsp;ori
                    </div>
                    <label for="rule-warnings" class="check-rule">
                        <input id="rule-warnings" class="form-check-input warn-penalty-checkbox" type="checkbox">
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
                                    <input id="limit-points-penalization" type="text" class="form-control exam-penalty-input" value="0">
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
                                    Minute: &nbsp;<input id="limit-minutes-penalization" type="text" class="form-control exam-penalty-input col" value="0">
                                </label>
                                <div style="width: 10px"></div>
                                <label for="limit-seconds-penalization">
                                    Secunde: &nbsp; <input id="limit-seconds-penalization" type="text" class="form-control exam-penalty-input col" value="0">
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

@endsection

