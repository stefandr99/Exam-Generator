
function filterStudentsResult(id) {
    let filterData = document.getElementById("filter-students").value;

    let route = "/examgenerator/exam/" + parseInt(id).toString() + "/statistics/filter=" + filterData.toString();

    id = parseInt(id).toString()

    $.ajax({
        type: "GET",
        url: "/examgenerator/home",
        success: function() {
            alert("Gata!");
        }
    });
}
