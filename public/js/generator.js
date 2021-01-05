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

    $.post("/examgenerator/exam/result", answers, function (data) {
        console.log(data);
    })

}
