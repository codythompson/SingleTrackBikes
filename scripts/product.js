function carouselSlidePrev(carouselId) {
    $('#' + carouselId).carousel('prev');
}

function carouselSlideNext(carouselId) {
    $('#' + carouselId).carousel('next');
}

function carouselToggle(ele, carouselId) {
    ele = $(ele);
    var carousel = $('#' + carouselId);
    var imgEle = ele.find("img").eq(0);
    if (ele.hasClass('product-paused')) {
        carousel.carousel();
        ele.removeClass('product-paused');
        ele.attr('title', 'stop scrolling');
        imgEle.attr('src', '/images/pause-icon-hover.png');
        imgEle.attr('alt', 'stop scrolling');
    }
    else {
        carousel.carousel('pause');
        ele.addClass('product-paused');
        ele.attr('title', 'resume scrolling');
        imgEle.attr('src', '/images/play-icon-hover.png');
        imgEle.attr('alt', 'resume scrolling');
    }
}
