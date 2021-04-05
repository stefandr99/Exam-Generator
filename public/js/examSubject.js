let startPoints = 0;
function checkTest(numberOfExercises, optionsNumber, examId, forced) {
    let i;
    let answers = [];
    optionsNumber = JSON.parse(optionsNumber);

    for (exercise = 0; exercise < numberOfExercises; exercise++) {
        answers[exercise] = [];
        for (i = 0; i < optionsNumber[exercise]; i++) {
            let id = "ex".concat(exercise.toString()).concat("option").concat(i.toString());
            answers[exercise][i] = document.getElementById(id).checked;
        }
    }

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    result = {answers: JSON.stringify(answers), exercisesNr: numberOfExercises,
        optionsNr: optionsNumber, examId: examId, isForced: forced};

    $.post("/examgenerator/exam/correct", result, function (info) {
        info = JSON.parse(info);
        window.location.href = "/examgenerator/exam/" + parseInt(info[0]).toString() + "/result/" + parseInt(info[1]).toString();
    })

}

var timer;
function startTimer(duration) {
    timer = duration;
    var hours, minutes, seconds;
    setInterval(function () {
        hours = parseInt(timer / 3600, 10);
        minutes = parseInt((timer / 60) % 60, 10);
        seconds = parseInt(timer % 60, 10);

        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        if (--timer < 0) {
            document.getElementById("submitExam").click();
        }
        if(timer >= 0) {
            document.getElementById("hourss").innerHTML = hours;
            document.getElementById("mins").innerHTML = minutes;
            document.getElementById("secs").innerHTML = seconds;
        }

    }, 1000);
}

var decodeHTML = function (html) {
    var txt = document.createElement('textarea');
    txt.innerHTML = html;
    return txt.value;
};

let limit = -1;
function penalization(data) {
    data = decodeHTML(data);
    let penalty = JSON.parse(data);

    if(penalty.type !== 'without') {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("/examgenerator/exam/increase_penalty");
    }

    switch (penalty.type) {
        case 'time':
            timer = timer - (parseInt(penalty.body.minutes) * 60 + parseInt(penalty.body.seconds));
            checkTimeAfterPenalty();
            break;
        case 'points':
            startPoints -= parseFloat(penalty.body.points);
            break;
        case 'limitations':
            if(limit === -1) {
                limit = penalty.body.limit;
            }
            penalizationWithLimits(penalty);
            break;
        case 'end':
            document.getElementById("submitExamForced").click();
            break;
        default:
            break;
    }
}


function penalizationWithLimits(penalty) {
    if(limit > 0) {
        if(penalty.body.warnings) {
            $("#fraudTheExam").modal();
            limit--;
        }
    }
    else {
        switch (penalty.body.exceeded.type) {
            case 'time':
                timer = timer - (parseInt(penalty.body.exceeded.minutes) * 60 + parseInt(penalty.body.exceeded.seconds));
                checkTimeAfterPenalty();
                break;
            case 'points':
                startPoints -= parseFloat(penalty.body.exceeded.points);
                break;
            case 'end':
                document.getElementById("submitExamForced").click();
                break;
        }
    }
}

function checkTimeAfterPenalty() {
    if(timer < 0) {
        document.getElementById("submitExamForced").click();
    }
}
