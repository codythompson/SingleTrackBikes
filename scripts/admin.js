$(document).ready(function () {
    $('.st-image-upload').hide();
});

function toggleUploadForm(upId) {
    $('#' + upId).slideToggle();
}

function toggleImagesModal(modalId) {
    $('#' + modalId).modal('toggle');
}
