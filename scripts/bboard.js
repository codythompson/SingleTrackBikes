$(document).ready( function() {
    $('.st-bulletin-create').toggle();
    $('.st-bulletin-delete').toggle();
    $('.st-bulletin-edit').toggle();
});

function bboardCreateToggle(ele) {
    ele = $(ele);
    ele.parent().children('.st-bulletin-create').slideToggle();
}

function bboardEditToggle(ele) {
    ele = $(ele);
    ele.parent().children('.st-bulletin-edit').slideToggle();
    ele.parent().children('.st-bulletin-edit-active').slideToggle();
    ele.parent().children('.st-bulletin-delete').hide();
    ele.parent().children('.st-bulletin-delete-active').hide();
}

function bboardEditHide(ele) {
    $(ele).parent().parent().parent().slideToggle();
}

function bboardDeleteToggle(ele) {
    ele = $(ele);
    ele.parent().children('.st-bulletin-delete').slideToggle();
    ele.parent().children('.st-bulletin-delete-active').slideToggle();
    ele.parent().children('.st-bulletin-edit').hide();
    ele.parent().children('.st-bulletin-edit-active').hide();
}

function bboardDeleteHide(ele) {
    $(ele).parent().parent().parent().slideToggle();
}
