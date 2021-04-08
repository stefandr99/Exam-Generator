
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
    }
}

function addExercise() {
    numberOfExamExercises++;
    let exerciseId = "exam-exercise-" + numberOfExamExercises;
    let exercise = document.getElementById(exerciseId);
    exercise.removeAttribute("hidden");
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
