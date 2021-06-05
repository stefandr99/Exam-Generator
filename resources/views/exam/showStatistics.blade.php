@extends('layouts.app')

@section('title')
    <title>Statistici examen</title>
@endsection

@section('content')
    <div class="container my-4">
        <div class="d-flex">
            <div class="p-2">
                <div class="card">
                    <div class="card-body">
                        <h4>
                            <b>Total puncte:</b> {{$exam->total_points}}
                            <br>
                            <b>Minimul de puncte:</b> {{$exam->minimum_points}}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="text-center m-auto">
                <h2><b>{{ $exam->type }} {{ $exam->course_name }}</b></h2>
            </div>

            <div class="p-2">
                <div class="card">
                    <div class="card-body">
                        <h4><i class="fas fa-hourglass-start"></i> {{ date_format(date_create($exam->starts_at), 'd-m-Y') }} <i class="far fa-clock"></i> {{ date_format(date_create($exam->starts_at), 'H:i') }}</h4>
                        <h4><i class="fas fa-hourglass-end"></i> {{ date_format(date_create($exam->ends_at), 'd-m-Y') }} <i class="fas fa-clock"></i> {{ date_format(date_create($exam->ends_at), 'H:i') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row float-left mx-auto">
            <div class="card rounded">
                <div class="card-body" style="padding: 10px;">
                    <form class="form-inline col" action="{{route('search_user_from_exam_stats')}}" style="padding: 0">
                        <div class="form-group search-subject">
                            <input name="exam" value="{{$exam->exam_id}}" hidden>
                            <label for="search-exam-subject"><b>Student:</b></label>
                            <input type="text" name="name" id="search-exam-subject" class="form-control mx-sm-4" placeholder="Nume">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Caută</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row float-right mx-auto">
            <div class="card rounded">
                <div class="card-body" style="padding: 10px;">
                    <form class="form-inline col" action="{{route('filter_exam_stats')}}" style="padding: 0">
                        <div class="form-group">
                            <input name="exam" value="{{$exam->exam_id}}" hidden>
                            <label for="filter_students"><i class="fas fa-filter" style="font-size: 25px;"></i></label>
                            <select id="filter_students" name="filter" class="form-control mx-sm-4" onchange="this.form.submit()">
                                @if($filter == 'all')
                                <option value="all" selected>Toți</option>
                                @else
                                    <option value="all">Toți</option>
                                @endif
                                @if($filter == 'promoted')
                                <option value="promoted" selected>Promovați</option>
                                @else
                                    <option value="promoted">Promovați</option>
                                @endif
                                @if($filter == 'failed')
                                <option value="failed" selected>Nepromovați</option>
                                @else
                                    <option value="failed">Nepromovați</option>
                                @endif
                                @if($filter == 'first_level_lateness')
                                <option value="first_level_lateness" selected>Întârzierea răspunsurilor <= 30 sec.</option>
                                @else
                                    <option value="first_level_lateness">Întârzierea răspunsurilor <= 30 sec.</option>
                                @endif
                                @if($filter == 'second_level_lateness')
                                <option value="second_level_lateness" selected>Întârzierea răspunsurilor intre 30 sec. - 3 min.</option>
                                @else
                                    <option value="second_level_lateness">Întârzierea răspunsurilor intre 30 sec. - 3 min.</option>
                                @endif
                                @if($filter == 'third_level_lateness')
                                <option value="third_level_lateness" selected>Întârzierea răspunsurilor peste 3 min.</option>
                                @else
                                    <option value="third_level_lateness">Întârzierea răspunsurilor peste 3 min.</option>
                                @endif
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <br>
        <br>

        <table class="table table-striped">
            <thead class="table-primary">
            <tr>
                <th scope="col">Nume</th>
                <th scope="col">Grupa</th>
                <th scope="col">Ora trimiterii răspunsurilor</th>
                <th scope="col">Întârziere trimitere</th>
                <th scope="col">Numărul penalizărilor</th>
                <th scope="col">Trimiterea răspunsurilor forțată?</th>
                <th scope="col">Rezultat</th>
                <th scope="col">Acțiune</th>
                <th scope="col"></th>
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
                           <td onclick="window.location='{{ route("show_exam_result", ['examId' => $exam->exam_id, 'userId' => $subject->user_id]) }}'" style="cursor: pointer"><i class="far fa-file-alt fa-3x"></i></td>
                    </tr>
            @endforeach
            </tbody>
        </table>

        <div style="text-align: center;">
            {{ $subjects->onEachSide(1)->links() }}
        </div>

        <b>Legendă:</b>
        <div class="d-flex flex-row">
            <div class="p-2"><div id="promoted-green" class="exam-legend-rectangular"></div></div>
            <div class="p-2">Promovat</div>
        </div>
        <div class="d-flex flex-row">
            <div class="p-2"><div id="light-green-submit-time" class="exam-legend-rectangular"></div></div>
            <div class="p-2">Promovat dpdv. al punctelor, dar trimitere întârziată a răspunsurilor (întârziere <= 30 sec.)</div>
        </div>
        <div class="d-flex flex-row">
            <div class="p-2"><div id="light-red-submit-time" class="exam-legend-rectangular"></div></div>
            <div class="p-2">Promovat dpdv. al punctelor, dar nepromovat dpdv. al trimiterii răspunsurilor (întârziere 30 sec. - 3 min.)</div>
        </div>
        <div class="d-flex flex-row">
            <div class="p-2"><div id="red-submit-time" class="exam-legend-rectangular"></div></div>
            <div class="p-2">Promovat dpdv. al punctelor, dar nepromovat dpdv. al trimiterii răspunsurilor (întârziere peste 3 min.)</div>
        </div>
        <div class="d-flex flex-row">
            <div class="p-2"><div id="unpromoted-red" class="exam-legend-rectangular"></div></div>
            <div class="p-2">Nepromovat</div>
        </div>

    </div>


@endsection
