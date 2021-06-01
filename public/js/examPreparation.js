
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

/**
 * Prepare any exam --BEGIN--
 */
function addNewExercise() {
    var numberOfExercisesInput = document.getElementById('number_of_exercises');
    var numberOfExercises = parseInt(numberOfExercisesInput.value);
    numberOfExercises++;
    numberOfExercisesInput.value = numberOfExercises;

    addNewTab(numberOfExercises, 'any');
    addExerciseContent(numberOfExercises);
}

function addNewTab(numberOfExercises, subject) {
    var existingLi = document.querySelector('#exercise_0_tab');
    var clone = existingLi.cloneNode(true);
    clone.id = 'exercise_' + numberOfExercises + '_tab';

    var aTabFile = clone.firstChild;
    aTabFile.id = 'exercise_' + numberOfExercises + '_title';
    aTabFile.href = "#exercise_" + numberOfExercises;
    aTabFile.innerHTML = 'Exercitiul ' + (numberOfExercises + 1) + '&nbsp;';

    var closeTabButton = document.createElement('span');
    closeTabButton.classList.add("close");
    closeTabButton.classList.add("mt-1");
    closeTabButton.innerHTML = '&times;';

    var closeId = 'exercise_' + numberOfExercises + '_tab';
    let deleteTabFunction = "deleteTab('" + closeId + "', '" + subject + "')";
    closeTabButton.setAttribute( "onClick", deleteTabFunction );

    aTabFile.appendChild(closeTabButton);

    var exercisesUl = document.getElementById('exercisesTab');
    var addTabButton = document.getElementById('add_exercise_tab');
    exercisesUl.insertBefore(clone, addTabButton);
}

function addExerciseContent(numberOfExercises) {
    var exerciseCardContent = document.getElementById('exercisesContent');

    var existingExercise = document.querySelector('#exercise_x');
    var clone = existingExercise.cloneNode(true);
    clone.id = 'exercise_' + numberOfExercises;

    var children = clone.childNodes;

    setExerciseTextarea(numberOfExercises, children[2]);
    setExerciseOptionDivs(numberOfExercises, children);
    setExerciseOptionBody(numberOfExercises, children[8].firstChild.firstChild);
    setExerciseOptionButtons(numberOfExercises, children[10], children[12])
    setExerciseOptionGenerated(numberOfExercises, children)
    setExercisePoints(numberOfExercises, children[24]);

    exerciseCardContent.appendChild(clone);
}

function setExerciseTextarea(numberOfExercises, textarea) {
    textarea.id = 'text-exercise-' + numberOfExercises;
    textarea.name = 'text_exercise_' + numberOfExercises;
}

function setExerciseOptionDivs(numberOfExercises, children) {
    var numberOfExercisesInput = children[4];
    numberOfExercisesInput.id = 'number_of_options_exercise_' + numberOfExercises;
    numberOfExercisesInput.name = 'number_of_options_exercise_' + numberOfExercises;

    var principalOptionsDiv = children[8];
    principalOptionsDiv.id = 'div_exercise_' + numberOfExercises + '_options';

    var optionDiv = principalOptionsDiv.firstChild;
    optionDiv.id = 'div_exercise_' + numberOfExercises + '_option_0';
}

function setExerciseOptionBody(numberOfExercises, div) {
    var children = div.childNodes;

    var optionInputLabel = children[0];
    optionInputLabel.setAttribute( "for", 'exercise-' + numberOfExercises + '-option-0' );

    var optionInput = children[2];
    optionInput.id = 'exercise-' + numberOfExercises + '-option-0';
    optionInput.name = 'exercise_' + numberOfExercises + '_option_0';

    var correctRadioInput = children[4].firstChild;
    correctRadioInput.id = 'exercise-' + numberOfExercises + '-option-0-true';
    correctRadioInput.name = 'exercise_' + numberOfExercises + '_option_0_answer';

    var wrongRadioInput = children[8].firstChild;
    wrongRadioInput.id = 'exercise-' + numberOfExercises + '-option-0-false';
    wrongRadioInput.name = 'exercise_' + numberOfExercises + '_option_0_answer';
}

