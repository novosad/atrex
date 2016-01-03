/**
 * Select price
 */


$(document).ready(function () {
    // loading current start and finish
    var start = $("#sel_start").val();
    var finish = $("#sel_finish").val();
    $.ajax({
        type: "POST",
        url: "band",
        data: {"start": start, "finish": finish},
        cache: false,
        success: function (data) {
            $(".selection_view").html(data);
        }
    });
    // press start
    $("#sel_start").keyup(function () {
        var start = $("#sel_start").val();
        var finish = $("#sel_finish").val();
        $.ajax({
            type: "POST",
            url: "band",
            data: {"start": start, "finish": finish},
            cache: false,
            success: function (data) {
                $(".selection_view").html(data);
            }
        });
    });
    // press finish
    $("#sel_finish").keyup(function () {
        var start = $("#sel_start").val();
        var finish = $("#sel_finish").val();
        $.ajax({
            type: "POST",
            url: "band",
            data: {"start": start, "finish": finish},
            cache: false,
            success: function (data) {
                $(".selection_view").html(data);
            }
        });
    });
});