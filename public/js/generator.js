function checkTest(numberOfExercises, optionsNumber, examId) {
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
    result = {answers: JSON.stringify(answers), exercisesNr: numberOfExercises,
        optionsNr: optionsNumber, examId: examId};

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $.post("/examgenerator/exam/correct", result, function (info) {
        info = JSON.parse(info);
        window.location.href = "/examgenerator/exam/" + parseInt(info[0]).toString() + "/result/" + parseInt(info[1]).toString();
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

var exerciseModifyNumber = -1;
function addModifyExercise(nr) {
    if(exerciseModifyNumber === -1)
        exerciseModifyNumber = nr - 1;
    exerciseModifyNumber++;
    let exerciseId = "exercise-" + exerciseModifyNumber;
    let ex = document.getElementById(exerciseId);
    ex.removeAttribute("hidden");
}

function removeModifyExercise(nr) {
    if(exerciseModifyNumber === -1)
        exerciseModifyNumber = nr - 1;
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

    exercises = {info: JSON.stringify(examData), exercises: JSON.stringify(exercises),
        id: examId};

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


function searchUser() {
    document.getElementById("searchButton").onclick = function () {
        var searchText = document.getElementById("searchBar").value;

        window.location.href = '?search=' + searchText;
    };
}


function startTimer(duration) {
    var timer = duration, hours, minutes, seconds;
    setInterval(function () {
        hours = parseInt(timer / 3600, 10);
        minutes = parseInt((timer / 60) % 60, 10);
        seconds = parseInt(timer % 60, 10);

        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        document.getElementById("hourss").innerHTML = hours;
        document.getElementById("mins").innerHTML = minutes;
        document.getElementById("secs").innerHTML = seconds;

        if (--timer < 0) {
            document.getElementById("submitExam").click();
        }
    }, 1000);
}

function addToList() {
    selectedTeacher = document.getElementById("course-teachers").value;
    teachersDropDown = document.getElementById("course-teachers");
    list = document.getElementById("selected-teachers");

    var spann = document.createElement("span");
    spann.classList.add("close");
    spann.innerHTML = "&times;";
    spann.id = "span-" + selectedTeacher;

    spann.onclick = function() {
        teacherName = spann.id.split("-")[1]
        var item = document.getElementById(teacherName);
        item.remove();

        var option = document.createElement("option");
        option.text = teacherName;
        option.value = teacherName;
        teachersDropDown.add(option);
    }
    var li = document.createElement("li");
    li.classList.add("list-group-item");
    li.classList.add("list-group-item-success");
    li.id = selectedTeacher;
    li.appendChild(document.createTextNode(selectedTeacher));


    li.appendChild(spann);

    list.appendChild(li);
    teachersDropDown.remove(teachersDropDown.selectedIndex);

}

function addCourse() {
    var courseName = document.getElementById("course-name").value;

    var lis = document.getElementById("selected-teachers").getElementsByTagName("li");
    teachers = [];
    for(let i = 0; i < lis.length; i++) {
        teachers.push(lis[i].id);
    }

    var year = document.getElementById("course-year").value;
    var semester = document.getElementById("course-semester").value;
    var credits = document.getElementById("course-credits").value


    result = {
        name: courseName,
        teachers: JSON.stringify(teachers),
        year: year,
        semester: semester,
        credits: credits
    };

    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $.ajax({
        type: 'POST',
        url: '/examgenerator/course/add',
        contentType: 'application/json',
        data: JSON.stringify(result),
    }).done(function () {
        window.location.href = "/examgenerator/home";
    })
}


function onRadioCollapse() {
    for (var i = 1; i < 4; i++) {
        var id = "examPenalty" + i.toString();
        var radio = document.getElementById(id);
        if(!radio.checked) {
            $('#collapsePenalty' + i.toString()).collapse('hide');
        }
    }
}
