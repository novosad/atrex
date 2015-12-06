/**
 * ajax filtr news
 */

$(document).ready(function () {
    // loading current month and years
    var incident = $("#contactform-name :selected").val();
    var years = $("#contactform-subject :selected").val();
    $.ajax({
        type: "POST",
        url: "incident",
        data: {"incident": incident, "years": years},
        cache: false,
        success: function (data) {
            $(".events").html(data);
        }
    });

    // choice month
    $("#contactform-name").change(function () {
        var incident = $("#contactform-name :selected").val();
        var years = $("#contactform-subject :selected").val();
        $.ajax({
            type: "POST",
            url: "incident",
            data: {"incident": incident, "years": years},
            cache: false,
            success: function (data) {
                $(".events").html(data);
            }
        });
    });

    // choice years
    $("#contactform-subject").change(function () {
        var incident = $("#contactform-name :selected").val();
        var years = $("#contactform-subject :selected").val();
        $.ajax({
            type: "POST",
            url: "incident",
            data: {"incident": incident, "years": years},
            cache: false,
            success: function (data) {
                $(".events").html(data);
            }
        });
    });

});
