@extends('layouts.app')

@section('title')
    <title>Cursuri</title>
@endsection

@section('content')
    <div class="container my-4">
        <div class="row">
            <form class="form-inline col" action="{{route('search_course')}}">
                <div class="form-group search-user">
                    <label for="search-course"><b>Curs:</b></label>
                    <input type="text" name="name" id="search-course" class="form-control mx-sm-4" placeholder="Nume">
                    <button type="submit" class="btn btn-primary">Caută</button>
                </div>
            </form>
            <button type="button" class="btn btn-primary add-course-button" data-toggle="modal" data-target="#addCourseModal">Adaugă curs</button>
        </div>

        <div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCourseModalLabel">Adaugă un nou curs</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="POST">
                        @csrf

                        <br>
                        <div class="form-group row">
                            <label for="course_name" class="col-md-4 col-form-label text-md-right">{{ __('Denumirea cursului') }}</label>
                            <div class="col-md-6">
                                <input id="course_name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Denumire">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="course-teachers" class="col-md-4 col-form-label text-md-right">{{ __('Profesorii cursului') }}</label>
                            <div class="col-md-6">
                                <select id="course-teachers" class="form-control" onchange="addTeacherToUList();">
                                    <option selected value="no-teacher">Adăugați</option>
                                    @foreach($allTeachers as $teach)
                                        <option value="{{ $teach->name }}">{{ $teach->name }}</option>
                                    @endforeach
                                </select>
                                <ul id="selected-teachers" class="form-group list-group list-group-horizontal text-center" style="margin-top: 10px;">

                                </ul>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="course-year" class="col-md-4 col-form-label text-md-right">{{ __('Anul') }}</label>
                            <div class="col-md-6">
                                <select id="course-year" class="form-control col-3">
                                    <option value="">--</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="course-semester" class="col-md-4 col-form-label text-md-right">{{ __('Semestrul') }}</label>
                            <div class="col-md-6">
                                <select id="course-semester" class="form-control col-3">
                                    <option value="">--</option>
                                    <option value="1">I</option>
                                    <option value="2">II</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="course-credits" class="col-md-4 col-form-label text-md-right">{{ __('Numărul de credite') }}</label>
                            <div class="col-md-6">
                                <select id="course-credits" class="form-control col-3">
                                    <option value="">--</option>
                                    @for($i = 1; $i < 9; $i++)
                                        <option value="{{$i}}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-success" type="button" onclick="addCourse();">Adaugă</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Anulează</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <br>


        <table class="table table-striped">
            <thead class="table-primary">
            <tr>
                <th scope="col">Nume</th>
                <th scope="col">An</th>
                <th scope="col">Semestru</th>
                <th scope="col">Numărul de credite</th>
                <th scope="col">Catedra</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @if(count($courses) == 0)
                <tr>
                    <td colspan="6"><p><b>Niciun curs nu se potrivește căutării.</b></p></td>
                </tr>
            @else
                @foreach ($courses as $course)
                    <tr>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->year }}</td>
                        <td>{{ $course->semester }}</td>
                        <td>{{ $course->credits }}</td><td>
                            @foreach($teachers[$course->id] as $teacher)
                                @if($teacher == $teachers[$course->id][count($teachers[$course->id]) - 1])
                                    {{ $teacher->name }}
                                @else
                                    {{ $teacher->name }},
                                @endif
                            @endforeach
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addTeacherToCourse{{$course->id}}Modal">Adaugă profesor</button>
                            <button type="button" class="btn btn-warning btn-sm"  data-toggle="modal" data-target="#deleteTeacherFromCourse{{$course->id}}Modal">Șterge profesor</button>
                            <button type="button" class="btn btn-danger btn-sm"  data-toggle="modal" data-target="#deleteCourse{{$course->id}}Modal">Șterge curs</button>


                            <div class="modal fade" id="addTeacherToCourse{{$course->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="addTeacherToCourse{{$course->id}}ModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addTeacherToCourse{{$course->id}}ModalLabel">Adaugă profesor</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('add_teacher_to_course') }}" method="POST" >
                                        @csrf
                                            <div class="modal-body">
                                                Adăugați un profesor la cursul <b>{{ $course->name }}</b>
                                                <br>
                                                <label for="teacher-to-add">Profesor:
                                                    <select id="teacher-to-add" class="form-control" name="teacherToAdd">
                                                        <option value="0">--</option>
                                                        @if(count($noTeachers[$course->id]) > 0)
                                                            @foreach($noTeachers[$course->id] as $noTeacher)
                                                                <option value="{{ $noTeacher->id }}">{{ $noTeacher->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </label>
                                                <input hidden name="courseId" value="{{$course->id}}">
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-success" type="submit">Adaugă</button>

                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Anulează</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                            <div class="modal fade" id="deleteTeacherFromCourse{{$course->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="deleteTeacherFromCourse{{$course->id}}ModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteTeacherFromCourse{{$course->id}}ModalLabel">Șterge profesor</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('delete_teacher_from_course') }}" method="POST" >
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                Ștergeți un profesor de la cursul <b>{{ $course->name }}</b>
                                                <br>
                                                <label for="teacher-to-delete">Profesor:
                                                    <select id="teacher-to-delete" class="form-control" name="teacherToDelete">
                                                        <option value="0">--</option>
                                                        @foreach($teachers[$course->id] as $teacher)
                                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                        @endforeach
                                                    </select>

                                                </label>
                                                <input hidden name="courseId" value="{{$course->id}}">
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-danger" type="submit">Șterge</button>

                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Anulează</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="deleteCourse{{$course->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="deleteCourse{{$course->id}}ModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteCourse{{$course->id}}ModalLabel">Confirmare ștergere curs</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('delete_course', ['id' => $course->id]) }}" method="POST" >
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                Sunteți sigur că doriți să ștergeți cursul <b>{{ $course->name }}</b>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-danger" type="submit">Da</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Nu</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
@endsection