function setExerciseOptionButtons(numberOfExercises, addButton, removeButton) {
    addButton.id = 'add_option_' + numberOfExercises;
    let addOptionFunction = 'addOption(' + numberOfExercises + ')';
    addButton.setAttribute( "onClick", addOptionFunction );

    removeButton.id = 'remove_option_' + numberOfExercises;
    let removeOptionFunction = 'removeOption(' + numberOfExercises + ')';
    removeButton.setAttribute( "onClick", removeOptionFunction );
}

function setExerciseOptionGenerated(numberOfExercises, children) {
    var optionsGeneratedLabel = children[18];
    optionsGeneratedLabel.setAttribute( "for", 'number-of-options-exercise-' + numberOfExercises );

    var optionsGenerated = optionsGeneratedLabel.firstChild;
    optionsGenerated.id = 'number-of-options-exercise-' + numberOfExercises;
    optionsGenerated.name = 'number_of_generated_options_' + numberOfExercises;
    optionsGenerated.addEventListener('change', function () {
        var id = '#collapseExerciseCorrectness_' + numberOfExercises
        $(id).collapse();
    });

    var collapseOptions = children[20];
    collapseOptions.id = 'collapseExerciseCorrectness_' + numberOfExercises;

    var collapseOptionsDiv = collapseOptions.firstChild.firstChild.firstChild.firstChild;
    var correctnessOptions = collapseOptionsDiv.childNodes;

    var correctOptionsLabel = correctnessOptions[0];
    correctOptionsLabel.setAttribute( "for", 'correct-options-ex-' + numberOfExercises );
    var correctOptionsInput = correctOptionsLabel.childNodes[1];
    correctOptionsInput.id = 'correct-options-ex-' + numberOfExercises;
    correctOptionsInput.name = 'correct_options_ex_' + numberOfExercises;

    var wrongOptionsLabel = correctnessOptions[2];
    wrongOptionsLabel.setAttribute( "for", 'wrong-options-ex-' + numberOfExercises );
    var wrongOptionsInput = wrongOptionsLabel.childNodes[1];
    wrongOptionsInput.id = 'wrong-options-ex-' + numberOfExercises;
    wrongOptionsInput.name = 'wrong_options_ex_' + numberOfExercises;
}

function setExercisePoints(numberOfExercises, pointsLabel) {
    pointsLabel.setAttribute( "for", 'points-exercise-' + numberOfExercises );

    var pointsInput = pointsLabel.childNodes[1];
    pointsInput.id = 'points-exercise-' + numberOfExercises;
    pointsInput.name = 'points_exercise_' + numberOfExercises;
}

function deleteTab(id, subject) {
    var numberOfExercisesInput = document.getElementById('number_of_exercises');
    var numberOfExercises = parseInt(numberOfExercisesInput.value);

    if(numberOfExercises > 0) {
        var tabToDelete = document.getElementById(id);
        tabToDelete.remove();

        var exerciseNumber = id.split("_")[1];

        renameTabs(numberOfExercises, parseInt(exerciseNumber), subject);

        var exerciseId = 'exercise_' + numberOfExercises;
        var exerciseToRemove = document.getElementById(exerciseId);
        if(exerciseToRemove != null)
            exerciseToRemove.remove();

        numberOfExercises--;
        numberOfExercisesInput.value = numberOfExercises;
    }
}

function renameTabs(numberOfExercises, idDeleted, subject) {
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

        tabTitle.innerHTML = 'Exercitiul ' + (currentExercise + 1) + '&nbsp;';
        tabTitle.appendChild(closeTabButton);

        tabTitle.href = "#exercise_" + currentExercise;

        if(subject === 'any')
            changeExerciseContent(ex - 1, ex);
        else
            changeDbExerciseContent(ex - 1, ex);
    }
}

