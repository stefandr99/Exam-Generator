
var exerciseNumber = 0;
function addExercise() {
    exerciseNumber++;
    let exerciseId = "exercise-" + exerciseNumber;
    let ex = document.getElementById(exerciseId);
    ex.removeAttribute("hidden");
}

function removeExercise() {
    if(exerciseNumber > 0) {
        let exerciseId = "exercise-" + exerciseNumber;
        let ex = document.getElementById(exerciseId);

        ex.hidden = true;

        let exerciseTypeId = "exam-exercise-" + exerciseNumber;
        let exerciseType = document.getElementById(exerciseTypeId);
        let exercisePointsId = "exercise-" + exerciseNumber + "-points";
        let exercisePoints = document.getElementById(exercisePointsId);
        exerciseType.value = 'no-exercise';
        exercisePoints.value = '';

        exerciseNumber--;
    }
}

var exerciseModifyNumber;
function setNumberOfExercises(exercisesNr) {
    exerciseModifyNumber = exercisesNr - 1;
}

function addOnModifyExercise() {
    exerciseModifyNumber++;
    let exerciseId = "exercise-" + exerciseModifyNumber;
    let ex = document.getElementById(exerciseId);
    ex.removeAttribute("hidden");
}

function removeOnModifyExercise() {
    if(exerciseModifyNumber > 0) {
        let exerciseId = "exercise-" + exerciseModifyNumber;
        let ex = document.getElementById(exerciseId);

        ex.hidden = true;

        let exerciseTypeId = "exam-exercise-" + exerciseModifyNumber;
        let exerciseType = document.getElementById(exerciseTypeId);
        let exercisePointsId = "exercise-" + exerciseModifyNumber + "-points";
        let exercisePoints = document.getElementById(exercisePointsId);
        exerciseType.value = 'no-exercise';
        exercisePoints.value = '';

        exerciseModifyNumber--;
    }
}


function onRadioPenaltyCollapse() {
    for (var i = 1; i < 4; i++) {
        var id = "examPenalty" + i.toString();
        var radio = document.getElementById(id);
        if(!radio.checked) {
            $('#collapsePenalty' + i.toString()).collapse('hide');
        }
    }
}

function onRadioPenaltyLimitCollapse() {
    for (var i = 1; i < 3; i++) {
        var id = "examPenaltyLimit" + i.toString();
        var radio = document.getElementById(id);
        if(!radio.checked) {
            $('#collapsePenaltyLimit' + i.toString()).collapse('hide');
        }
    }
}
