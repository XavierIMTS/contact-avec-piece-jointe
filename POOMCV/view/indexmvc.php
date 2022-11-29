<link href="../resources/css/form.css" rel="stylesheet">


<?php 
//require_once("../model/Formdata.class.php");
require_once("../model/SendEmailUploadfile.class.php");
require_once("../controler/SendEmailCtrl.class.php");

/*
Debug pour les variable $_POST et $_FILE
echo "<pre>";
echo "Index ";
echo "_POST: ";
    var_dump($_POST);
    echo "_FILE: ";
    var_dump($_FILES);
echo "</pre>";
*/

$SendEmailCtrl = new SendEmailCtrl($_POST);

if(!empty($_FILES)){ // verifie si $_FILES (sperglobale) est inialisé. Il faut enctype="multipart/form-data" dans le formumalire.
   
    $SendEmailCtrl->get_email_data(); // renvoie le tableau et debug
    $SendEmailCtrl->sendemail(); 
    /*
    Comme j'ai eu des problèmes avec la gestion des $  illisible des variable. J'ai déjà testé directement de la view au model
    Le MVC ne veut pas que l'on fasse cela mais parfois c'est pratique :)
    $SendEmailUploadfile = new SendEmailUploadfile( $_POST, $_FILES);  
    $SendEmailUploadfile->get_data();
    $SendEmailUploadfile->get_files();

    $SendEmailUploadfile->Send();
     */
}

?>
<!--  creation d'un formulaire en MVC
utilise la class controleur SendEmailCtrl.class.php
qui appelle la class SendEmailUploadfile.class.php
-->
<form method="post" action="indexmvc.php" enctype="multipart/form-data" >
<?php
    echo $SendEmailCtrl->input('name', "Nom", true);
    echo $SendEmailCtrl->input('firstname', "Prénom", true);
    echo $SendEmailCtrl->input('mail', "Email", true);
    echo $SendEmailCtrl->input('compagny', "Entreprise");

    $arrayindicatif = $SendEmailCtrl->getCountryCode(); // convertir le array countrycode dans un format $key => $value;
    echo $SendEmailCtrl->SelectWithOptions('Indicatif', 'Indicatif ?' , $arrayindicatif);
    echo $SendEmailCtrl->input('phonenumber', "Téléphone");


    echo $SendEmailCtrl->textarea('message',"Message");
    echo $SendEmailCtrl->inputfile();

// debug l'objet si besoin
//var_dump($SendEmailCtrl);

//die(); // fonction qui arrête le script ici. pratique pour faire des tests

    echo $SendEmailCtrl->submit();

?>
</form>

