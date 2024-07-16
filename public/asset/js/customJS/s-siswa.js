$(document).ready(function () {
    $("#clearSearch").hide();

    var selectElement = document.getElementById("kelas");
    var selected = "Semua Kelas";
    selectElement.addEventListener("change", function () {
        var selectedIndex = selectElement.selectedIndex;
        var selectedOption = selectElement.options[selectedIndex];
        var selectedInnerHTML = selectedOption.innerHTML;
        selected = selectedInnerHTML;
        // console.log("Selected Option Inner HTML:", selectedInnerHTML);
    });

    function performSearch() {
        var searchValue = $("#search").val();
        var kelasValue = $("#kelas").val();

        const loading = `<div id="loadingIndicator" class="d-none">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`;
        $("#tableContent").html(loading);

        // Menampilkan animasi loading
        $("#loadingIndicator").removeClass("d-none");

        $.ajax({
            url: url, // Ganti dengan URL rute pencarian di server
            method: "GET",
            data: {
                search: searchValue,
                kelas: kelasValue,
            },
            success: function (data) {
                $("#tableContent").html(data);
                $("#clearSearch").show();
                if (searchValue == "") {
                    searchValue = "Semua";
                }
                $("#btnClear").html(
                    searchValue +
                        " di " +
                        selected +
                        '  <i class="fa-solid fa-xmark"></i>'
                );
                $("#loadingIndicator").addClass("d-none");
                console.log("load hilang");
            },
            error: function () {
                $("#loadingIndicator").addClass("d-none");
                console.log("load hilang");
            },
        });

        $("#badge").show(); // Menampilkan badge hasil pencarian
    }

    $("#btnSearch").click(function () {
        performSearch();
    });

    $("#search").keypress(function (e) {
        if (e.which == 13) {
            // 13 is the key code for Enter
            e.preventDefault();
            performSearch();
        }
    });

    $("#clearSearch").click(function () {
        $("#search").val(""); // Mengosongkan input pencarian

        $.ajax({
            url: url, // Ganti dengan URL rute pencarian di server
            method: "GET",
            data: {
                search: "",
            },
            success: function (data) {
                $("#tableContent").html(data);
            },
        });

        $("#clearSearch").hide(); // Menyembunyikan badge hasil pencarian
    });
});
