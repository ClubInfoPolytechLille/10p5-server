$(function(){
    $('.button-collapse').sideNav();
    $('.modal-trigger').leanModal()
    Materialize.toast("Connecté en tant que vigou", 4000);
}); // end of document ready

function vendu() {
    var interieur = $("<span>").text("Vendu 1 bière à KAE1EET2YI (15,30 € → 13,50 €) ").append(
            $("<a>").attr("href", "#!").text("Annuler")
        );
    Materialize.toast(interieur, 4000);
}

function soldeInsuffisant() {
    $("#soldeInsuffisant").openModal();
}
