@extends('layouts.app')

@section('title')
    <title>Istoric</title>
@endsection

@section('content')
    <div class="card card-body timer-card">
        <div class="countdown">
            <div class="bloc-time hours" data-init-value="14">
                <span class="count-title">Ore</span>

                <div class="figure hours hours-1">
                    <span class="top">1</span>
                    <span class="top-back">
                  <span>1</span>
                </span>
                    <span class="bottom">1</span>
                    <span class="bottom-back">
                  <span>1</span>
                </span>
                </div>

                <div class="figure hours hours-2">
                    <span class="top">4</span>
                    <span class="top-back">
                  <span>4</span>
                </span>
                    <span class="bottom">4</span>
                    <span class="bottom-back">
                  <span>4</span>
                </span>
                </div>
            </div>

            <div class="bloc-time min" data-init-value="0">
                <span class="count-title">Minute</span>

                <div class="figure min min-1">
                    <span class="top">0</span>
                    <span class="top-back">
                  <span>0</span>
                </span>
                    <span class="bottom">0</span>
                    <span class="bottom-back">
                  <span>0</span>
                </span>
                </div>

                <div class="figure min min-2">
                    <span class="top">0</span>
                    <span class="top-back">
                  <span>0</span>
                </span>
                    <span class="bottom">0</span>
                    <span class="bottom-back">
                  <span>0</span>
                </span>
                </div>
            </div>

            <div class="bloc-time sec" data-init-value="0">
                <span class="count-title">Secunde</span>

                <div class="figure sec sec-1">
                    <span class="top">0</span>
                    <span class="top-back">
                  <span>0</span>
                </span>
                    <span class="bottom">0</span>
                    <span class="bottom-back">
                  <span>0</span>
                </span>
                </div>

                <div class="figure sec sec-2">
                    <span class="top">0</span>
                    <span class="top-back">
                  <span>0</span>
                </span>
                    <span class="bottom">0</span>
                    <span class="bottom-back">
                  <span>0</span>
                </span>
                </div>
            </div>
        </div>
    </div>
    <script type="application/javascript">
        window.onload = function() {
            Countdown.init();
        }
    </script>
@endsection
