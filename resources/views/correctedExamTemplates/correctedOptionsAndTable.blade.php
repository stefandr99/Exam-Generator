<!-- Aici se primeste $table, $options si $number care este numarul exercitiului -->

<div class="container px-lg-5">
    <div class="row mx-lg-n3">
        <div class="col py-3 px-lg-4 bg-light r_relationship">
            <table class="table">
                <thead>
                <tr>
                    @for($i = 0; $i < $table["head"]["counter"]; $i++)
                        <th scope="col">{{ $table["head"]["row"][$i] }}</th>
                    @endfor
                </tr>
                </thead>
                <tbody>
                @for($row = 1; $row <= $table["body"]["counter"]; $row++)
                    <tr>
                        @for($column = 0; $column < 5; $column++)
                            <td>{{ $table["body"]["rows"][$row][$column] }}</td>
                        @endfor
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
        <div class="col py-5 px-lg-5 bg-light">
            @for($option = 0; $option < $options["counter"]; $option++)
                <div class="form-check large-text-font">
                    @if(array_key_exists('exercise_' . $number . '_option_' . $option, $studentAnswers))
                        <input class="form-check-input" type="checkbox" value="" checked onclick="return false;">
                    @else
                        <input class="form-check-input" type="checkbox" value="" onclick="return false;">
                    @endif
                    <label class="form-check-label">
                        @if($results[$number][$option])
                            <span id="correct-answer">{!! $options["solution"][$option]["option"] !!}</span>
                            <i class="fas fa-check-circle correct-check"></i>
                            @if(array_key_exists('exercise_' . $number . '_option_' . $option, $studentAnswers))
                                <span id="correct-answer-text"><b>*Corect bifat!*</b></span>
                            @else
                                <span id="correct-answer-text"><b>*Da, acesta era un raspuns gresit!*</b></span>
                            @endif
                        @else
                            <span id="wrong-answer">{!! $options["solution"][$option]["option"] !!}</span>
                            <i class="fas fa-times-circle wrong-check"></i>
                            @if(array_key_exists('exercise_' . $number . '_option_' . $option, $studentAnswers))
                                <span id="wrong-answer-text"><b>*Gresit bifat!*</b></span>
                            @else
                                <span id="wrong-answer-text"><b>*Acest raspuns trebuia bifat!*</b></span>
                            @endif
                        @endif
                    </label>
                </div>
            @endfor
        </div>
    </div>
</div>
