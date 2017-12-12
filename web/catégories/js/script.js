"use strict";
$(document).ready(function() {
   
    var choix = $("#choix");
    // champ de formulaire "menu déroulant"
    
    var msg = $("#messgVariete");
    // champ de formulaire "zone texte à remplir"
    
    var erreurs = false;
    // variable permettant de vérifier s'il reste des erreurs avant l'envoi du formulaire
    
    
    $("#demandeFruitsLegumes").on("submit", function(e) {
        e.preventDefault();
        // attends que j'ai fait mes vérifs, n'envoie pas le formulaire
        
        if (choix.val().length == 1) {
            choix.parent().addClass("has-error");
            erreurs = true;
        } else {
            choix.parent().addClass("has-success");
        }    

        if (msg.val().length < 15) {
            msg.parent().addClass("has-error");
            erreurs = true;
        } else {
            msg.parent().addClass("has-success");
        }
        
        if (erreurs == false) {
            /*$.ajax({
                type: "post",
                url: "script-formulaire.php",
                data: $("#demandeFruitsLegumes").serialize(),
                success: function() {*/
                    $(this).replaceWith("<div class='alert alert-success'> Votre demande a été envoyée avec succès ! </div>");
                /*}
            });*/
        }
        
    });
    
    choix.on("change", function() {
        $(this).parent().removeClass("has-success has-error");
        erreurs = false;
        
        /* Vérification du choix en "temps réel" : pendant la saisie de l'utilisateur
        
        if (choix.val().length == 0) {
            choix.parent().addClass("has-error");
        } else {
            choix.parent().addClass("has-success");
        }*/
        
    });
    
    msg.on("change", function() {
        $(this).parent().removeClass("has-success has-error");
        erreurs = false;
    });
    
    
});