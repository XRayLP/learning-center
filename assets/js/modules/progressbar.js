$(document).ready(function(){
    var percent = $('.progress-bar').attr("aria-valuenow");
    var degree = $('.progress-circle').attr("aria-degree");
    if (degree < 180) {
        $('.progress-right .progress-bar ').css("transform", "rotate(" + (degree) +"deg)");
    } else {
        $('.progress-right .progress-bar ').css("transform", "rotate(180deg)");
        $('.progress-left .progress-bar ').css("transform", "rotate(" + (degree - 180) + "deg)");
    }
});
