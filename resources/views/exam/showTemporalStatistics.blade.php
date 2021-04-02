@extends('layouts.app')

@section('title')
    <title>Statistici examen</title>
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
        </div>
        <br>

        <table class="table table-striped">
            <thead class="table-primary">
            <tr>
                <th scope="col">Nume</th>
                <th scope="col">Grupa</th>
                <th scope="col">Curs</th>
                <th scope="col">Data</th>
                <th scope="col">Ora inceperii examenului</th>
                <th scope="col">Ora terminarii examenului (1)</th>
                <th scope="col">Ora trimiterii raspunsurilor (2)</th>
                <th scope="col">Diferenta de timp |(1) - (2)|</th>
                <th scope="col">Numarul penalizarilor</th>
                <th scope="col">Trimiterea raspunsurilor fortata?</th>
                <th scope="col">Rezultat</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
@endsection
