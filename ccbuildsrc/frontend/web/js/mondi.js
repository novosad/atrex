/**
 * Getting current month
 */

$(document).ready(function () {
        var time = new Date();
        // month
        var current = time.getMonth() + 1;

        // formate number month
        if (current < 10){
            current = '' + time.getMonth() + 1;
        }

        $("#contactform-name [value='" + current + "']").attr("selected", "selected");

        // years
        var year = time.getFullYear();
        $("#contactform-subject [value='" + year + "']").attr("selected", "selected");
    }
)