
Voici une page de contact  avec piece jointe et recaptcha utilisable dans vos projets qio fonctionne avec O2Switch

Dans la page form.html ou form.php vous devez entrer vos clefs Recaptcha obtenues sur le site https://developers.google.com/recaptcha/intro

il faut créer des clefs pour la V2
https://developers.google.com/recaptcha/docs/display

Clefs de site et clef secret:

Vous devez renseigner les clefs  site  et secret dans le formulaire.
la clef ci-dessous fonctionne avec localhost et backhub.fr uniquement.
<!--  pour la sécurité tout html c'est pas top... -->
	<input type="text" name="secret" value="6LeGjCojAAAAAKu449zg0fOsbjsnbZ5UTdZjBK1b" hidden></p> 
	<!--  localhost 2022.tests.backhub.fr -->
	<input type="text" name="websitename" value="localhost" hidden></p>

Il faut aussi entrer la clef de site pour que le javascript affiche 
    <div class="g-recaptcha" data-sitekey="6LeGjCojAAAAAP6bYhafANfGn9as0wSXAdDre1_N">

Renseigner le sous domaine:

Important renseigner le sous domaine complet pour valider la clef de repcaptcha visiblement le domain ne suffit pas
	<!--  localhost 2022.tests.backhub.fr -->
	<input type="text" name="websitename" value="2022.tests.backhub.fr" hidden></p>

Recevoir une réponse par email:

	<!--  indiquer votre email pour recevoir une réponse à cette adresse -->
	<input type="text" name="email_sender" value="xavier@institutsolacroup.com" hidden></p>
	<!--  indiquer le sujet du email quue vous allez recevoir à votre adresse mail -->
	<input type="text" name="email_subject" value="Nouveau Contact de backhub.fr " hidden></p>