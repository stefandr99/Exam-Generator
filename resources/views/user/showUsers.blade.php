@extends('layouts.app')

@section('title')
    <title>Utilizatori</title>
@endsection

@section('content')
    <div class="container my-4">
        <div class="row">
            <form class="form-inline col" action="{{route('search_user')}}">
                <div class="form-group search-user">
                    <label for="search-user"><b>Utilizator:</b></label>
                    <input type="text" name="search" id="search-user" class="form-control mx-sm-4" placeholder="Informație">

                    <label for="search-user-by"><b>După:</b></label>
                    <select id="search-user-by" name="criteria" class="form-control mx-sm-4">
                        <option value="name">Nume</option>
                        <option value="registration_number">Număr matricol</option>
                        <option value="year&group">An de studiu & grupă</option>
                        <option value="year">An de studiu</option>
                        <option value="group">Grupă</option>
                    </select>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Caută</button>
                </div>
            </form>
        </div>
        <br>
        @if (Auth::user()->role == 1)
            <div class="row">
                <form class="form-inline col" action="{{route('register_bulk_users')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="form-group upload-users-div">
                        <label for="user-upload"><b>Încarcă utilizatori:</b></label>
                        <input type="file" id="user-upload" name="user-upload" class="btn btn-primary form-control mx-sm-4 input-users" accept=".xlsx, .xls, .csv">
                        <button type="submit" class="btn btn-primary">Încarcă</button>
                    </div>
                </form>
                <button type="button" class="btn btn-primary add-user-button"  data-toggle="modal" data-target="#addUserModal">Adaugă un utilizator</button>
                <button type="button" class="btn btn-primary next-semester"  data-toggle="modal" data-target="#nextSemesterModal">
                    @if($users[1]->semester == 1)
                        Treceti in semestrul urmator <i class="fas fa-angle-double-right"></i>
                    @else
                        Treceti in anul urmator <i class="fas fa-angle-double-right"></i>
                    @endif
                </button>

            </div>
        @endif
        <br>

        <table class="table table-striped">
            <thead class="table-primary">
            <tr>
                <th scope="col">Nume</th>
                <th scope="col">Număr matricol</th>
                <th scope="col">An</th>
                <th scope="col">Semestru</th>
                <th scope="col">Grupă</th>
                <th scope="col">Email</th>
                <th scope="col">Rol</th>
            </tr>
            </thead>
            <tbody>
            @if(count($users) == 0)
                <tr>
                    <td colspan="6"><p><b>Niciun utilizator nu se potrivește căutării.</b></p></td>
                </tr>
            @else
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->registration_number }}</td>
                        <td>{{ $user->year }}</td>
                        <td>{{ $user->semester }}</td>
                        <td>{{ $user->group }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->role == 1)
                                <span class="btn btn-danger" style="pointer-events: none">Administrator</span>
                            @endif
                            @if ($user->role == 2)
                                <div class="dropdown show">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Profesor
                                    </button>
                                    <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteUser{{$user->id}}Modal">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-role">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mkStudent{{$user->id}}Modal">
                                            Student
                                        </button>
                                    </div>
                                </div>

                                <div class="modal fade" id="mkStudent{{$user->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="mkStudent{{$user->id}}ModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="mkStudent{{$user->id}}ModalLabel">Confirmare schimbare de rol</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <form action="{{route('update_role', ['id' => $user->id, 'newRole' => 3])}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    Sunteți sigur că doriți să îl/o faceți STUDENT pe {{ $user->name }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Da</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Nu</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($user->role == 3)
                                <div class="dropdown show">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Student
                                    </button>
                                    <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteUser{{$user->id}}Modal">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <div class="dropdown-menu  dropdown-role" style="min-width: 80px; border: none">
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#mkTeacher{{$user->id}}Modal">
                                            Profesor
                                        </button>
                                    </div>
                                </div>

                                <div class="modal fade" id="mkTeacher{{$user->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="mkTeacher{{$user->id}}eModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="mkTeacher{{$user->id}}ModalLabel">Confirmare schimbare de rol</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('update_role', ['id' => $user->id, 'newRole' => 2])}}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    Sunteți sigur că doriți să îl/o faceți PROFESOR pe {{ $user->name }}?
                                                    <label for="mkTeacherCourse">Curs:
                                                        <select id="course-id-{{$user->id}}" name="courseId" class="form-control">
                                                            <option value="no-type">--</option>
                                                            @foreach($courses as $course)
                                                                <option value="{{$course->id}}">{{ $course->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Da</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Nu</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @endif

                            <div class="modal fade" id="deleteUser{{$user->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="deleteUser{{$user->id}}ModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteUser{{$user->id}}ModalLabel">Confirmare stergere utilizator</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form action="{{ route('delete_user') }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                Sunteți sigur că doriți să îl/o stergeti pe {{ $user->name }}?
                                                <label>
                                                    <input hidden name="userId" value="{{$user->id}}">
                                                </label>
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

        <!-- MODALS -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Adaugă un utilizator</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('register_by_admin') }}">
                        @csrf

                        <br>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nume') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="registration_number" class="col-md-4 col-form-label text-md-right">{{ __('Număr matricol') }}</label>

                            <div class="col-md-6">
                                <input id="registration_number" type="text" class="form-control @error('registration_number') is-invalid @enderror" name="registration_number" value="{{ old('registration_number') }}" autocomplete="1234" autofocus>

                                @error('registration_number')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="year" class="col-md-4 col-form-label text-md-right">{{ __('An') }}</label>

                            <div class="col-md-6">
                                <input id="year" type="text" class="form-control @error('year') is-invalid @enderror" name="year" value="{{ old('year') }}" autocomplete="1" autofocus>

                                @error('year')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="group" class="col-md-4 col-form-label text-md-right">{{ __('Grupa') }}</label>

                            <div class="col-md-6">
                                <input id="group" type="text" class="form-control @error('group') is-invalid @enderror" name="group" value="{{ old('group') }}" autocomplete="A1" autofocus>

                                @error('group')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Adresa de E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Parola') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmare Parolă') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-success" type="submit">Adaugă</button>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Anulează</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade" id="nextSemesterModal" tabindex="-1" role="dialog" aria-labelledby="nextSemesterModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        @if($users[1]->semester == 1)
                            <h5 class="modal-title" id="nextSemesterModalLabel">Confirmare trecere in semestrul urmator</h5>
                        @else
                            <h5 class="modal-title" id="nextSemesterModalLabel">Confirmare trecere in anul urmator</h5>
                        @endif
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{route('next_semester', ['semester' => $users[1]->semester])}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            @if($users[1]->semester == 1)
                                Sunteți sigur că doriți să treceti in semestrul urmator?
                            @else
                                Sunteți sigur că doriți să treceti in anul urmator?
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Da</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Nu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        @if(count($errors) > 0)
            <script>
                $(document).ready(function(){
                    $("#addUserModal").modal();
                });
            </script>
        @endif
    </div>

@endsection
