
var exerciseNumber = 0;
function addDBExercise() {
    exerciseNumber++;
    let exerciseId = "exercise-" + exerciseNumber;
    let ex = document.getElementById(exerciseId);
    ex.removeAttribute("hidden");

    nrOfExercisesId = "number_of_exercises";
    document.getElementById(nrOfExercisesId).value = exerciseNumber;
}

function removeDBExercise() {
    if(exerciseNumber > 0) {
        let exerciseId = "exercise-" + exerciseNumber;
        let ex = document.getElementById(exerciseId);

        ex.hidden = true;

        let exerciseTextId = "exam-exercise-" + exerciseNumber;
        document.getElementById(exerciseTextId).value = '';

        let exercisePointsId = "exercise-" + exerciseNumber + "-points";
        document.getElementById(exercisePointsId).value = '';

        let exerciseTypeId = "exam-exercise-" + exerciseNumber;
        document.getElementById(exerciseTypeId).value = 'no-exercise';

        exerciseNumber--;

        nrOfExercisesId = "number_of_exercises";
        document.getElementById(nrOfExercisesId).value = numberOfExamExercises;
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

var numberOfExercises = 1;
function addNewTab() {
    numberOfExercises++;
    var existingLi = document.querySelector('#exercise_1_tab');
    var clone = existingLi.cloneNode(true);
    clone.id = 'exercise_' + numberOfExercises + '_tab';

    var aTabFile = clone.firstChild;
    aTabFile.id = 'exercise_' + numberOfExercises + '_title';
    aTabFile.href = "#exercise_1";
    aTabFile.innerHTML = 'Exercitiul ' + numberOfExercises + '&nbsp;';

    var closeTabButton = document.createElement('span');
    closeTabButton.classList.add("close");
    closeTabButton.classList.add("mt-1");
    closeTabButton.innerHTML = '&times;';
    closeTabButton.addEventListener('click', function(){
        var closeId = 'exercise_' + numberOfExercises + '_tab';
        deleteTab(closeId);
    });
    aTabFile.appendChild(closeTabButton);

    var exercisesUl = document.getElementById('exercisesTab');
    var addTabButton = document.getElementById('add_exercise_tab');
    exercisesUl.insertBefore(clone, addTabButton);
}

function addExerciseContent() {

}

function deleteTab(id) {
    if(numberOfExercises > 1) {
        var tabToDelete = document.getElementById(id);
        tabToDelete.remove();

        var exerciseNumber = id.split("_")[1];

        var exerciseId = 'exercise_' + exerciseNumber;
        var exerciseToRemove = document.getElementById(exerciseId);
        if(exerciseToRemove != null)
            exerciseToRemove.remove();

        renameTabs(parseInt(exerciseNumber));
        renameExerciseContent(parseInt(exerciseNumber));
        numberOfExercises--;
    }
}

function renameTabs(idDeleted) {
    for(var ex = idDeleted + 1; ex <= numberOfExercises; ex++) {
        let currentExercise = ex - 1;
        var idTabLi = "exercise_" + ex + "_tab";
        var tabLi = document.getElementById(idTabLi);
        tabLi.id = "exercise_" + currentExercise + "_tab";

        var idTabTitle = "exercise_" + ex + "_title";
        var tabTitle = document.getElementById(idTabTitle);
        tabTitle.id = "exercise_" + currentExercise + "_title";

        var closeTabButton = document.createElement('span');
        closeTabButton.classList.add("close");
        closeTabButton.classList.add("mt-1");
        closeTabButton.innerHTML = '&times;';
        closeTabButton.addEventListener('click', function(){
            var closeId = 'exercise_' + currentExercise + '_tab';
            deleteTab(closeId);
        });

        tabTitle.innerHTML = 'Exercitiul ' + currentExercise + '&nbsp;';
        tabTitle.appendChild(closeTabButton);

        var idContentCard = "exercise_" + ex;
        var contentCard = document.getElementById(idContentCard);
        if(contentCard != null)
            contentCard.id = "exercise_" + currentExercise;
        tabTitle.href = "#exercise_" + currentExercise;
    }
}








function addExerciseOption2(exercise) {
    var optionCounterId = 'number_of_options_exercise_' + exercise;
    var optionsCounter = document.getElementById(optionCounterId);
    optionsCounter.value = parseInt(optionsCounter.value) + 1;

    var optionNumber = parseInt(optionsCounter.value);
    var existingOption = document.querySelector('#div_exercise_0_options');
    var clone = existingOption.firstChild.cloneNode(true);
    clone.id = 'div_exercise_0_option_' + optionNumber;

    var optionLabels = clone.firstChild.childNodes;

    var optionInputLabel = optionLabels[0];
    optionInputLabel.for = 'exercise-0-option-' + optionNumber;
    optionInputLabel.innerHTML = optionNumber + 1 + '.&nbsp;&nbsp;';
    var optionInput = optionLabels[2];
    optionInput.id = 'exercise-0-option-' + optionNumber;
    optionInput.name = 'exercise_0_option_' + optionNumber;

    var correctRadioInput = optionLabels[4].firstChild;
    correctRadioInput.id = 'exercise-0-option-' + optionNumber + '-true';
    correctRadioInput.name = 'exercise_0_option_' + optionNumber + '_answer';

    var wrongRadioInput = optionLabels[8].firstChild;
    wrongRadioInput.id = 'exercise-0-option-' + optionNumber + '-false';
    wrongRadioInput.name = 'exercise_0_option_' + optionNumber + '_answer';

    existingOption.appendChild(clone);
}

function removeOption2(exercise) {
    var optionCounterId = 'number_of_options_exercise_' + exercise;
    var optionsCounter = document.getElementById(optionCounterId);

    var counter = parseInt(optionsCounter.value);

    if(counter > 0) {
        var optionId = 'div_exercise_0_option_' + counter;
        var optionToRemove = document.getElementById(optionId);
        if (optionToRemove != null)
            optionToRemove.remove();

        optionsCounter.value = parseInt(optionsCounter.value) - 1;
    }
}


