$(document).ready( function() {
    $('.st-bulletin-create').toggle();
});

function bboardCreateToggle(ele) {
    ele = $(ele);
    ele.parent().children('.st-bulletin-create').slideToggle();
}
