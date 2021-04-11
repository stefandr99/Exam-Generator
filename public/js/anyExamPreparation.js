
let numberOfExamExercises;
let numberOfExerciseOptions = [];
function setNumberOfAnyExamExercises() {
    numberOfExamExercises = 0;
    for(let i = 0; i < 100; i++)
        numberOfExerciseOptions[i] = 0;
}

function addExerciseOption(exercise) {
    numberOfExerciseOptions[exercise]++;
    let exerciseOptionId = "exercise-" + exercise + "-hidden-option-" + numberOfExerciseOptions[exercise];
    let option = document.getElementById(exerciseOptionId);
    option.removeAttribute("hidden");

    nrOfOptionsId = "number_of_options_exercise_" + exercise;
    document.getElementById(nrOfOptionsId).value = numberOfExerciseOptions[exercise];
}

function removeExerciseOption(exercise) {
    if(numberOfExerciseOptions[exercise] > 0) {
        let exerciseOptionId = "exercise-" + exercise + "-hidden-option-" + numberOfExerciseOptions[exercise];
        let option = document.getElementById(exerciseOptionId);

        option.hidden = true;

        let exerciseOptionTextId = "exercise-" + exercise + "-option-" + numberOfExerciseOptions[exercise];
        document.getElementById(exerciseOptionTextId).value = '';

        let optionCorrectness = "exercise-" + exercise + "-option-" + numberOfExerciseOptions[exercise] + "-true";
        document.getElementById(optionCorrectness).checked = true;

        numberOfExerciseOptions[exercise]--;
        nrOfOptionsId = "number_of_options_exercise_" + exercise;
        document.getElementById(nrOfOptionsId).value = numberOfExerciseOptions[exercise];
    }
}

function addExercise() {
    numberOfExamExercises++;
    let exerciseId = "exam-exercise-" + numberOfExamExercises;
    let exercise = document.getElementById(exerciseId);
    exercise.removeAttribute("hidden");

    nrOfExercisesId = "number_of_exercises";
    document.getElementById(nrOfExercisesId).value = numberOfExamExercises;
}

function removeExercise() {
    if(numberOfExamExercises > 0) {
        let exerciseId = "exam-exercise-" + numberOfExamExercises;
        let exercise = document.getElementById(exerciseId);

        exercise.hidden = true;

        let exerciseTextId = "text-exercise-" + numberOfExamExercises;
        document.getElementById(exerciseTextId).value = '';

        let exercisePointsId = "points-exercise-" + numberOfExamExercises;
        document.getElementById(exercisePointsId).value = '';

        removeAllOptionsFromExercise(numberOfExamExercises);

        numberOfExamExercises--;

        nrOfExercisesId = "number_of_exercises";
        document.getElementById(nrOfExercisesId).value = numberOfExamExercises;
    }
}

function removeAllOptionsFromExercise(exercise) {
    for(let option = numberOfExerciseOptions[exercise]; option > 0; option--) {
        let exerciseOptionId = "exercise-" + exercise + "-hidden-option-" + numberOfExerciseOptions[exercise];
        let option = document.getElementById(exerciseOptionId);

        option.hidden = true;

        let exerciseOptionTextId = "exercise-" + exercise + "-option-" + numberOfExerciseOptions[exercise];
        document.getElementById(exerciseOptionTextId).value = '';

        let optionCorrectness = "exercise-" + exercise + "-option-" + numberOfExerciseOptions[exercise] + "-true";
        document.getElementById(optionCorrectness).checked = true;
    }
    let exerciseOptionTextId = "exercise-" + exercise + "-option-0";
    document.getElementById(exerciseOptionTextId).value = '';

    let optionCorrectness = "exercise-" + exercise + "-option-0-true";
    document.getElementById(optionCorrectness).checked = true;

    numberOfExerciseOptions[exercise] = 0;
}
