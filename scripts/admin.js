$(document).ready(function () {
    //$('.st-image-upload').hide();
    var editEles = $('.st-content-edit');
    $.each(editEles, function (i, obj) {
        obj = $(obj);
        if (!(obj.hasClass('st-content-open'))) {
            obj.hide();
        }
    });
    var delEles = $('.st-content-delete');
    $.each(delEles, function(i, obj) {
        obj = $(obj);
        if (!(obj.hasClass('.st-content-open'))) {
            obj.hide();
        }
    });

    $('.st-image-delete').hide();
});

function postEditorContent(textAreaId, hiddenFieldId) {
    var html = nicEditors.findEditor(textAreaId).getContent();
    $('#' + hiddenFieldId).val(html);
}

function hideContainer(containerId) {
    $('#' + containerId).slideUp();
}

function toggleContainer(containerId) {
    var ele = $('#' + containerId);
    if (ele.hasClass('st-content-open')) {
        ele.removeClass('st-content-open');
        ele.slideUp()
    }
    else {
        $('.st-content-edit').slideUp();
        $('.st-content-edit').removeClass('st-content-open');
        $('.st-content-delete').slideUp();
        $('.st-content-delete').removeClass('st-content-open');
        ele.addClass('st-content-open');
        ele.slideDown();
    }
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

