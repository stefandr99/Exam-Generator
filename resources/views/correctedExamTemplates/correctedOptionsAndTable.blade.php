<!-- Aici se primeste $table, $options si $number care este numarul exercitiului -->

<div class="container px-lg-5">
    <div class="row mx-lg-n3">
        <div class="col py-3 px-lg-4 r_relationship">
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
        <div class="col py-5 px-lg-5">
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
    </div>
</div>
