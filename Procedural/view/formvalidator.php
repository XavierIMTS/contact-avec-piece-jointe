  <!-- ce fichier est amené à disparaitre et à être remplacer par du javascript -->

<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content=
        "width=device-width, initial-scale=1.0">
  
    <!-- CSS file -->
    
	<link rel="stylesheet" href="resources/css/stylerecaptcha.css">
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<?php 

	include "../controler/sendemail.php"; 

?>
<body>
<div class="container">
        <!-- HTML Form -->

<form method="post" action='../controler/sendemail.php' enctype="multipart/form-data"><!-- il est obligatoire d'utiliser  enctype="multipart/form-data" pour que le transfert de fichiers fonctionne -->

	<p><b>Mail<sup style="color:red">*</sup>:</b><br>
	<input type="text" name="mail" value="<?php echo isset($_POST['mail'])?htmlentities($_POST['mail'],ENT_QUOTES,"UTF-8"):''; ?>"></p>
	<p><b>Nom<sup style="color:red">*</sup>:</b><br>
	<input type="text" name="nom" value="<?php echo isset($_POST['nom'])?htmlentities($_POST['nom'],ENT_QUOTES,"UTF-8"):''; ?>"></p>
	<p><b>Prénom<sup style="color:red">*</sup>:</b><br>
	<input type="text" name="prenom" value="<?php echo isset($_POST['prenom'])?htmlentities($_POST['prenom'],ENT_QUOTES,"UTF-8"):''; ?>"></p>
	<p><b>Entreprise<sup style="color:red">*</sup>:</b><br>
	<input type="text" name="entreprise" value="<?php echo isset($_POST['entreprise'])?htmlentities($_POST['entreprise'],ENT_QUOTES,"UTF-8"):''; ?>"></p>
	<p><b>Téléphone<sup style="color:red">*</sup>:</b><br>
	<input type="text" name="telephone" value="<?php echo isset($_POST['telephone'])?htmlentities($_POST['telephone'],ENT_QUOTES,"UTF-8"):''; ?>"></p>
	
	
	<p><b>Message<sup style="color:red">*</sup>:</b><br>
	<textarea name="message" cols="40" rows="20"><?php echo isset($_POST['message'])?htmlentities($_POST['message'],ENT_QUOTES,"UTF-8"):'Bonjour,'; ?></textarea></p>
	
	<p><b>Pièces jointes:<?php echo $UPLOAD['fichiers_min']>0?'<sup style="color:red">*</sup>':''; ?></b><br>
	
	<?php
	echo "Extensions acceptées: <i>",implode(", ",array_keys($UPLOAD['extensions_autorisees'])),"</i><br>";
	for($i=1;$i<=$UPLOAD['fichiers_max'];$i++){//on affiche autant d'input file qu'on à choisi de fichiers maximum
		echo '<input type="file" name="fichiers[]">';
	}
	?></p><br>
	<div class="g-recaptcha" data-sitekey="6LeGjCojAAAAAP6bYhafANfGn9as0wSXAdDre1_N"></div>
            <br>
	<input  type="submit" name="envoyer" value="Envoyer!" >
	
</form>
</div>
</body>
    
</html>