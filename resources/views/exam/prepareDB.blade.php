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

            <div class="row">
                <input name="exam_course" value="{{$dbCourse->id}}" hidden>

                <div class="col-sm-5 mx-auto">
                    <div class="card mt-3 tab-card text-center">
                        <div class="card-header tab-card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="examSubjectInfo" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="type-tab" data-toggle="tab" href="#type" role="tab" aria-controls="TipulExamenului" aria-selected="true">Tipul</a>
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
                                        <option value="{{$type}}" {{ (old("exam_type") == $type ? "selected":"") }}>{{$type}}</option>
                                    @endforeach
                                </select>
                                @error('exam_type')
                                    <span class="invalid-tooltip invalid-tooltip-upper">
                                        {{ $message }}
                                    </span>
                                @enderror
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
                                <h5 class="card-title">Data susținerii examenului</h5>
                                <p class="card-text">Vă rugăm să alegeti data susținerii examenului.</p>

                                <input id="exam-date" name="exam_date" class="form-control mx-auto @error('exam_date') is-invalid @enderror" type="datetime-local" value="{{$tomorrow . "T08:00:00"}}" style="width: 60%;">

                                @error('exam_date')
                                    <span class="invalid-tooltip">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>
                            <div class="tab-pane fade p-3" id="hoursAndMins" role="tabpanel" aria-labelledby="hours-mins-tab">
                                <h5 class="card-title">Durata examenului</h5>
                                <p class="card-text">Vă rugăm să introduceți durata examenului în ore și minute.</p>

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

            @include('prepareTemplates.exerciseDB')
            <!-- <EXERCITII> -->
            <div class="large-text-font">
                <label>
                    <input hidden id="number_of_exercises" name="number_of_exercises" value="0">
                </label>
                <div class="col-12 mx-auto">
                    <div class="card mt-3 tab-card">
                        <div class="card-header tab-card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="exercisesTab" role="tablist">
                                <li class="nav-item" id="exercise_0_tab">
                                    <a class="nav-link" id="exercise_0_title" data-toggle="tab" href="#exercise_0" role="tab" aria-controls="Exercise1" aria-selected="true">Exercițiul 1 &nbsp;<span class="close mt-1" onclick="deleteTab(this.parentNode.parentNode.id);">&times;</span></a>
                                </li>
                                <li class="nav-item" id="add_exercise_tab">
                                    <a class="nav-link" id="add_exercise_button" aria-selected="false" onclick="addNewDbExercise();"><i class="fas fa-plus-circle"></i></a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="exercisesContent">
                            <div class="tab-pane fade show active p-3" id="exercise_0" role="tabpanel" aria-labelledby="ex-0-tab">
                                <label for="exam-exercise-0" class="card-text text-uppercase font-weight-bold">Tipul exercițiului:</label>
                                <select id="exam-exercise-0" name="exam_exercise_0"  class="custom-select form-control @error('exam_exercise_0') is-invalid @enderror">
                                    <option value="" selected disabled>--</option>
                                    <option value="type-1" {{ (old("exam_exercise_0") == "type-1" ? "selected":"") }}>
                                        Determinarea dependețelor în funcție de o relație "r" dată tabelar
                                    </option>
                                    <option value="type-2" {{ (old("exam_exercise_0") == "type-2" ? "selected":"") }}>
                                        Determinarea dependețelor în funcție de o relație "Catalog(elev, notă, materie, datăNotare, profesor)" ce impune anumite restricții
                                    </option>
                                    <option value="type-3" {{ (old("exam_exercise_0") == "type-3" ? "selected":"") }}>
                                        Determinarea X+ în funcție de o schemă de relație "R" și o mulțime &Sigma; de dependențe funcționale
                                    </option>
                                    <option value="type-4" {{ (old("exam_exercise_0") == "type-4" ? "selected":"") }}>
                                        Determinarea cheilor candidat pentru o schemă de relație "R" și mulțimile de dependență &Sigma; și &Delta;
                                    </option>
                                </select>
                                @error('exam_exercise_0')
                                    <div class="invalid-tooltip invalid-tooltip-upper">
                                        {{ $message }}
                                    </div>
                                @enderror

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
                                    Minute: &nbsp;<input id="limit-minutes-penalization" name="limit_minutes_penalization" type="text" class="form-control exam-penalty-input col" value="0">
                                </label>
                                <div style="width: 10px"></div>
                                <label for="limit-seconds-penalization">
                                    Secunde: &nbsp; <input id="limit-seconds-penalization" name="limit_seconds_penalization" type="text" class="form-control exam-penalty-input col" value="0">
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

