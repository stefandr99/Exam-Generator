<!-- Aici se primeste $options si $number care este numarul exercitiului -->

<div class="px-lg-5">
    @for($option = 0; $option < $options["counter"]; $option++)
        @if($results[$number][$option])
            <div class="correctAnswerGroup">
        @else
            <div class="wrongAnswerGroup">
        @endif
            @if(array_key_exists('exercise_' . $number . '_option_' . $option, $studentAnswers))
                <input class="form-check-input" type="checkbox" value="" checked onclick="return false;">
            @else
                <input class="form-check-input" type="checkbox" value="" onclick="return false;">
            @endif
            <label>
                {!! $options["solution"][$option]["option"] !!}
            </label>
        </div>
    @endfor
</div>
