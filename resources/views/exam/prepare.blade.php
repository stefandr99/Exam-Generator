@extends('layouts.app')

@section('title')
    <title>Pregatire examen</title>
@endsection

@section('content')
    <div class="container my-4">
        <h1 class="text-center"><b>Pregătiți examenul</b></h1>
        <br>
        <form class="form-group">
            <label for="exam-subject" class="dependencies-options">Materia:
                <select id="exam-subject" class="form-control">
                    <option value="no-subject">--</option>
                    <option value="Baze de date">Baze de date</option>
                    <option value="Proiectarea algoritmilor">Proiectarea algoritmilor</option>
                    <option value="Rețele de calculatoare">Rețele de calculatoare</option>
                    <option value="Programare avansată">Programare avansată</option>
                </select>
            </label>
            <br>

            <label for="exam-type" class="dependencies-options">Tipul examenului:
                <select id="exam-type" class="form-control">
                    <option value="no-type">--</option>
                    <option value="Parțial">Parțial</option>
                    <option value="Examen">Examen</option>
                    <option value="Restanță">Restanță</option>
                </select>
            </label>
            <br>

            <label for="exam-date" class="dependencies-options">
                Data și ora examenului:
                <input class="form-control" type="datetime-local" value="2021-02-18T08:00:00" id="exam-date">
            </label>
            <br>

            <label for="exam-duration" class="dependencies-options">Durata examenului:
                <div class="row">
                    <div class="col-3">
                        <input type="text" class="form-control" placeholder="Ore" id="exam-duration-hours">
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" placeholder="Minute" id="exam-duration-minutes">
                    </div>
                </div>
            </label>
            <br>

            <div class="dependencies-options">
                <b>Exerciții:</b>
                <br>
                Exercițiul 1.
                <br>
                <label for="exam-exercise-0" class="dependencies-options">Tipul exercițiului:
                    <select id="exam-exercise-0" class="form-control">
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
                <label for="exercise-0-points" class="dependencies-options">Puncte:
                    <input type="text" class="form-control" id="exercise-0-points" placeholder="Puncte">
                </label>

                <div class="extra-exercises">
                    @for($ex = 1; $ex < 100; $ex++)
                        <div hidden id="exercise-{{$ex}}">
                            Exercițiul {{ $ex + 1 }}.
                            <br>
                            <label for="exam-exercise-{{$ex}}" class="dependencies-options">Tipul exercitiului:
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
                            <label for="exercise-{{$ex}}-points" class="dependencies-options">Puncte:
                                <input type="text" class="form-control" id="exercise-{{$ex}}-points" placeholder="Puncte">
                            </label>
                        </div>
                    @endfor
                </div>
            </div>

            <br>
            <button type="button" class="btn btn-primary" onclick="addExercise()">Adăugați încă un exercițiu</button>
            <button type="button" class="btn btn-danger" onclick="removeExercise()">Stergeți ultimul exercițiu</button>
            <br>
            <br>
            <label for="exam-minimum" class="dependencies-options">Punctajul minim:
                <input type="text" class="form-control" placeholder="Punctaj minim" id="exam-minimum">
            </label>
            <br>
            <br>
            <p class="dependencies-options">Penalizare:
                <br>
                <small>
                    <b>INFO: </b>Aplicați penalizarea <b>"focus on exam"</b> pentru studenți. În timpul examenului dacă un student nu mai are în
                    prim plan subiectul examenului (pagina web a aplicației), acestuia i se va aplica una din urmatoarele sancțiuni
                    (<b>Important:</b> fiecare penalizare se va executa per greșeala):
                </small>
            </p>

            <label>
                <input id="examPenalty1" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty1" aria-expanded="false" aria-controls="collapsePenalty1" onclick="onRadioCollapse();">
            </label>
            Depunctare
            <div class="collapse" id="collapsePenalty1">
                <div class="card card-body" style="width: 8rem; height: 4.4rem">
                    <div class="row">
                    Puncte: &nbsp;
                    <label for="pointsPenalty" class="dependencies-options">
                        <input type="text" class="form-control exam-penalty-input" value="0" id="exam-minimum">
                    </label>
                    </div>
                </div>
            </div>
            <br>
            <label>
                <input id="examPenalty2" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty2" aria-expanded="false" aria-controls="collapsePenalty2" onclick="onRadioCollapse();">
            </label>
            Scăderea din timpul rămas
            <div class="collapse" id="collapsePenalty2">
                <div class="card card-body" style="width: 9rem;">
                    <div class="row">
                        <label for="pointsPenalty">
                            Minute: &nbsp;<input type="text" class="form-control exam-penalty-input col" value="0" id="exam-minimum">
                        </label>
                        <div style="width: 10px"></div>
                        <label for="pointsPenalty">
                            Secunde: &nbsp; <input type="text" class="form-control exam-penalty-input col" value="0" id="exam-minimum">
                        </label>
                    </div>
                </div>
            </div>
            <br>
            <label>
                <input id="examPenalty3" name="examPenalty" type="radio" data-toggle="collapse" data-target="#collapsePenalty3" aria-expanded="false" aria-controls="collapsePenalty3" onclick="onRadioCollapse();">
            </label>
            Permite încalcarea regulii cu limită
            <div class="collapse" id="collapsePenalty3">
                <div class="card card-body" style="width: 14rem;">
                    <div class="row">
                        De maxim: &nbsp;
                        <label for="pointsPenalty" class="dependencies-options">
                            <input type="text" class="form-control exam-penalty-input" value="0" id="exam-minimum">
                        </label>
                        &nbsp;ori
                    </div>
                    <label for="enableRule" class="check-rule">
                        <input id="enableRule" class="form-check-input warn-penalty-checkbox" type="checkbox">
                        &nbsp;<small>avertizează la fiecare abatere</small>
                    </label>
                    Sancțiune la depașirea limitei:
                </div>
            </div>
            <br>

            <label>
                <input id="examPenalty4" name="examPenalty" type="radio" onclick="onRadioCollapse();">
            </label>
            Sfarșește examenul studentului în cauză
            <br>

            <label>
                <input id="examPenalty5" name="examPenalty" type="radio" onclick="onRadioCollapse();">
            </label>
            Fără penalizare
            <br>

            <div class="r_relationship">
                <button type="button" class="btn btn-success btn-lg btn-block" onclick="scheduleExam()">Programați examenul</button>
            </div>
        </form>

    </div>

@endsection

