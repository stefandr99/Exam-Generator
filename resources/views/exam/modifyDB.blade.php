@extends('layouts.app')

@section('title')
    <title>Modificare examen Baze de date</title>
@endsection

@section('content')
    <div class="container my-4">
        <h1 class="text-center"><b>Modificați examenul la Baze de date</b></h1>
        <br>
        <form class="form-group">
            <div class="d-flex  justify-content-between">

                <input id="exam-course" name="exam_course" value="{{$dbCourse->id}}" hidden>

                <div class="position-relative">
                    <label for="exam-type" class="large-text-font"><b>Tipul examenului:</b></label><br>
                    <select id="exam-type" name="exam_type" class="form-control custom-select @error('exam_type') is-invalid @enderror" style="width: 150px; margin-top: -8px">
                        @foreach(array("Parțial", "Examen", "Restanță") as $examType)
                            @if($examType == $exam->type)
                                <option value="{{$examType}}" selected>{{$examType}}</option>
                            @else
                                <option value="{{$examType}}">{{$examType}}</option>
                            @endif
                        @endforeach
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
                        <input id="exam-date" name="exam_date" class="form-control @error('exam_date') is-invalid @enderror" type="datetime-local" value="{{explode(" ", $exam->starts_at)[0] . "T" . explode(" ", $exam->starts_at)[1]}}">

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
                            <input id="exam-duration-hours" name="exam_hours" type="text" class="form-control hour-exam-column @error('exam_hours') is-invalid @enderror" value="{{$exam->hours}}" size="5" placeholder="Ore">
                            @error('exam_hours')
                            <div class="invalid-tooltip">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="position-relative">
                            <input id="exam-duration-minutes" name="exam_minutes" type="text" class="form-control min-exam-column @error('exam_minutes') is-invalid @enderror" value="{{$exam->minutes}}" size="5" placeholder="Minute">
                            @error('exam_minutes')
                            <div class="invalid-tooltip" style="margin-left: 15px;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </label>
            </div>

            <div class="large-text-font">
                <b>Exerciții:</b>
                @for($i = 0; $i < $exam->number_of_exercises; $i++)
                    <div id="exercise-{{$i}}">
                        Exercițiul {{$i + 1}}.
                        <br>
                        <label for="exam-exercise-{{$i}}" class="large-text-font">Tipul exercițiului:
                            <select id="exam-exercise-{{$i}}" class="form-control">

                                @if($exam->exercises[$i][0] == "type-1")
                                    <option value="type-1" selected>
                                @else
                                    <option value="type-1">
                                @endif
                                    Determinarea dependețelor în funcție de o relație "r" dată tabelar
                                </option>

                                @if($exam->exercises[$i][0] == "type-2")
                                    <option value="type-2" selected>
                                @else
                                    <option value="type-2">
                                @endif
                                    Determinarea dependețelor în funcție de o relație "Catalog(elev, notă, materie, datăNotare, profesor)" ce impune anumite restricții
                                </option>

                                @if($exam->exercises[$i][0] == "type-3")
                                    <option value="type-3" selected>
                                @else
                                    <option value="type-3">
                                @endif
                                    Determinarea X+ în funcție de o schemă de relație "R" și o mulțime &Sigma; de dependențe funcționale
                                </option>

                                @if($exam->exercises[$i][0] == "type-4")
                                    <option value="type-4" selected>
                                @else
                                    <option value="type-4">
                                @endif
                                    Determinarea cheilor candidat pentru o schemă de relație "R" și mulțimile de dependență &Sigma; și &Delta;
                                </option>

                            </select>
                        </label>

                        <br>
                        <label for="exercise-{{$i}}-points" class="large-text-font">Puncte:
                            <input type="text" class="form-control" id="exercise-{{$i}}-points" placeholder="Puncte" value="{{$exam->exercises[$i][1]}}">
                        </label>
                    </div>
                @endfor

                <div class="extra-exercises">
                    @for($ex = $exam->number_of_exercises; $ex < 100; $ex++)
                        <div hidden id="exercise-{{$ex}}">
                            Exercițiul {{ $ex + 1 }}.
                            <br>
                            <label for="exam-exercise-{{$ex}}" class="large-text-font">Tipul exercitiului:
                                <select id="exam-exercise-{{$ex}}" class="form-control">
                                    <option value="no-exercise">--</option>
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
                                <input type="text" class="form-control" id="exercise-{{$ex}}-points" placeholder="Puncte">
                            </label>
                        </div>
                    @endfor
                </div>
            </div>

            <br>
            <button type="button" class="btn btn-primary" onclick="addOnModifyExercise()">Adăugați încă un exercițiu</button>
            <button type="button" class="btn btn-danger" onclick="removeOnModifyExercise()">Stergeți ultimul exercițiu</button>
            <br>
            <br>
            <label for="exam-minimum" class="large-text-font">Punctajul minim:
                <input type="text" class="form-control" placeholder="Punctaj minim" id="exam-minimum" value="{{$exam->minimum_points}}">
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
                                <input id="points-penalization" type="text" class="form-control exam-penalty-input" value="{{$exam->penalization['body']['points']}}">
                            @else
                                <input id="points-penalization" type="text" class="form-control exam-penalty-input" value="0">
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
                                Minute: &nbsp;<input id="minutes-penalization" type="text" class="form-control exam-penalty-input col" value="{{$exam->penalization['body']['minutes']}}">
                            </label>
                            <div style="width: 10px"></div>
                            <label for="seconds-penalization">
                                Secunde: &nbsp;<input id="seconds-penalization" type="text" class="form-control exam-penalty-input col" value="{{$exam->penalization['body']['seconds']}}">
                            </label>
                        @else
                            <label for="minutes-penalization">
                                Minute: &nbsp;<input id="minutes-penalization" type="text" class="form-control exam-penalty-input col" value="0">
                            </label>
                            <div style="width: 10px"></div>
                            <label for="seconds-penalization">
                                Secunde: &nbsp;<input id="seconds-penalization" type="text" class="form-control exam-penalty-input col" value="0">
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
                                <input id="rule-limit" type="text" class="form-control exam-penalty-input" value="{{$exam->penalization['body']['limit']}}">
                            @else
                                <input id="rule-limit" type="text" class="form-control exam-penalty-input" value="0">
                            @endif

                        </label>
                        &nbsp;ori
                    </div>
                    <label for="rule-warnings" class="check-rule">
                        @if($exam->penalization['type'] == 'limitations' && $exam->penalization['body']['warnings'] == true)
                            <input id="rule-warnings" class="form-check-input warn-penalty-checkbox" type="checkbox" checked>
                        @else
                            <input id="rule-warnings" class="form-check-input warn-penalty-checkbox" type="checkbox">
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
                                        <input id="limit-points-penalization" type="text" class="form-control exam-penalty-input" value="{{$exam->penalization['body']['exceeded']['points']}}">
                                    @else
                                        <input id="limit-points-penalization" type="text" class="form-control exam-penalty-input" value="0">
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
                                        Minute: &nbsp;<input id="limit-minutes-penalization" type="text" class="form-control exam-penalty-input col" value="{{$exam->penalization['body']['exceeded']['minutes']}}">
                                    </label>
                                    <div style="width: 10px"></div>
                                    <label for="limit-seconds-penalization">
                                        Secunde: &nbsp; <input id="limit-seconds-penalization" type="text" class="form-control exam-penalty-input col" value="{{$exam->penalization['body']['exceeded']['seconds']}}">
                                    </label>
                                @else
                                    <label for="limit-minutes-penalization">
                                        Minute: &nbsp;<input id="limit-minutes-penalization" type="text" class="form-control exam-penalty-input col" value="0">
                                    </label>
                                    <div style="width: 10px"></div>
                                    <label for="limit-seconds-penalization">
                                        Secunde: &nbsp; <input id="limit-seconds-penalization" type="text" class="form-control exam-penalty-input col" value="0">
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
                <button type="button" class="btn btn-success btn-lg btn-block" onclick="updateExam({{$exam->id}})">Salvați modificările</button>
            </div>
        </form>

    </div>

    <script type="application/javascript">
        window.onload = function() {
            setNumberOfExercises({{$exam->number_of_exercises}});
        }
    </script>

@endsection

