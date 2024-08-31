tinymce.init({
    selector: "#tinymce",
    plugins: "image link lists media",
    toolbar:
        "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat",
    menubar: false,
    paste_data_images: true,
    statusbar: false,

    images_upload_handler: function (blobInfo, success, failure) {
        // Fungsi penanganan unggah gambar, dapat diisi sesuai kebutuhan.
        // Di sini, kami mengembalikan false untuk menonaktifkan unggah gambar.
        return true;
    },
    ai_request: (request, respondWith) =>
        respondWith.string(() =>
            Promise.reject("See docs to implement AI Assistant")
        ),
});
