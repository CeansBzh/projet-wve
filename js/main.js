//Navbar

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

//Popup modal

var modal = document.querySelector(".modal");
var trigger = document.querySelector(".popupModalOuvre");
var closeButton = document.querySelector(".popupModalFerme");

function toggleModal() {
    modal.classList.toggle("show-modal");
}

function windowOnClick(event) {
    if (event.target === modal) {
        toggleModal();
    }
}

trigger.addEventListener("click", toggleModal);
closeButton.addEventListener("click", toggleModal);
window.addEventListener("click", windowOnClick);

//Date de fin inconnue => réinitialisation du champ date



$(function() {
    $("#date_fin_inconnue").on("change", function() {
        $("#date_fin").val("YYYY-MM-DD");
    });
});