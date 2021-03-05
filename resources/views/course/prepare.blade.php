@extends('layouts.app')

@section('title')
    <title>Adauga curs</title>
@endsection

@section('content')
    <div class="container my-4">
        <h1 class="text-center"><b>Adaugați un nou curs</b></h1>
        <br>
        <form class="form-group">
            <label for="course-name" class="dependencies-options">Numele cursului:
                <input type="text" class="form-control" id="course-name" placeholder="Nume">
            </label>
            <br>


            <label for="course-teachers" class="dependencies-options">Profesorii cursului:
                <select id="course-teachers" class="form-control" onchange="addToList();">
                    <option value="no-teacher">--</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->name }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </label>
            <br>
            <ul id="selected-teachers" class="list-group list-group-horizontal">
            </ul>

            <div class="row">
                <label for="course-year" class="col-1 dependencies-options">Anul:
                        <select id="course-year" class="form-control">
                            <option value="no-type">--</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                </label>
                <br>
                <label for="course-semester" class="col-1 dependencies-options">Semestrul:
                    <select id="course-semester" class="form-control">
                        <option value="no-type">--</option>
                        <option value="1">I</option>
                        <option value="2">II</option>
                    </select>
                </label>
                <br>
            </div>

            <label for="course-credits" class="dependencies-options">Numărul de credite:
                <select id="course-credits" class="form-control">
                    <option value="no-type">--</option>
                    @for($i = 1; $i < 9; $i++)
                        <option value="{{$i}}">{{ $i }}</option>
                    @endfor
                </select>
            </label>

            <br>
            <br>
                <button type="button" class="btn btn-success btn-lg btn-lg" onclick="addCourse()">Adaugați cursul</button>

        </form>
    </div>

@endsection

