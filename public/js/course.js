
function addTeacherToUList() {
    selectedTeacher = document.getElementById("course-teachers").value;
    teachersDropDown = document.getElementById("course-teachers");
    list = document.getElementById("selected-teachers");

    var spann = document.createElement("span");
    spann.classList.add("close");
    spann.classList.add("text-center");
    spann.innerHTML = "&nbsp&times;";
    spann.id = "span-" + selectedTeacher;
    spann.style.cursor = 'pointer';
    spann.style.marginRight = '15px';

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
    li.classList.add("add-course-teachers-list");
    li.classList.add("list-group-item-success");
    li.classList.add("text-center");
    li.classList.add("align-items-center");
    li.style.width = '65px';
    li.id = selectedTeacher;
    li.appendChild(document.createTextNode(selectedTeacher));

    li.appendChild(spann);

    list.appendChild(li);
    teachersDropDown.remove(teachersDropDown.selectedIndex);

}


function addCourse() {
    var courseName = document.getElementById("course_name").value;

    var lis = document.getElementById("selected-teachers").getElementsByTagName("li");
    teachers = [];
    for(let i = 0; i < lis.length; i++) {
        teachers.push(lis[i].id);
    }

    var year = document.getElementById("course-year").value;
    var semester = document.getElementById("course-semester").value;
    var credits = document.getElementById("course-credits").value

    if(courseName === '' || year === '' || semester === '' || credits === '') {
        window.location.href = "/examgenerator/course/all";
        return;
    }

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
        window.location.href = "/examgenerator/course/all";
    })
}
