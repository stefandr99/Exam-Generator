@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <h1 id="partial-title"><b>Pregatiti examenul</b></h1>
        <br>
        <form class="form-group">
            <label for="exam-subject" class="dependencies-options">Materia:
                <select id="exam-subject" class="form-control">
                    <option value="no-subject">--</option>
                    <option value="Baze de date">Baze de date</option>
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
                Data si ora examenului:
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
                <b>Exercitii:</b>
                <br>
                Exercitiul 0.
                <br>
                <label for="exam-exercise-0" class="dependencies-options">Tipul exercitiului:
                    <select id="exam-exercise-0" class="form-control" style="text-overflow: ellipsis; overflow: hidden">
                        <option value="no-exercise">--</option>
                        <option value="exercise-type-1">
                            Determinarea dependetelor in functie de o relatie "r" data tabelar
                        </option>
                        <option value="exercise-type-2">
                            Determinarea dependetelor in functie de o relatie "Catalog(elev, nota, materie, dataNotare, profesor)" ce impune anumite restrictii
                        </option>
                        <option value="exercise-type-3">
                            Determinarea X+ in functie de o schema de relatie "R" si o multime &Sigma; de dependente functionale
                        </option>
                        <option value="exercise-type-4">
                            Determinarea cheilor candidat pentru o schema de relatie "R" si multimile de dependenta &Sigma; si &Delta;
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
                            Exercitiul {{ $ex }}.
                            <br>
                            <label for="exam-exercise-{{$ex}}" class="dependencies-options">Tipul exercitiului:
                                <select id="exam-exercise-{{$ex}}" class="form-control">
                                    <option value="no-exercise">--</option>
                                    <option value="exercise-type-1">
                                        Determinarea dependetelor in functie de o relatie "r" data tabelar
                                    </option>
                                    <option value="exercise-type-2">
                                        Determinarea dependetelor in functie de o relatie "Catalog(elev, nota, materie, dataNotare, profesor)" ce impune anumite restrictii
                                    </option>
                                    <option value="exercise-type-3">
                                        Determinarea X+ in functie de o schema de relatie "R" si o multime &Sigma; de dependente functionale
                                    </option>
                                    <option value="exercise-type-4">
                                        Determinarea cheilor candidat pentru o schema de relatie "R" si multimile de dependenta &Sigma; si &Delta;
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
            <button type="button" class="btn btn-primary" onclick="addExercise()">Adaugati inca un exercitiu</button>
            <button type="button" class="btn btn-danger" onclick="removeExercise()">Stergeti ultimul exercitiu</button>
            <br>
            <br>
            <div class="r_relationship">
                <button type="button" class="btn btn-success btn-lg btn-block" onclick="scheduleExam()">Programati examenul</button>
            </div>
        </form>

    </div>

@endsection

