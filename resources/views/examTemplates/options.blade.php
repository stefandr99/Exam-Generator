<!-- Aici se primeste $options si $number care este numarul exercitiului -->

<div class="px-lg-3">
    @for($option = 0; $option < $options["counter"]; $option++)
        <div class="inputGroup">
            <input id="ex{{$number}}option{{$option}}" name="exercise_{{$number}}_option_{{$option}}" type="checkbox" />
            <label for="ex{{$number}}option{{$option}}">
                {!! $options["solution"][$option]["option"] !!}
            </label>
        </div>
    @endfor
</div>
