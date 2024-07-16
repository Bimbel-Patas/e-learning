$(document).ready(function () {
    $("#clearSearch").hide();

    function performSearch() {
        var searchValue = $("#search").val();

        const loading = `<div id="loadingIndicator" class="d-none">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`;
        $("#tableContent").html(loading);

        $("#loadingIndicator").removeClass("d-none");

        $.ajax({
            url: url, // Ganti dengan URL rute pencarian di server
            method: "GET",
            data: {
                search: searchValue,
            },
            success: function (data) {
                $("#tableContent").html(data);
                $("#clearSearch").show();
                if (searchValue == "") {
                    searchValue = "Semua";
                }
                // Mengosongkan input pencarian
                $("#btnClear").html(
                    searchValue + ' <i class="fa-solid fa-xmark"></i>'
                );
                $("#loadingIndicator").addClass("d-none");
            },
            error: function () {
                $("#loadingIndicator").addClass("d-none");
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
