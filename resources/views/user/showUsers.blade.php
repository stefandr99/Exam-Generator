@extends('layouts.app')

@section('title')
    <title>Utilizatori</title>
@endsection

@section('content')
    <div class="container my-4">

        <form class="form-inline" action="{{route('search_user')}}">
            <div class="form-group search-user">
                <label for="search-user"><b>Utilizator:</b></label>
                <input type="text" name="name" id="search-user" class="form-control mx-sm-4" placeholder="Nume">
                <button type="submit" class="btn btn-primary">Caută</button>
            </div>
        </form>
        <br>

        <table class="table table-striped">
            <thead class="table-primary">
            <tr>
                <th scope="col">Nume</th>
                <th scope="col">Număr matricol</th>
                <th scope="col">An</th>
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
                                            <div class="modal-body">
                                                Sunteți sigur că doriți să îl faceți STUDENT pe {{ $user->name }}?
                                            </div>
                                            <div class="modal-footer">
                                                <a class="btn btn-primary" href="{{ route('update_role', ['id' => $user->id, 'newRole' => 3]) }}" onclick="event.preventDefault(); document.getElementById('mkStudent{{$user->id}}').submit();">Da</a>
                                                <form id="mkStudent{{$user->id}}" action="{{ route('update_role', ['id' => $user->id, 'newRole' => 3]) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('PUT')
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Nu</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($user->role == 3)
                                <div class="dropdown show">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Student
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
                                                <div class="modal-body">
                                                    Sunteți sigur că doriți să îl faceți PROFESOR pe {{ $user->name }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <a class="btn btn-primary" href="{{ route('update_role', ['id' => $user->id, 'newRole' => 2]) }}" onclick="event.preventDefault(); document.getElementById('mkTeacher{{$user->id}}').submit();">Da</a>
                                                    <form id="mkTeacher{{$user->id}}" action="{{ route('update_role', ['id' => $user->id, 'newRole' => 2]) }}" method="POST" class="hidden">
                                                        @csrf
                                                        @method('PUT')
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Nu</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
@endsection
