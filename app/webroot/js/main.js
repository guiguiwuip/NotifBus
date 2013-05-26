$(document).ready(function() {




/* -----------------------------------------------------------------


                    Variables Globales


 ----------------------------------------------------------------- */




var host  = "localhost";
var siteUrl  = "http://localhost/Notifbus";

var debug = true;

//Stocke la position de l'utilisateur
var lieu = {
    lat : null,
    lng : null,
    ok  : false
};

//Chemin vers logo pour notification
var img_notif = "";

var delaiAvantUpdate = 2000, //Temps entre demande position et lancement update (defaut: 10000)
    delaiEntreUpdate = 60000; //Temps entre demande chaque update

/* ELements Html */
var loader = $('div.loader'),
    body = $('body'),
    header = $('header');

var notifications = {},
    notifOn = true; //Notifaications activées ?





/* -----------------------------------------------------------------


                    CONNEXION et INSCRIPTION


 ----------------------------------------------------------------- */




/*
 * Connexion et inscription asynchrone
 *
 * Connecte ou inscrit un utilisateur à NotifBus puis lance l'affichage de la page d'accueil.
 * Si erreur de connexion, affichage d'un message d'erreur.
 */
$('form#UserLoginForm.async, form#UserInscriptionForm.async').submit(function(){

    var form = $(this);
    var data = form.serialize();

    loaderOn();

    /*
     * Requête AJAX
     */
    $.ajax({
        type : "POST",
        url  : form.attr('action'),
        data : data,
        success: function (response) {

            if(response == "Connecté." || response == "inscrit") { 
                
                if(response == "inscrit"){

                    flash(
                        {
                            title: 'Welcome aboard !',
                            content: 'L\'inscription à NotifBus s\'est bien passée. Vous pouvez commencer à créer vos arrêts favoris et ajouter des lieux.'
                        },
                        'green'
                    );
                }
                
                home();
            } 
            //Problème d'indentifiant ou de mot de passe
            else {        
                flash({title:"Erreur", content:response}, 'red');
                loaderOff();
            }

            if(debug) console.log(response);

        },
        //Crash du serveur ou pas de connexion
        error: function () {
            flash({title:"Erreur serveur", content:"Merci de réessayer."}, 'red');
            loaderOff();
        }

    });


	return false;
});

/*
 * Si on arrive sur la page d'accueil déjà connecté, on lance l'actualisation
 */
if(body.hasClass('home')) {
    actualisation();
}




/* -----------------------------------------------------------------


                            HOME


 ----------------------------------------------------------------- */



/**
 * home method
 *
 * Charge les éléments Html de la page d'accueil
 * Lance la boucle d'actu
 * 
 * @return {[type]} [description]
 */
function home() {

    //Chargement des éléments HTML
    $.ajax({
        type: "POST",
        url : siteUrl,
        success : function(html) {

            $('div.container-login').addClass('closed').after(html);

            actualisation();

        },
        error: function () {
            flash({title:"Erreur serveur", content:"Merci de réessayer."}, 'red');
        }
    });
}

/**
 * actualisation method
 *
 * Lancement de l'actualisation régulière de la page d'accueil
 * @return {[type]} [description]
 */
function actualisation() {

    //On réactualise les éléments html remarquables (si déconnexion, etc. ils ont changés)
    var loader = $('div.loader'),
        body = $('body'),
        header = $('header');

    //On lance une première actualisation de la page
    findPosition();

    //On attend delaiAvantUpdate millisec que la nouvelle position soit sauvegardée.
    setTimeout(function(){
        update();
    }, delaiAvantUpdate);


    //On lance une actualisation régulière
    updating = setInterval(function(){
        findPosition();

        //On attend 10 sec que la nouvelle position soit sauvegardée.
        setTimeout(function(){
            update();
        }, delaiAvantUpdate);

    }, delaiEntreUpdate); //toutes les delaiEntreUpdate millisec


    loaderOff();
}


/**
 * update method
 *
 * Mise à jour de la page d'accueil :
 *     - mise à jour des prochains passages
 *     - check si notifications à afficher
 * 
 * @return void
 */
function update() {

    loaderOn();

    var url = siteUrl + '/arrets/update/';
    //Si lieu défini, on l'envoi à Cake pour la gestion des notif
    if(lieu.ok) {
        url += lieu.lat +'/'+ lieu.lng;
    }

    if(debug) console.log("Demande d'update.");

    //Appel à Cake (renvoi les arrêts favoris ordonnés par temps d'attente et les notifications)
    $.ajax({
        type     : "POST",
        url      : url,
        dataType : 'json',
        success  : function (response) {

            if(debug) {
                console.log("Update reçue : ");
                console.log(response);
            }

            var arrets  = response.arrets,
                notifs  = response.notifications;
            var now = new Date();

            updateArrets(arrets);

            $.each(notifs, function(){

                //On crée la notification
                var notif = {
                    content : "Ligne " + 
                        this.Ligne.name + " direction " + this.Ligne.sens + 
                        ((this.Ligne.options != null) ? 
                            ", " + this.Ligne.options + ", " : 
                            " ") + 
                        "à " + this.Arret.name + 
                        ((this.attente > 0) ? 
                            " dans " + this.attente + " min." : 
                            " en ce moment ("+now.getHours()+"h"+ ( now.getMinutes()<10 ? '0' : '')+now.getMinutes() +")."
                        ),
                    tag     : this.Arret.id
                }

                //Notification vocale
                //@see https://github.com/hiddentao/google-tts
                
                //NOT WORKING

                /*if (!window.GoogleTTS) {
                   if(debug) console.log("GoogleTTS non disponnible.");
                } 
                else {
                    var googleTts = new window.GoogleTTS();

                    // play
                    setTimeout(function(){
                        googleTts.play(notif.content, 'fr', function(err) {
                            if (err) {
                                if(debug) console.log('GoogleTTS ne peut pas parler.');
                                console.dir(err);
                            }
                        });
                    }, 1000);
                }*/

                //On ajoute au notif courante (écrasement de la précédente notif du même arrêt)
                notifications[(notif.tag)] = notif;
                updateNotifMenu(); //on met à jour le menu

            })

            loaderOff();

        },
        //Crash du serveur ou pas de connexion
        error    : function () {
            if(debug) console.log("Erreur serveur dans la mise à jour de la page. " +url);
            loaderOff();

            //Probablement une fin de Session côté PHP, on redemande le login
            flash(
                {
                    title: "Erreur dans l'actualisation de la page", 
                    content: "Il peut s'agir d'une déconnexion vis-à-vis du serveur. Tenter une reconnexion ?"
                }, 
                'red',
                function() {
                    clearInterval(updating);
                    $('div.container-login').removeClass('closed');
                    $('div#container').remove();
                },
                'noConnect'
            );
        }

    });
}

/* -----------------------------------------------------------------


                            ARRETS


 ----------------------------------------------------------------- */

/**
 * Actualise les arrêts favoris listés sur la page d'accueil
 * @params arrets
 * @return void
 */
function updateArrets(arrets) {

    //On supprimer tous les arrets existant
    body.find('div.carte, div.wrap').animate({'height': 0}, 200).remove();

    var i = Object.keys(arrets).length; //Utilisé pour définir le z-index
    $.each(arrets, function(){
        var template = 
            '<div class="carte" data-id="'+this.Arret.id+'" style="z-index:'+i+';">'+
                '<div class="info navbar navbar-inverse">'+
                    '<div class="navbar-inner">'+
                        '<div class="nav-collapse collapse">'+

                           '<div class="container">'+

                                '<ul class="nav arret">'+
                                    '<li class="inline">'+
                                        this.Arret.name +
                                    '</li>'+
                                    '<li class="inline"> Ligne '+
                                        this.Ligne.name +
                                    '</li>'+
                                    '<li class="inline"> Vers '+
                                        this.sens +
                                    '</li>'+
                                    '<li class="inline">'+
                                         ( isNaN(this.prochainPassage) ? this.prochainPassage : (this.prochainPassage+'  min') )+
                                         '<span class="edit fui-settings-24"></span>'
                                    '</li>'+
                                '</ul>'+

                           '</div>'+

                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>';


        $('div#container').append(template);
        $(template).show(200);
        
        i--;
    });

    $('#container').append('<div class="wrap"><button id="addArret" class="btn btn-large btn-info">Ajouter un arret</button></div>')

}


//On clique sur le bouton édition d'un arret favoris
$(document).on('click', 'div.carte span.edit', editArret);

/**
 * editArret method
 *
 * Lancement de l'édition d'un arrêt
 * 
 * @param  arret 
 * @return void
 */
function editArret(event) {

    var arret = $(event.target).parents('div.carte');

    loaderOn();

    //On appèle Cake
    $.ajax({
        type     : "GET",
        url      : siteUrl+'/arrets/edit/'+$(arret).attr('data-id'),
        success  : function (response) {
            
            //On cache les infos
            $(arret).find('.navbar.info').hide(200).after(response);

            //On lance zelect sur les input
            $(arret).find('#ArretArret').zelect({
                placeholder : "Arret"
            });
            $(arret).find('#ArretLigneId').zelect({
                placeholder : "Ligne"
            });
            $(arret).find('#ArretSens').zelect({
                placeholder : "Sens"
            });
            $(arret).find('#ArretOptions').zelect({
                placeholder : "Options"
            });

            loaderOff();
        },
        error    : function (err) {
            if(debug) console.log("Erreur serveur dans l'edit de l'arrêt");
            loaderOff();

            flash(
                {
                    title: "Erreur dans l'actualisation de la page", 
                    content: "Il peut s'agir d'une déconnexion vis-à-vis du serveur. Tenter une reconnexion ?"
                }, 
                'red',
                function() {
                    clearInterval(updating);
                    $('div.container-login').removeClass('closed');
                    $('div#container').remove();
                },
                'noConnect'
            );
        }
    });
    
}

/*
 * On met à jour le nom d'un arret, on charge les lignes correspondantes
 */
$(document).on('change', 'div.carte div.edit #ArretArret', function(event) {

    loaderOn();
    var arret = $(event.target).parents('div.carte');
    var idTan = $(event.target).val();

    //On appel Cake
    $.ajax({
        type     : "GET",
        url      : siteUrl+'/lignes/lignes/'+idTan,
        success  : function (response) {

            //On parse le json en objet javascript
            var response = JSON.parse(response);

            //On vide le select
            $(arret).find('#ArretLigneId').empty();

            //On remplit avec les nouvelles data
            $.each(response, function() {
                $(arret).find('#ArretLigneId').append($('<option>').text(this.text).attr('value', this.id));
            });
            
            //On relance zelect
            $(arret).find('#ArretLigneId').parent().find('.zelect').remove();
            $(arret).find('#ArretLigneId').zelect({
                placeholder : "Ligne"
            });

            //On vide aussi le sens
            $(arret).find('#ArretSens').empty().parent().find('.zelect').remove();
            $(arret).find('#ArretSens').zelect({
                placeholder : "Sens"
            });

            loaderOff();
        },
        error    : function (err) {
            if(debug) console.log("Erreur serveur dans l'edit de l'arrêt");
            loaderOff();
        }
    });

});

/* 
 * On met à jour la ligne d'un arret, on charge les sens correspondants
 */
$(document).on('change', 'div.carte div.edit #ArretLigneId', function(event) {

    loaderOn();
    var arret = $(event.target).parents('div.carte');
    var idTan = $(event.target).val();

    //Appel à Cake
    $.ajax({
        type     : "GET",
        url      : siteUrl+'/lignes/sens/'+idTan,
        success  : function (response) {

            //On parse le json en objet javascript
            var response = JSON.parse(response);

            //On vide le select
            $(arret).find('#ArretSens').empty();

            //On remplit avec les nouvelles data
            $.each(response, function() {
                $(arret).find('#ArretSens').append($('<option>').text(this.text).attr('value', this.id));
            });
            
             //On relance zelect
            $(arret).find('#ArretSens').parent().find('.zelect').remove();
            $(arret).find('#ArretSens').zelect({
                placeholder : "Sens"
            });

            loaderOff();
        },
        error    : function (err) {
            if(debug) console.log("Erreur serveur dans l'edit de l'arrêt");
            loaderOff();
        }
    });

});

/*
 * On met à jour le sens d'un arret, on charge les options correspondantes
 */
$(document).on('change', 'div.carte div.edit #ArretSens', function(event) {

    loaderOn();
    var arret = $(event.target).parents('div.carte');
    var idTan = $(arret).find('#ArretLigneId').val();
    var sens = $(arret).find('#ArretSens').val();

    //Appel à CAke
    $.ajax({
        type     : "GET",
        url      : siteUrl+'/terminus/options/'+idTan+'/'+sens,
        success  : function (response) {

            //On parse le json en objet javascript
            var response = JSON.parse(response);

            //On vide le select
            $(arret).find('#ArretOptions').empty();

            //On remplit avec les nouvelles data
            $(arret).find('#ArretOptions').append($('<option>').text('Pas d\'options').attr('value', 0)); //"Pas d'options"
            $.each(response, function() {
                $(arret).find('#ArretOptions').append($('<option>').text(this.name).attr('value', this.id));
            });
            
            //On relance zelect
            $(arret).find('#ArretOptions').parent().find('.zelect').remove();
            $(arret).find('#ArretOptions').zelect({
                placeholder : "Options"
            });

            loaderOff();
        },
        error    : function (err) {
            if(debug) console.log("Erreur serveur dans l'edit de l'arrêt");
            loaderOff();
        }
    });

});

/*
 * On ajoute un créneau horaire à un arret
 */
$(document).on('click', 'div.carte div.edit span.fui-plus-24', function(event) {

    var arret = $(event.target).parents('div.carte');
    var ul    = $(event.target).parents('ul.horaires');
    
    //id du nouveau créneau
    var nb = parseInt(ul.attr('data-nb'))+1;

    var template = 
        '<ul class="nav horaires" data-nb="'+nb+'">' +
            '<li class="inline">' +
                '<div class="input time required">'+

                    '<select name="data[Horaire]['+nb+'][start][hour]" id="Horaire'+nb+'StartHour">'+
                        '<option value="00">0</option><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>'+
                    '</select>'+
                    ':'+
                    '<select name="data[Horaire]['+nb+'][start][min]" id="Horaire'+nb+'StartMin">'+
                        '<option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>'+
                    '</select>'+
                
                '</div>'+
           '</li>'+
            '<li class="inline">'+
                '<div class="input time required">'+

                    '<select name="data[Horaire]['+nb+'][end][hour]" id="Horaire'+nb+'EndHour">'+
                        '<option value="00">0</option><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>'+
                    '</select>'+
                    ':'+
                    '<select name="data[Horaire]['+nb+'][end][min]" id="Horaire'+nb+'EndMin">'+
                        '<option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>'+
                    '</select>'+

                '</div>'+
            '</li>'+
            '<li class="inline"><span class="fui-plus-24"></span></li>'+            
        '</ul>'
        ;

    //On insère à la suite du dernier créneau
    $(ul).after(template);

    //On supprime les autres '+' des créneaux précédants 
    $(arret).find('ul.horaires:not([data-nb="'+nb+'"]) span.fui-plus-24').remove();

});

/*
 * On enregistre l'arrêt
 */
$(document).on('submit', 'div.carte form', function() {

    var arret = $(event.target).parents('div.carte');
    var id    = $(arret).attr('data-id');

    //Value des input
    var idArret = $(arret).find('#ArretArret').val();
    var ligne   = $(arret).find('#ArretLigneId').parents('.select').find('.zelected').text();
    var sens    = $(arret).find('#ArretSens').parents('.select').find('.zelected').text();

    //On empèche la submission vide
    if(
        idArret != 'Arret' 
        && ligne != 'Ligne' 
        && sens != 'Sens' 
        )
    {

        loaderOn();

        //Appel à Cake
        $.ajax({
            type     : "POST",
            url      : siteUrl+'/arrets/edit/' + ((id) ? id : ''),
            data     : $(this).serialize(),
            success  : function (response) {

                if(response == "Arrêt sauvegardé.") {

                    //On lance l'update
                    update();
                } 
                else 
                {
                    flash(
                        {
                            title  : "Erreur dans l'édition de l'arrêt.", 
                            content: response
                        }, 
                        'red',
                        function() {
                            clearInterval(updating);
                            $('div.container-login').removeClass('closed');
                            $('div#container').remove();
                        },
                        'noConnect'
                    );
                }

                loaderOff();
            },
            error    : function (err) {
                if(debug) console.log("Erreur serveur dans l'add de l'arrêt");
                loaderOff();

                flash(
                    {
                        title  : "Erreur dans l'édition de l'arrêt.", 
                        content: "Il peut s'agir d'une déconnexion vis-à-vis du serveur. Tenter une reconnexion ?"
                    }, 
                    'red',
                    function() {
                        clearInterval(updating);
                        $('div.container-login').removeClass('closed');
                        $('div#container').remove();
                    },
                    'noConnect'
                );
            }
        });
    }

    return false;
});


/*
 * On ferme un volet d'édition d'un arrêt favoris
 */
$(document).on('click', 'div.carte div.edit span.fui-cross-24', function() {

    var arret = $(event.target).parents('div.carte');

    $(arret).find('div.edit').hide(200).remove();
    $(arret).find('div.info').show();
});


/*
 * Nouvel arrêt
 */
$(document).on('click', '#addArret', function() {
   
    loaderOn();

    //Appel à Cake
    $.ajax({
        type     : "GET",
        url      : siteUrl+'/arrets/edit/',
        success  : function (response) {

            //On crée la carte
            $('#container').find('.wrap').before('<div class="carte" style="z-index:0;"></div>');
            var arret = $('#container').find('div.carte').last();
            
            //On insère le formulaire
            $(arret).html(response).show(200);

            //On lance zelect
            $(arret).find('#ArretArret').zelect({
                placeholder : "Arret"
            });
            $(arret).find('#ArretLigneId').zelect({
                placeholder : "Ligne"
            });
            $(arret).find('#ArretSens').zelect({
                placeholder : "Sens"
            });
            $(arret).find('#ArretOptions').zelect({
                placeholder : "Options"
            });

            loaderOff();
        },
        error    : function (err) {
            if(debug) console.log("Erreur serveur dans l'add de l'arrêt");
            loaderOff();

            flash(
                {
                    title  : "Erreur dans la création de l'arrêt.", 
                    content: "Il peut s'agir d'une déconnexion vis-à-vis du serveur. Tenter une reconnexion ?"
                }, 
                'red',
                function() {
                    clearInterval(updating);
                    $('div.container-login').removeClass('closed');
                    $('div#container').remove();
                },
                'noConnect'
            );
        }
    });


});


/* -----------------------------------------------------------------


                            NOTIFICATIONS


 ----------------------------------------------------------------- */



/*
 * L'utilisateur demande à ne plus être notifié pour un arrets pour le créneau horaire courant
 * = il a prit le bus/tram
 */
$(document).on('click', 'li.notif ul.actions li.nePlusMeRappeler', function(){
    
    var tag = $(this).attr('data-tag');

    loaderOn();

    $.ajax({
        type     : "POST",
        url      : siteUrl+'/arrets/nePlusMeRappeler/'+tag,
        success  : function (response) {
            if(response == 'ok') {
                if(debug) console.log('Arret '+tag+' marqué comme nePlusMeRappeler.');
            }

            loaderOff();
        },
        error    : function() {
            if(debug) console.log("Erreur serveur dans l'opération");
            loaderOff();
        }
    });

    delete notifications[tag];

    updateNotifMenu();
    
});

/*
 * L'utilisateur indique avoir prit le transport
 */
$(document).on('click', 'li.notif ul.actions li.fermer', function(){
    
    var tag = $(this).attr('data-tag');

    delete notifications[tag];

    updateNotifMenu();
    
});

/*
 * L'utilisateur ouvre/ferme le menu de notifications
 * Ainsi le menu peut rester ouvert sans survol
 * @see style.css header ul.opened
 */
$('body.accueil li.notifMenu a').click(function(){

    if($(this).parent().find('ul.wrapper').hasClass('opened')) {
        $(this).parent().find('ul.wrapper').removeClass('opened');
    } else {
        $(this).parent().find('ul.wrapper').addClass('opened');
    }

    return false; //On empèche le lien de fonctionner
});

/*
 * L'utilisateur désactive/active les notification
 */
$(document).on('click', 'li.notifMenu ul.wrapper li.onOff div.toggle', function(){
    
    if($(this).hasClass('toggle-off')) {
        notifOn = false;
    } else {
        notifOn = true;
    }
    
});


/**
 * updateNotifMenu method
 *
 * Mettre à jour le menu "Notifbus" :
 * - petit rond comptant les notifs non vues
 * - insertion des notifications
 * 
 * @return void
 */
function updateNotifMenu() {

    if(debug) {
        console.log('Notifications courantes :');
        console.log(notifications);
    }

    //Si l'utilisateur n'a pas désactiver les notifications
    if(notifOn) {

        var noNotif = '<li class="noNotif"> <a href="#">Pas de Notificiation.</a> </li>';

        //Nombre de notifications 
        var nbNotif = 0;

        for (var i in notifications) {
            if (notifications.hasOwnProperty(i)) {
                nbNotif++;
            }
        }

        //Des notifications a afficher
        if(nbNotif > 0) {
            //Affichage du nombre
            $('li.notifMenu').find('a.title').html('Notifbus <span class="navbar-unread">'+nbNotif+'</span>');

            //On enlève les notification précédente
            $('li.notifMenu').find('li.noNotif, ul li.notif').remove();

            //On insère chaque nouvelle notification et joue le tts
            $.each(notifications, function(){
                $('li.notifMenu').find('ul li.onOff').before('<li class="notif '+this.tag+'">'+
                        '<a href="#">'+this.content+'</a>' +
                        '<ul class="actions">'+
                            '<li class="nePlusMeRappeler" data-tag="'+this.tag+'"><a href="#">Ne plus me le rappeler d\'ici le prochain créneau !</a></li>' +
                            '<li class="fermer" data-tag="'+this.tag+'"><a href="#">Merci de l\'info !</a></li>' +
                        '</ul>'+
                    '</li>'
                );

            });

            //Ouverture du menu
            //$('li.notifMenu').find('ul.wrapper').addClass('opened');
        } 
        //Pas de notification a afficher
        else {
            $('li.notifMenu').find('a.title span').remove();
            $('li.notifMenu').find('ul li.notif').remove();
            $('li.notifMenu').find('ul li.onOff').before(noNotif);
        }
    }
    //L'utilisateur a désactivé les notifications
    else {
        $('li.notifMenu').find('li.noNotif, ul li.notif').remove();

        var noNotif = '<li class="noNotif"> <a href="#">Notifications désactivées.</a> </li>'; 
        $('li.notifMenu').find('ul li.onOff').before(noNotif);

        $('li.notifMenu ul.wrapper li.onOff div.toggle').addClass('toggle-off');
    }

}




/* -----------------------------------------------------------------


                            POSISTION


 ----------------------------------------------------------------- */



/*
 * Aide à la création d'un nouveau lieu
 *
 * Remplit les champs lattitude et longitude avec la position actuelle de l'appareil
 */

$('button.setPosition').click(findPosition);

/**
 * position method
 *
 * Utilise la geolocalisation HTML5
 * @return array | false
 */
function findPosition() {

    loaderOn();

    if(navigator.geolocation) {

        var options = {
            enableHighAccuracy : true, //Autorise le GPS
            timeout            : 9000, //9s avant error
            maximumAge         : 0 //Pas de mise en cache
        };
        
        navigator.geolocation.getCurrentPosition(savePosition, errorPosition, options);

        if(debug) console.log("Demande de position.");

    }
    else {
        if(debug) console.log("Geolocalisation non supportée.");
        loaderOff();
        return false;
    }

}

/**
 * savePosition method
 * @return void
 */
function savePosition(position) {
    //On stock en variable
    lieu.lat = position.coords.latitude;
    lieu.lng = position.coords.longitude;
    lieu.ok  = true;

    if(debug) {
        console.log("Position = "); 
        console.log(lieu);
    }


    //On remplit les champs lat et lng présents si besoin
    if($('input.inputLat').length > 0) {

        $('input.inputLat').val(lieu.lat);
        $('input.inputLng').val(lieu.lng);

        if(debug) console.log("Input lat et lng remplis.");
    }
    loaderOff();
}

/**
 * errorPosition method
 * @return void
 */
function errorPosition() {

    lieu.ok = false;

    if(debug) console.log("Echec position.");

    loaderOff();
}




/* -----------------------------------------------------------------


                            FLASH


 ----------------------------------------------------------------- */



/**
 * flash method
 *
 * Ouverture d'une bannière d'information en haut de page.
 * Plusieurs status dispo correspondant à la couleur de la bannière (rouge, vert)
 *
 * @return void
 */
function flash(msg, status, next, tag){

    //Construction du message
    if(typeof msg == 'object') {
        var contenu = 
            '<h1>'+msg.title+'</h1>'+
            '<p>'+msg.content+'</p>' ;
    } else {
        var contenu = '<h1>'+msg+'</h1>';
    }

    //Si on a besoin d'un choix utilisateur 
    var actions = '';
    if(next) {
        actions = '<div class="button">'+
                '<button class="btn btn-large next">Ok</button>'+
                '<button class="btn btn-large">Fermer</button>'+
            '</div>';
    }

    if(tag) {
        $('div.container-flash div.flash.'+tag).hide(200).remove();
    } else {
        tag = '';
    }

    //On insert le flash
    $('div.container-flash').prepend(
        '<div class="flash '+status+' '+tag+'">'+
            '<span class="close fui-cross-16"></span>'+
            contenu+
            actions+
        '</div>'
        ).fadeIn(200)
    //On affiche
    .addClass('open');

    //On écoute le click sur le bouton d'action
    if(next) {
        $('div.container-flash button.next').click(function(){
            next();
            $(this).parents('div.flash').hide(200);
        })
    }

    if(debug) {
        console.log("Flash "+status+ " : ");
        console.log(msg);
    }
}

    //On écoute le click sur le bouton close et la croix
$(document).on('click', 'div.container-flash div.flash span.close, button:not(.next)', function(){
        $(this).parents('div.flash').hide(200);
});


/**
 * loaderOn method
 * Affiche le loader
 * @return void
 */
function loaderOn() {
    loader.addClass('activ');
}
/**
 * loaderOff method
 * Cache le loader
 * @return void
 */
function loaderOff() {
    loader.removeClass('activ');
}



});