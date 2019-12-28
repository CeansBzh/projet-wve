$(function() {
    $(".toggle").on("click", function() {
        if ($(".item").hasClass("visible")) {
            $(".item").removeClass("visible");
            $(this).find("a").html("<i class='fas fa-bars'></i>");
        } else {
            $(".item").addClass("visible");
            $(this).find("a").html("<i class='fas fa-times'></i>");
        }
    });
});