function changeExerciseContent(decrementedExercise, currExercise) {

    let decrementedTextarea = document.getElementById('text-exercise-' + decrementedExercise);
    let currTextarea = document.getElementById('text-exercise-' + currExercise);
    decrementedTextarea.value = currTextarea.value;

    var decrementedNumberOfOp = document.getElementById('number_of_options_exercise_' + decrementedExercise).value;
    decrementedNumberOfOp = parseInt(decrementedNumberOfOp);
    var currentNumberOfOp = document.getElementById('number_of_options_exercise_' + currExercise).value;
    currentNumberOfOp = parseInt(currentNumberOfOp);

    for(let i = 0; i <= currentNumberOfOp; i++) {
        if(i > decrementedNumberOfOp)
            addOption(decrementedExercise);

        let decrOptionInput = document.getElementById('exercise-' + decrementedExercise + '-option-' + i);
        let currOptionInput = document.getElementById('exercise-' + currExercise + '-option-' + i);
        decrOptionInput.value = currOptionInput.value;

        let radioInputAnswer = 'exercise_' + currExercise + '_option_' + i + '_answer';
        var answer = document.querySelector('input[name=' + radioInputAnswer + ']:checked').value;
        if(answer === 'true') {
            let trueRadioInput = document.getElementById('exercise-' + decrementedExercise + '-option-' + i + '-true')
            trueRadioInput.checked = true;
        }
        else {
            let falseRadioInput = document.getElementById('exercise-' + decrementedExercise + '-option-' + i + '-false')
            falseRadioInput.checked = true;
        }
    }

    while(decrementedNumberOfOp > currentNumberOfOp) {
        removeOption(decrementedExercise);
        decrementedNumberOfOp--;
    }

    var decrOptionsGenInput = document.getElementById('number-of-options-exercise-' + decrementedExercise);
    var currOptionsGenInput = document.getElementById('number-of-options-exercise-' + currExercise);
    decrOptionsGenInput.value = currOptionsGenInput.value;

    var decrCorrectOptionsGenInput = document.getElementById('correct-options-ex-' + decrementedExercise);
    var currCorrectOptionsGenInput = document.getElementById('correct-options-ex-' + currExercise);
    decrCorrectOptionsGenInput.value = currCorrectOptionsGenInput.value;
    var decrWrongOptionsGenInput = document.getElementById('wrong-options-ex-' + decrementedExercise);
    var currWrongOptionsGenInput = document.getElementById('wrong-options-ex-' + currExercise);
    decrWrongOptionsGenInput.value = currWrongOptionsGenInput.value;

    let decrPointsInput = document.getElementById('points-exercise-' + decrementedExercise);
    let currPointsInput = document.getElementById('points-exercise-' + currExercise);
    decrPointsInput.value = currPointsInput.value;
}

function addOption(exercise) {
    var optionCounterId = 'number_of_options_exercise_' + exercise;
    var optionsCounter = document.getElementById(optionCounterId);
    optionsCounter.value = parseInt(optionsCounter.value) + 1;

    var optionNumber = parseInt(optionsCounter.value);
    var existingOption = document.querySelector('#div_exercise_' + exercise +'_options');

    var optionTemplate = document.querySelector('#div_exercise_x_option_0');
    var clone = optionTemplate.cloneNode(true);
    clone.id = 'div_exercise_' + exercise +'_option_' + optionNumber;

    var optionLabels = clone.firstChild.childNodes;

    var optionInputLabel = optionLabels[0];
    optionInputLabel.for = 'exercise-' + exercise +'-option-' + optionNumber;
    optionInputLabel.innerHTML = (optionNumber + 1) + '.&nbsp;&nbsp;';
    var optionInput = optionLabels[2];
    optionInput.id = 'exercise-' + exercise +'-option-' + optionNumber;
    optionInput.name = 'exercise_' + exercise +'_option_' + optionNumber;

    var correctRadioInput = optionLabels[4].firstChild;
    correctRadioInput.id = 'exercise-' + exercise +'-option-' + optionNumber + '-true';
    correctRadioInput.name = 'exercise_' + exercise +'_option_' + optionNumber + '_answer';
    correctRadioInput.checked = true;

    var wrongRadioInput = optionLabels[8].firstChild;
    wrongRadioInput.id = 'exercise-' + exercise +'-option-' + optionNumber + '-false';
    wrongRadioInput.name = 'exercise_' + exercise +'_option_' + optionNumber + '_answer';

    existingOption.appendChild(clone);
}

function removeOption(exercise) {
    var optionCounterId = 'number_of_options_exercise_' + exercise;
    var optionsCounter = document.getElementById(optionCounterId);

    var counter = parseInt(optionsCounter.value);

    if(counter > 0) {
        var optionId = 'div_exercise_' + exercise + '_option_' + counter;
        var optionToRemove = document.getElementById(optionId);
        if (optionToRemove != null)
            optionToRemove.remove();

        optionsCounter.value = parseInt(optionsCounter.value) - 1;
    }
}

/**
 * Prepare any Exam --END--
 */



