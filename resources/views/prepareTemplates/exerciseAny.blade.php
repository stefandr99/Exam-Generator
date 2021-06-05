<div hidden>
    <div class="tab-pane fade show active p-3" id="exercise_x" role="tabpanel" aria-labelledby="ex-x-tab">
        <label class="card-text text-uppercase font-weight-bold">Enunt:</label>
        <textarea id="text-exercise-x" name="text_exercise_0" class="form-control" rows="3" cols="100" placeholder="Enunt">
            {{old('text_exercise_x')}}
        </textarea>

        <input hidden id="number_of_options_exercise_x" name="number_of_options_exercise_x" value="0">
        <label class="card-text text-uppercase font-weight-bold">Variante de raspuns:</label>
        <div id="div_exercise_x_options">
            <div id="div_exercise_x_option_0">
                <div class="inline-elements">
                    <label for="exercise-x-option-0">1.&nbsp;&nbsp;</label>
                    <input id="exercise-x-option-0" name="exercise_x_option_0" type="text" class="form-control" placeholder="Varianta de raspuns">&nbsp;

                    <label>
                        <input id="exercise-x-option-0-true" value="true" name="exercise_x_option_0_answer" type="radio" checked>
                    </label>
                    <p>&nbsp;Corect&nbsp;&nbsp;</p>
                    <label>
                        <input id="exercise-x-option-0-false" value="false" name="exercise_x_option_0_answer" type="radio">
                    </label>
                    <p>&nbsp;Gresit</p>
                </div>
            </div>
        </div>

        <div class="float-left">
            <button id="add_option_x" type="button" class="btn btn-outline-info btn-sm">Adăugați încă o varianta</button>
            <button id="delete_option_x" type="button" class="btn btn-outline-danger btn-sm">Stergeți ultima varianta</button>

            <br>
            <small>Numarul de variante de raspuns generate:</small>
            <label for="number-of-options-exercise-x">
                <input id="number-of-options-exercise-x" name="number_of_generated_options_x" type="text" class="form-control nr-of-ops-per-ex" size="1" placeholder="Nr" onchange="$('#collapseExerciseCorrectness_x').collapse();">
            </label>

            <div class="collapse" id="collapseExerciseCorrectness_x">
                <div class="card card-body" style="width: 8.1rem; height: 5.4rem;">
                    <div class="row" style="padding-bottom: 30px;">
                        <small>
                            <div class="position-relative">
                                <label for="correct-options-ex-x">
                                    Corecte: &nbsp;
                                    <input id="correct-options-ex-x" name="correct_options_ex_x" type="text" class="form-control col correct-wrong-options" value="0">
                                </label>
                                <label for="wrong-options-ex-x">
                                    Gresite:&nbsp;
                                    <input id="wrong-options-ex-x" name="wrong_options_ex_x" type="text" class="form-control col correct-wrong-options" value="0">
                                </label>
                            </div>
                        </small>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="float-right form-inline mt-2">
            <label for="points-exercise-x" class="card-text text-uppercase font-weight-bold">Puncte:&nbsp;
                <input id="points-exercise-x" name="points_exercise_x" type="text" class="form-control" placeholder="Puncte">
            </label>
        </div>
    </div>
</div>
