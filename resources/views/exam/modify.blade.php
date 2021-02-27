@extends('layouts.app')

@section('title')
    <title>Modificare examen</title>
@endsection

@section('content')
    <div class="container my-4">
        <h1 id="partial-title"><b>Modificați examenul</b></h1>
        <br>
        <form class="form-group">
            <label for="exam-subject" class="dependencies-options">Materia:
                <select id="exam-subject" class="form-control">
                    @foreach(array('Baze de date', 'Proiectarea algoritmilor', 'Rețele de calculatoare', 'Programare avansată') as $subject)
                        @if($exam->course_name == $subject)
                            <option value="{{$subject}}" selected>{{$subject}}</option>
                        @else
                            <option value="{{$subject}}">{{$subject}}</option>
                        @endif
                    @endforeach
                </select>
            </label>
            <br>

            <label for="exam-type" class="dependencies-options">Tipul examenului:
                <select id="exam-type" class="form-control">
                    @foreach(array("Parțial", "Examen", "Restanță") as $examType)
                        @if($examType == $exam->type)
                            <option value="{{$examType}}" selected>{{$examType}}</option>
                        @else
                            <option value="{{$examType}}">{{$examType}}</option>
                        @endif
                    @endforeach
                </select>
            </label>
            <br>

            <label for="exam-date" class="dependencies-options">
                Data și ora examenului:
                <input class="form-control" type="datetime-local" value="{{explode(" ", $exam->date)[0] . "T" . explode(" ", $exam->date)[1]}}" id="exam-date">
            </label>
            <br>

            <label for="exam-duration" class="dependencies-options">Durata examenului:
                <div class="row">
                    <div class="col-3">
                        <input type="text" class="form-control" placeholder="Ore" id="exam-duration-hours" value="{{$exam->hours}}">
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" placeholder="Minute" id="exam-duration-minutes" value="{{$exam->minutes}}">
                    </div>
                </div>
            </label>
            <br>

            <div class="dependencies-options">
                <b>Exerciții:</b>
                @for($i = 0; $i < $exam->number_of_exercises; $i++)
                    <div id="exercise-{{$i}}">
                        Exercițiul {{$i + 1}}.
                        <br>
                        <label for="exam-exercise-{{$i}}" class="dependencies-options">Tipul exercițiului:
                            <select id="exam-exercise-{{$i}}" class="form-control">

                                @if($exam->exercises_type[$i][0] == "type-1")
                                    <option value="type-1" selected>
                                @else
                                    <option value="type-1">
                                @endif
                                    Determinarea dependețelor în funcție de o relație "r" dată tabelar
                                </option>

                                @if($exam->exercises_type[$i][0] == "type-2")
                                    <option value="type-2" selected>
                                @else
                                    <option value="type-2">
                                @endif
                                    Determinarea dependețelor în funcție de o relație "Catalog(elev, notă, materie, datăNotare, profesor)" ce impune anumite restricții
                                </option>

                                @if($exam->exercises_type[$i][0] == "type-3")
                                    <option value="type-3" selected>
                                @else
                                    <option value="type-3">
                                @endif
                                    Determinarea X+ în funcție de o schemă de relație "R" și o mulțime &Sigma; de dependențe funcționale
                                </option>

                                @if($exam->exercises_type[$i][0] == "type-4")
                                    <option value="type-4" selected>
                                @else
                                    <option value="type-4">
                                @endif
                                    Determinarea cheilor candidat pentru o schemă de relație "R" și mulțimile de dependență &Sigma; și &Delta;
                                </option>

                            </select>
                        </label>

                        <br>
                        <label for="exercise-{{$i}}-points" class="dependencies-options">Puncte:
                            <input type="text" class="form-control" id="exercise-{{$i}}-points" placeholder="Puncte" value="{{$exam->exercises_type[$i][1]}}">
                        </label>
                    </div>
                @endfor

                <div class="extra-exercises">
                    @for($ex = $exam->number_of_exercises; $ex < 100; $ex++)
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
            <button type="button" class="btn btn-primary" onclick="addModifyExercise({{$exam->number_of_exercises}})">Adăugați încă un exercițiu</button>
            <button type="button" class="btn btn-danger" onclick="removeModifyExercise({{$exam->number_of_exercises}})">Stergeți ultimul exercițiu</button>
            <br>
            <br>
            <label for="exam-minimum" class="dependencies-options">Punctajul minim:
                <input type="text" class="form-control" placeholder="Punctaj minim" id="exam-minimum" value="{{$exam->minimum_points}}">
            </label>
            <br>
            <br>
            <div class="r_relationship">
                <button type="button" class="btn btn-success btn-lg btn-block" onclick="updateExam({{$exam->id}})">Salvați modificările</button>
            </div>
        </form>

    </div>

@endsection

