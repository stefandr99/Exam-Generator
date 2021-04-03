@extends('layouts.app')

@section('title')
    <title>Statistici examen</title>
@endsection

@section('content')
    <div class="container my-4">
        <div class="row">
            <form class="form-inline col" action="{{route('search_user')}}">
                <div class="form-group search-user">
                    <label for="search-course"><b>Student:</b></label>
                    <input type="text" name="name" id="search-course" class="form-control mx-sm-4" placeholder="Nume">
                    <button type="submit" class="btn btn-primary">Caută</button>
                </div>
            </form>
        </div>
        <br>

        <div class="d-flex">
            <div class="mr-auto p-2">
                <h4>
                    <b>Total puncte:</b> {{$exam->total_points}}
                    <br>
                    <b>Minimul de puncte:</b> {{$exam->minimum_points}}
                </h4>
            </div>
            <div class="p-2">
                <h4><b>Data examinarii:</b> {{ date_format(date_create($exam->starts_at), 'd-m-Y') }}, {{ date_format(date_create($exam->starts_at), 'H:i') }}</h4>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <h2>{{ $exam->type }} {{ $exam->course_name }}</h2>
        </div>
        <table class="table table-striped">
            <thead class="table-primary">
            <tr>
                <th scope="col">Nume</th>
                <th scope="col">Grupa</th>
                <th scope="col">Ora terminarii examenului (1)</th>
                <th scope="col">Ora trimiterii raspunsurilor (2)</th>
                <th scope="col">Diferenta de timp |(1) - (2)|</th>
                <th scope="col">Numarul penalizarilor</th>
                <th scope="col">Trimiterea raspunsurilor fortata?</th>
                <th scope="col">Rezultat</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($subjects as $subject)
                @if($time[$subject->id]['timeDifference']['invert'] == 0)
                    <tr id="green-submit-time">
                @else
                    @if($time[$subject->id]['timeDifference']['hours'] == 0 &&
                        $time[$subject->id]['timeDifference']['minutes'] == 0 &&
                            $time[$subject->id]['timeDifference']['seconds'] <= 5)
                                <tr id="very-light-red-submit-time">
                    @elseif($time[$subject->id]['timeDifference']['hours'] == 0 &&
                        $time[$subject->id]['timeDifference']['minutes'] == 0 &&
                            $time[$subject->id]['timeDifference']['seconds'] <= 20)
                                <tr id="light-red-submit-time">
                    @elseif($time[$subject->id]['timeDifference']['hours'] == 0 &&
                        $time[$subject->id]['timeDifference']['minutes'] == 0 &&
                            $time[$subject->id]['timeDifference']['seconds'] <= 45)
                                <tr id="red-submit-time">
                    @else
                        <tr id="very-red-submit-time">
                    @endif
                @endif
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->student_group }}</td>
                        <td>{{ $time[$subject->id]['endHour'] }}</td>
                        <td>{{ $time[$subject->id]['submitHour'] }}</td>
                        <td>{{ $time[$subject->id]['timeDifference']['hours']}}:{{$time[$subject->id]['timeDifference']['minutes']}}:{{$time[$subject->id]['timeDifference']['seconds'] }}</td>
                        <td>{{ $subject->penalizations }}</td>
                        <td>
                            @if($subject->forced_submit)
                                Da
                            @else
                                Nu
                            @endif
                        </td>
                        <td>{{ $subject->obtained_points }} / {{$exam->total_points}} puncte</td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
