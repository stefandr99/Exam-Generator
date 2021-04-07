
function scheduleExam() {
    let examData = [];
    let totalPoints = 0;
    examData[0] = document.getElementById("exam-subject").value;
    examData[1] = document.getElementById("exam-type").value;
    examData[2] = document.getElementById("exam-date").value;
    examData[3] = document.getElementById("exam-duration-hours").value;
    examData[4] = document.getElementById("exam-duration-minutes").value;
    examData[5] = document.getElementById("exam-minimum").value;
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

    let penalization = getPenalization();

    exercises = {info: JSON.stringify(examData), exercises: JSON.stringify(exercises),
                penalization: JSON.stringify(penalization)};

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $.post("/examgenerator/exam/schedule", exercises, function () {
        window.location.href = "/examgenerator/program";
    })

}

function getPenalization() {
    var penalization = {}, penaltyType;

    var radios = document.getElementsByName('examPenalty');
    //console.log(JSON.stringify(radios));

    var length = radios.length;
    //alert(length);
    for (var i = 0; i < length; i++) {
        if (radios[i].checked) {
            penaltyType = radios[i].value;
            //alert(penaltyType);
            break;
        }
    }
    penalization.type = penaltyType;
    //console.log(JSON.stringify(penalization));
    //alert(1);
    penalization.body = getPenalizationBody(penaltyType);

    return penalization;
}

function getPenalizationBody(type) {
    var body = {};
    switch (type) {
        case 'points':
            var points = document.getElementById('points-penalization').value;
            body.points = points;
            break;
        case 'time':
            var minutes = document.getElementById('minutes-penalization').value;
            var seconds = document.getElementById('seconds-penalization').value;
            body.minutes = minutes;
            body.seconds = seconds;
            break;
        case 'limitations':
            var limit = document.getElementById('rule-limit').value;
            var warnings = document.getElementById('rule-warnings').checked;
            body.limit = limit;
            body.warnings = warnings;

            var limitRadios = document.getElementsByName('examPenaltyLimit');
            var length = limitRadios.length;
            var penaltyLimitType;
            for (var i = 0; i < length; i++) {
                if (limitRadios[i].checked) {
                    penaltyLimitType = limitRadios[i].value;
                    //alert(penaltyLimitType);
                    break;
                }
            }
            body.exceeded = getPenalizationBodyWhenLimit(penaltyLimitType);
            break;
    }

    //console.log(JSON.stringify(body));
    //alert(1);
    return body;
}

function getPenalizationBodyWhenLimit(type) {
    var bodyLimit = {};
    bodyLimit.type = type;
    switch (type) {
        case 'points':
            var points = document.getElementById('limit-points-penalization').value;
            bodyLimit.points = points;
            break;
        case 'time':
            var minutes = document.getElementById('limit-minutes-penalization').value;
            var seconds = document.getElementById('limit-seconds-penalization').value;
            bodyLimit.minutes = minutes;
            bodyLimit.seconds = seconds;
            break;
    }
    return bodyLimit;
}

function updateExam(examId) {
    let examData = [];
    let totalPoints = 0;
    examData[0] = document.getElementById("exam-subject").value;
    examData[1] = document.getElementById("exam-type").value;
    examData[2] = document.getElementById("exam-date").value;
    examData[3] = document.getElementById("exam-duration-hours").value;
    examData[4] = document.getElementById("exam-duration-minutes").value;
    examData[5] = document.getElementById("exam-minimum").value;
    let exercises = [];
    exercises[0] = ++exerciseModifyNumber;
    exercises[1] = [];
    for(let i = 0; i < exerciseModifyNumber; i++) {
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

    let penalization = getPenalization();

    exercises = {info: JSON.stringify(examData), exercises: JSON.stringify(exercises),
        penalization: JSON.stringify(penalization), id: examId};

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $.ajax({
        type: 'PUT',
        url: '/examgenerator/exam/update',
        contentType: 'application/json',
        data: JSON.stringify(exercises),
    }).done(function () {
        window.location.href = "/examgenerator/program";
    })

}
