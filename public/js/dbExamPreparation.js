
function addNewDbExercise() {
    var numberOfExercisesInput = document.getElementById('number_of_exercises');
    var numberOfExercises = parseInt(numberOfExercisesInput.value);
    numberOfExercises++;
    numberOfExercisesInput.value = numberOfExercises;

    addNewTab(numberOfExercises, 'db');
    addDbExerciseContent(numberOfExercises);

    deactivateFirstContent(numberOfExercises);
}

function addDbExerciseContent(numberOfExercises) {
    var exerciseCardContent = document.getElementById('exercisesContent');

    var existingExercise = document.querySelector('#exercise_x');
    var clone = existingExercise.cloneNode(true);
    clone.id = 'exercise_' + numberOfExercises;

    var children = clone.childNodes;

    setExerciseType(numberOfExercises, children);
    setExercisePoints(numberOfExercises, children[4]);

    exerciseCardContent.appendChild(clone);
}

function setExerciseType(numberOfExercises, children) {
    var exerciseTypeLabel = children[0];
    exerciseTypeLabel.setAttribute( "for", 'exam-exercise-' + numberOfExercises);

    var exerciseTypeSelect = children[2];
    exerciseTypeSelect.id = 'exam-exercise-' + numberOfExercises;
    exerciseTypeSelect.name = 'exam_exercise_' + numberOfExercises;
}

function changeDbExerciseContent(decrExercise, currExercise) {
    let decrExerciseType = document.getElementById('exam-exercise-' + decrExercise);
    let currExerciseType = document.getElementById('exam-exercise-' + currExercise);
    decrExerciseType.value = currExerciseType.value;

    let decrPointsInput = document.getElementById('points-exercise-' + decrExercise);
    let currPointsInput = document.getElementById('points-exercise-' + currExercise);
    decrPointsInput.value = currPointsInput.value;
}
