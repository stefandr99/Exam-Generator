<!-- Aici se primeste $options si $number care este numarul exercitiului -->

<div class="px-lg-5">
    @for($option = 0; $option < $options["counter"]; $option++)
        <div class="form-check large-text-font">
            @if($studentAnswers[$number][$option])
                <input class="form-check-input" type="checkbox" value="" checked onclick="return false;">
            @else
                <input class="form-check-input" type="checkbox" value="" onclick="return false;">
            @endif
            <label class="form-check-label">
                @if($results[$number][$option])
                    <span id="correct-answer">{!! $options["solution"][$option + 1]["option"] !!}</span>
                    ✅
                    @if($studentAnswers[$number][$option])
                        <span id="correct-answer-text"><b>*Corect bifat!*</b></span>
                    @else
                        <span id="correct-answer-text"><b>*Da, acesta era un raspuns gresit!*</b></span>
                    @endif
                @else
                    <span id="wrong-answer">{!! $options["solution"][$option + 1]["option"] !!}</span>
                    ❌
                    @if($studentAnswers[$number][$option])
                        <span id="wrong-answer-text"><b>Gresit bifat!</b></span>
                    @else
                        <span id="wrong-answer-text"><b>*Acest raspuns trebuia bifat!*</b></span>
                    @endif
                @endif
            </label>
        </div>
    @endfor
</div>
