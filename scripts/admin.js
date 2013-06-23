$(document).ready(function () {
    //$('.st-image-upload').hide();
    $('.st-image-delete').hide();
});

function postEditorContent(textAreaId, hiddenFieldId) {
    var html = nicEditors.findEditor(textAreaId).getContent();
    $('#' + hiddenFieldId).val(html);
}

//image modal stuff
var ST_image_url_input_id;

function toggleUploadForm(upId) {
    $('#' + upId).slideToggle();
}

function toggleImagesModal(modalId) {
    $('#' + modalId).modal('toggle');
}

function showImagesModal(modalId, urlInput) {
    ST_image_url_input_id = urlInput;
    toggleImagesModal(modalId);
}

function selectImage(modalId, imgUrl) {
    $('#' + ST_image_url_input_id).val(imgUrl);
    toggleImagesModal(modalId);
}

function toggleDelDialog(button) {
    $(button).parent().children('.st-image-delete').slideToggle();
}

function cancelDelDialog(button) {
    $(button).parent().parent().slideUp();
}
