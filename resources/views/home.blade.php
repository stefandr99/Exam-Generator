@extends('layouts.app')

@section('title')
    <title>Exam Generator</title>
@endsection

<body style="background-image: url('http://localhost/examgenerator/img/home2.png');">
@section('content')
    <main style="padding-right: 450px;" class="text-center">
    <h1 class="home-title">
        <span>
            Exam Generator
        </span>
    </h1>
    <h3 class="home-title">
        <span>
            Platformă de examinare pentru studenți și profesori
        </span>
    </h3>
        @if(auth()->user()->role == 1)
            <button class="custom-home-btn home-btn ml-3 px-0" style="margin-top: 80px; outline: none;" onclick="window.location='{{ route("show_courses") }}'">Gestionează cursuri</button>
            <button class="custom-home-btn home-btn ml-3 px-0" style="margin-top: 80px; outline: none;" onclick="window.location='{{ route("users") }}'">Gestionează utilizatori</button>
        @endif
        @if(auth()->user()->role == 2)
            <button class="custom-home-btn home-btn mr-3" style="margin-top: 80px; outline: none;" onclick="window.location='{{ route("show_exams") }}'">Examenele mele</button>
            <button class="custom-home-btn home-btn ml-3 px-0" style="margin-top: 80px; outline: none;" onclick="window.location='{{ route("prepare_DB_exam") }}'">Pregătește examen la BD</button>
            <button class="custom-home-btn home-btn ml-3 px-0" style="margin-top: 80px; outline: none;" onclick="window.location='{{ route("prepare_any_exam") }}'">Pregătește orice examen</button>
        @endif
        @if(auth()->user()->role == 3)
            <button class="custom-home-btn home-btn" style="margin-top: 80px; outline: none;" onclick="window.location='{{ route("show_exams") }}'">Examenele mele</button>
        @endif
    </main>
@endsection
</body>
