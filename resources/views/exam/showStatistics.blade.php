@extends('layouts.app')

@section('title')
    <title>Statistici examen</title>
@endsection

@section('content')
    <div class="container my-4">
        <div class="d-flex">
            <div class="mr-auto p-2">
                <h4>
                    <b>Total puncte:</b> {{$exam->total_points}}
                    <br>
                    <b>Minimul de puncte:</b> {{$exam->minimum_points}}
                </h4>
            </div>
            <div class="p-2">
                <h4><b>Data inceperii examenului:</b> {{ date_format(date_create($exam->starts_at), 'd-m-Y') }}, {{ date_format(date_create($exam->starts_at), 'H:i') }}</h4>
                <h4><b>Data incheierii examenului:</b> {{ date_format(date_create($exam->ends_at), 'd-m-Y') }}, {{ date_format(date_create($exam->ends_at), 'H:i') }}</h4>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <h2>{{ $exam->type }} {{ $exam->course_name }}</h2>
        </div>
        <br>

        <div class="d-flex">
            <div class="mr-auto p-2">
                <div class="row">
                    <form class="form-inline col" action="{{route('search_user')}}">
                        <div class="form-group search-user">
                            <label for="search-course"><b>Student:</b></label>
                            <input type="text" name="name" id="search-course" class="form-control mx-sm-4" placeholder="Nume">
                            <button type="submit" class="btn btn-primary">Caută</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="p-2">
                <div class="row">
                    <div class="form-group filter-students-align">
                        <label for="filter-students"><b>Filtreaza:</b></label>
                        <select id="filter-students" class="form-control mx-sm-4" onchange="filterStudentsResult({{$exam->exam_id}})">
                            <option value="no_filter">Niciun filtru</option>
                            <option value="promoted">Promovati</option>
                            <option value="failed">Nepromovati</option>
                            <option value="first_level_lateness">Intarzierea raspunsurilor <= 30 sec.</option>
                            <option value="second_level_lateness">Intarziere raspunsurilor intre 30 sec. - 3 min.</option>
                            <option value="third_level_lateness">Intarziere raspunsurilor peste 3 min.</option>
                        </select>
                        <button onclick="filterStudentsResult({{$exam->exam_id}})">Apasa</button>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <table class="table table-striped">
            <thead class="table-primary">
            <tr>
                <th scope="col">Nume</th>
                <th scope="col">Grupa</th>
                <th scope="col">Ora trimiterii raspunsurilor</th>
                <th scope="col">Intarziere trimitere</th>
                <th scope="col">Numarul penalizarilor</th>
                <th scope="col">Trimiterea raspunsurilor fortata?</th>
                <th scope="col">Rezultat</th>
                <th scope="col">Actiune</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($subjects as $subject)
                @if($subject->obtained_points < $exam->minimum_points)
                    <tr id="unpromoted-red">
                @elseif($subject->time_promoted == 1 && $subject->obtained_points >= $exam->minimum_points)
                    <tr id="promoted-green">
                @else
                    @if($subject->second_diff < 30)
                        <tr id="light-green-submit-time">
                    @elseif($subject->second_diff < 180)
                        <tr id="light-red-submit-time">
                    @else
                        <tr id="red-submit-time">
                    @endif
                @endif
                        <td>{{ $subject->user_name }}</td>
                        <td>{{ $subject->student_group }}</td>
                        <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $subject->submitted_at)->format('H:i:s') }}</td>
                        @if($subject->time_diff > $neutralHour)
                            <td>{{ $subject->time_diff }}</td>
                        @else
                            <td>Nu</td>
                        @endif
                        <td>{{ $subject->penalizations }}</td>
                        <td>
                            @if($subject->forced_submit)
                                Da
                            @else
                                Nu
                            @endif
                        </td>
                        <td>{{ $subject->obtained_points }} / {{$exam->total_points}} puncte</td>
                        <td>
                            @if($subject->time_promoted == 0 &&
                                $subject->obtained_points >= $exam->minimum_points &&
                                $subject->second_diff > 30)
                                <button class="btn btn-sm to-promote-button" data-toggle="modal" data-target="#promoteStudent{{$subject->id}}Modal">Promoveaza</button>
                            @elseif($subject->time_promoted == 1 &&
                                    $subject->obtained_points >= $exam->minimum_points &&
                                    $subject->second_diff > 30)
                                <button class="btn btn-sm to-no-promote-button" data-toggle="modal" data-target="#noPromoteStudent{{$subject->id}}Modal">
                                    Pica <i class="fas fa-info-circle" data-toggle="tooltip" title="Anuleaza actiunea de promovare a acestui student"></i>
                                </button>
                            @endif

                            <div class="modal fade" id="promoteStudent{{$subject->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="promoteStudent{{$subject->id}}ModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="promoteStudent{{$subject->id}}ModalLabel">Confirmare promovare student</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form action="{{route('promote_student', ['examId' => $exam->exam_id, 'userId' => $subject->user_id])}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                Sunteți sigur că doriți să il/o promovati pe {{ $subject->user_name }}?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn to-promote-button">Da</button>
                                                <button type="button" class="btn to-no-promote-button" data-dismiss="modal">Nu</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="noPromoteStudent{{$subject->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="noPromoteStudent{{$subject->id}}ModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="noPromoteStudent{{$subject->id}}ModalLabel">Confirmare anulare promovare student</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form action="{{route('undo_promote_student', ['examId' => $exam->exam_id, 'userId' => $subject->user_id])}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                Sunteți sigur că doriți să anulati promovarea pentru {{ $subject->user_name }}?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn to-no-promote-button">Da</button>
                                                <button type="button" class="btn to-promote-button" data-dismiss="modal">Nu</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>

        <div style="text-align: center;">
            {{ $subjects->onEachSide(1)->links() }}
        </div>

        <b>Legenda:</b>
        <div class="d-flex flex-row">
            <div class="p-2"><div id="promoted-green" class="exam-legend-rectangular"></div></div>
            <div class="p-2">Promovat</div>
        </div>
        <div class="d-flex flex-row">
            <div class="p-2"><div id="light-green-submit-time" class="exam-legend-rectangular"></div></div>
            <div class="p-2">Promovat dpdv. al punctelor, dar trimitere intarziata a raspunsurilor (intarziere <= 30 sec.)</div>
        </div>
        <div class="d-flex flex-row">
            <div class="p-2"><div id="light-red-submit-time" class="exam-legend-rectangular"></div></div>
            <div class="p-2">Promovat dpdv. al punctelor, dar nepromovat dpdv. al trimiterii raspunsurilor (intarziere 30 sec. - 3 min.)</div>
        </div>
        <div class="d-flex flex-row">
            <div class="p-2"><div id="red-submit-time" class="exam-legend-rectangular"></div></div>
            <div class="p-2">Promovat dpdv. al punctelor, dar nepromovat dpdv. al trimiterii raspunsurilor (intarziere peste 3 min.)</div>
        </div>
        <div class="d-flex flex-row">
            <div class="p-2"><div id="unpromoted-red" class="exam-legend-rectangular"></div></div>
            <div class="p-2">Nepromovat</div>
        </div>

    </div>
@endsection