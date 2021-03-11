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
            <button type="button" class="btn btn-primary add-course-button" onclick="window.location='{{route('prepare_new_course')}}'">Adaugă curs</button>
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
                            <button type="button" class="btn btn-danger btn-sm"  data-toggle="modal" data-target="#deleteTeacherFromCourse{{$course->id}}Modal">Șterge profesor</button>

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


                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
@endsection
