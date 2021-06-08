<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/anyExamPreparation.js') }}" defer></script>
    <script src="{{ asset('js/course.js') }}" defer></script>
    <script src="{{ asset('js/examPreparation.js') }}" defer></script>
    <script src="{{ asset('js/dbExamPreparation.js') }}" defer></script>
    <script src="{{ asset('js/examScheduling.js') }}" defer></script>
    <script src="{{ asset('js/examSubject.js') }}" defer></script>
    <script src="{{ asset('js/examStatistics.js') }}" defer></script>
    <script src="{{ asset('js/user.js') }}" defer></script>
    <script src="{{ asset('js/timer.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenMax.min.js"></script>
    <script src="https://kit.fontawesome.com/2c4278887e.js" crossorigin="anonymous"></script>



    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/generator.css') }}" rel="stylesheet">
    <link href="{{ asset('css/exam.css') }}" rel="stylesheet">
    <link href="{{ asset('css/program.css') }}" rel="stylesheet">
    <link href="{{ asset('css/history.css') }}" rel="stylesheet">
    <link href="{{ asset('css/s-exam.css') }}" rel="stylesheet">
    <link href="{{ asset('css/exam-exercises.css') }}" rel="stylesheet">
    <link href="{{ asset('css/student_program.css') }}" rel="stylesheet">
    <link href="{{ asset('css/timer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Exam Generator') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest

                        @elseif(Auth::user()->role == 2)
                            <li class="nav-item dropdown dropdown-menu-dark">
                                <a id="navbarDropdownTeacherExam" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Examen
                                </a>

                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdownTeacherExam">
                                    <a class="dropdown-item" href="{{ route('prepare_DB_exam') }}">
                                        {{ __('Pregatește examen Baze de date') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('prepare_any_exam') }}">
                                        {{ __('Pregatește examen') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('show_exams') }}">
                                        {{ __('Examenele mele') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('show_last_30days_exams') }}">
                                        {{ __('Examenele mele din ultimele 30 de zile') }}
                                    </a>
                                </ul>
                            </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Logare') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Înregistrare') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if (Auth::user()->role == 3)
                                        <a class="dropdown-item" href="{{ route('show_exams') }}">
                                            {{ __('Examenele mele') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('exams_history') }}">
                                            {{ __('Istoricul meu') }}
                                        </a>
                                    @endif

                                    @if (Auth::user()->role == 1)
                                        <a class="dropdown-item" href="{{ route('show_courses') }}">
                                            {{ __('Cursuri') }}
                                        </a>
                                    @endif

                                    @if (Auth::user()->role != 3)
                                        <a class="dropdown-item" href="{{ route('users') }}">
                                            {{ __('Utilizatori') }}
                                        </a>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>

                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
</body>
</html>
