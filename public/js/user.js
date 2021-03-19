
function searchUser() {
    document.getElementById("searchButton").onclick = function () {
        var searchText = document.getElementById("searchBar").value;

        window.location.href = '?search=' + searchText;
    };
}
