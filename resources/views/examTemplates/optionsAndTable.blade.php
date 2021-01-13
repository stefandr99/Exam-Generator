<!-- Aici se primeste $table ,$options si $number care este numarul exercitiului -->

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
            @for($option = 1; $option <= $options["counter"]; $option++)
                <div class="form-check dependencies-options">
                    <input class="form-check-input" type="checkbox" value="" id="ex{{$number}}option{{$option}}">
                    <label class="form-check-label" for="ex{{$number}}option{{$option}}">
                        {!! $options["solution"][$option]["option"] !!}
                    </label>
                </div>
            @endfor
        </div>
    </div>
</div>
