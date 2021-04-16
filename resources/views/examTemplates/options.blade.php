<!-- Aici se primeste $options si $number care este numarul exercitiului -->

<div class="px-lg-5">
    @for($option = 0; $option < $options["counter"]; $option++)
        <div class="form-check large-text-font">
            <input class="form-check-input" type="checkbox" value="" id="ex{{$number}}option{{$option}}">
            <label class="form-check-label" for="ex{{$number}}option{{$option}}">
                {!! $options["solution"][$option]["option"] !!}
            </label>
        </div>
    @endfor
</div>
