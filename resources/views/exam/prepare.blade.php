@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <h1 id="partial-title"><b>Pregătiți examenul</b></h1>
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
                Exercițiul 0.
                <br>
                <label for="exam-exercise-0" class="dependencies-options">Tipul exercițiului:
                    <select id="exam-exercise-0" class="form-control" style="text-overflow: ellipsis; overflow: hidden">
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
                            Exercițiul {{ $ex }}.
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
            <div class="r_relationship">
                <button type="button" class="btn btn-success btn-lg btn-block" onclick="scheduleExam()">Programați examenul</button>
            </div>
        </form>

    </div>

@endsection

