function checkTest() {
    let i;
    let nrMaxOfOptions = 20;
    let answers = [];
    let numberOfExercises = 4;
    let NR_OF_OPTIONS_1 = 6, NR_OF_OPTIONS_2 = 10, NR_OF_OPTIONS_3 = 6, NR_OF_OPTIONS_4 = 3;
    answers[0] = [];
    let currentExercise = 1;
    answers[currentExercise] = [];
    for(i = 1; i <= NR_OF_OPTIONS_1; i++) {
        let id = "ex1option".concat(i.toString());
        answers[currentExercise][i] = document.getElementById(id).checked;
    }

    currentExercise = 2;
    answers[currentExercise] = [];
    for(i = 1; i <= NR_OF_OPTIONS_2; i++) {
        let id = "ex2option".concat(i.toString());
        answers[currentExercise][i] = document.getElementById(id).checked;
    }

    currentExercise = 3;
    answers[currentExercise] = [];
    for(i = 1; i <= NR_OF_OPTIONS_3; i++) {
        let id = "ex3option".concat(i.toString());
        answers[currentExercise][i] = document.getElementById(id).checked;
    }

    currentExercise = 4;
    answers[currentExercise] = [];
    for(i = 1; i <= NR_OF_OPTIONS_4; i++) {
        let id = "ex4option".concat(i.toString());
        answers[currentExercise][i] = document.getElementById(id).checked;
    }
    answers = {arg: JSON.stringify(answers)};

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $.post("/examgenerator/exam/correct", answers, function (id) {
        window.location.href = "/examgenerator/exam/result/" + id;
    })

}

var exerciseNumber = 0;

function addExercise() {
    exerciseNumber++;
    let exerciseId = "exercise-" + exerciseNumber;
    let ex = document.getElementById(exerciseId);
    ex.removeAttribute("hidden");
}

function removeExercise() {
    if(exerciseNumber > 1) {
        let exerciseId = "exercise-" + exerciseNumber;
        let ex = document.getElementById(exerciseId);
        ex.hidden = true;
        exerciseNumber--;
    }
}

function scheduleExam() {
    let examData = [];
    let totalPoints = 0;
    examData[0] = document.getElementById("exam-subject").value;
    examData[1] = document.getElementById("exam-type").value;
    examData[2] = document.getElementById("exam-date").value;
    examData[3] = document.getElementById("exam-duration-hours").value;
    examData[4] = document.getElementById("exam-duration-minutes").value;
    let exercises = [];
    exercises[0] = ++exerciseNumber;
    exercises[1] = [];
    for(let i = 0; i < exerciseNumber; i++) {
        exercises[1][i] = [];
        let exerciseTypeId = "exam-exercise-" + i;
        let exerciseType = document.getElementById(exerciseTypeId);

        let exercisePointsId = "exercise-" + i + "-points";
        let exercisePoints = document.getElementById(exercisePointsId);
        exercises[1][i][0] = exerciseType.value;
        exercises[1][i][1] = exercisePoints.value;
        totalPoints += parseInt(exercisePoints.value);
    }
    exercises[2] = totalPoints;

    exercises = {info: JSON.stringify(examData), exercises: JSON.stringify(exercises)};

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $.post("/examgenerator/exam/schedule", exercises, function () {
        window.location.href = "/examgenerator/program";
    })

}
