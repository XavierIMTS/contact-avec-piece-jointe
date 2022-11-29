<?php

//vous pouvez paramètrer ci-dessous:
$UPLOAD['fichiers_max']=1;//nombre de fichier maximum pouvant être proposé
$UPLOAD['fichiers_min']=0;//nombre de fichier minimum devant être proposé (0 = le formulaire peut être envoyé même si il y aucun fichier de proposé
$UPLOAD['dossier_pieces_jointes']='../pieces-jointes/';//dossier de destination des fichiers, avec le / à la fin
$UPLOAD['taille_fichier_max']=5;//en Mo (peut être utilisé avec un point pour une valeure en dessous de 1mo, exemple pour 500ko: 0.5
$UPLOAD['garder_meme_nom']=1;//si le nom doit être remplacé par un nom aleatoire ou si il doit être identique à l'original (1=oui, 0=non)
$UPLOAD['nettoyer_le_nom']=1;//si les caractères spéciaux doivent être remplacés par un - et les majuscules en minuscules (1=oui, 0=non)
$UPLOAD['garder_fichier']=1;//si le fichier doit être gardé sur le serveur, si oui, un timestamp sera ajouté au nom pour pas qu'il soit écrasé si il y a une autre pièce jointe avec le même nom(1=oui, 0=non)



//laissez les extensions que vous souhaitez seulement autoriser,
// une liste exhaustive officielle pour l'ensemble des types MIME est disponible à l'adresse suivante :
// https://www.iana.org/assignments/media-types/media-types.xhtml
$UPLOAD['extensions_autorisees']=array(
	".pdf"=>"application/pdf",
	".png"=>"image/png",
	".jpg"=>"image/jpg",
	".jpeg"=>"image/jpeg",
	);



		//on passe aux pièces jointes si il y en a
		if(!empty($_FILES)){
			//variable d'initiation, ne pas toucher
			$UPLOAD['nbr_fichiers']=0;
			$UPLOAD['erreur']=0;
			$UPLOAD['msg_erreur']="";
			$UPLOAD['fichiers']=array();
			$ArrayNomFichier=[];//pour éviter l'ajout d'un fichier deux fois
			//on fait la verif des photos si il y en a:
			if(count($_FILES["fichiers"]['name'])>$UPLOAD['fichiers_max']){
				$UPLOAD['erreur']=1;
				$UPLOAD['msg_erreur'].="&bull; Nombre de fichier incorrect.<br />";
			} else {
				for($i=0;$i<=count($_FILES["fichiers"]['name'])-1;$i++) {
					if($_FILES["fichiers"]['name'][$i]!=""){//si aucun fichier proposé on lance pas un for pour rien
						if(in_array($_FILES["fichiers"]['name'][$i],$ArrayNomFichier)){
							$UPLOAD['erreur']=1;
							$UPLOAD['msg_erreur'].="&bull; Des fichiers sont identiques.<br />";
						} else {
							$ArrayNomFichier[]=$_FILES["fichiers"]['name'][$i];
							if($_FILES["fichiers"]['error'][$i]!=4){// 4. Aucun fichier proposé
								if($_FILES["fichiers"]['error'][$i]==0){// 0. Aucune erreur, le téléchargement est correct. 
									if(!in_array($_FILES["fichiers"]['type'][$i],$UPLOAD['extensions_autorisees'])){
										$UPLOAD['erreur']=1;
										$UPLOAD['msg_erreur'].="&bull; Le format du fichier ".($i+1)." est incorrect (seulement les extensions suivantes: <i>".implode(", ",array_keys($UPLOAD['extensions_autorisees']))."</i>, sont acceptées).<br />";
									} elseif(($_FILES["fichiers"]['size'][$i]/1000000)>$UPLOAD['taille_fichier_max']){
										$UPLOAD['erreur']=1;
										$UPLOAD['msg_erreur'].="&bull; Le fichier ".($i+1)." excède la taille maximale de ".$UPLOAD['taille_fichier_max']."Mo, veuillez diminuer sa taille ou en choisir un autre.<br />";
									} else {
										$ext=strtolower(substr($_FILES["fichiers"]['name'][$i],strrpos($_FILES["fichiers"]['name'][$i],"."))); //.ext
										if(!array_key_exists($ext,$UPLOAD['extensions_autorisees'])){
											$UPLOAD['erreur']=1;
											$UPLOAD['msg_erreur'].="&bull; Le format du fichier ".($i+1)." est incorrect (seulement les extensions suivantes: <i>".implode(", ",array_keys($UPLOAD['extensions_autorisees']))."</i>, sont acceptées).<br />";
										} else {
											if($UPLOAD['garder_meme_nom']==1){
												$nom=substr($_FILES["fichiers"]['name'][$i],0,strrpos($_FILES["fichiers"]['name'][$i],"."));
												$nom=$UPLOAD['nettoyer_le_nom']==1?preg_replace("#[^a-z0-9]#","-",strtolower($nom)):$nom;
											} else $nom=($i+1).substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,5);
											//OK!
											$UPLOAD['fichiers'][]=array(
															"ext"=>$ext,
															"nom"=>$nom,
															"tmp_name"=>$_FILES["fichiers"]['tmp_name'][$i],
															);
											$UPLOAD['nbr_fichiers']++;
										}
									}
								} else {
									$UPLOAD['erreur']=1;
									$UPLOAD['msg_erreur'].="&bull; Le fichier ".($i+1)." ".($_FILES["fichiers"]['error'][$i]==1?" excède la taille maximale de ".$UPLOAD['taille_fichier_max']."Mo, veuillez diminuer sa taille ou en choisir un autre. <small>La taille du fichier téléchargé excède la valeur de upload_max_filesize, configurée dans le php.ini. </small>":" comporte le code d'erreur ".htmlentities($_FILES["fichiers"]['error'][$i]).", le formulaire ne peut être validé. Si vous pensez que c'est une erreur, merci d'en informer le support technique s'il vous plaît.")."<br />";
								}
							}
						}
					}
				}
			}
			if($UPLOAD['erreur']==1){
				echo $UPLOAD['msg_erreur'];
			} else {

				if($UPLOAD['nbr_fichiers']!=0){
					//on upload les fichiers:
					for($i=0;$i<=count($UPLOAD['fichiers'])-1;$i++) {
						$cheminVersFichier=$UPLOAD['dossier_pieces_jointes'].$UPLOAD['fichiers'][$i]['nom'].$UPLOAD['fichiers'][$i]['ext'];
						if($UPLOAD['garder_fichier']==1)$cheminVersFichier=$UPLOAD['dossier_pieces_jointes'].$UPLOAD['fichiers'][$i]['nom']."-".time().$UPLOAD['fichiers'][$i]['ext'];
						if(!move_uploaded_file($UPLOAD['fichiers'][$i]['tmp_name'],$cheminVersFichier)) {
							$UPLOAD['erreur']=1;
							$UPLOAD['msg_erreur'].="&bull; Désolé, une erreur est survenue lors de l'ajout du fichier ".($i+1)." sur le serveur, merci de réessayer ou merci d'en informer le support technique s'il vous plaît si le problème persiste.<br />";
						} else {
							$MAIL['prepare_mail']->add_attachment($cheminVersFichier);
						}
					}
				} else {
					if($UPLOAD['fichiers_min']>0){
						$UPLOAD['erreur']=1;
						$UPLOAD['msg_erreur'].="&bull; Vous devez proposer un minimum de ".$UPLOAD['fichiers_min']." fichier(s).";
					}
				}

				if($UPLOAD['erreur']==1){
					echo $UPLOAD['msg_erreur'];
				} else {
					if($MAIL['prepare_mail']->send()){

					
					//$MAIL['prepare_mail']->send();
					echo "<div class='success'> Succès! <br> votre message à été envoyé";

					//on supprime les fichiers uploadés si il y en a eu
					if($UPLOAD['nbr_fichiers']!=0 and $UPLOAD['garder_fichier']==0){
						foreach($UPLOAD['fichiers'] as $fichier){
							unlink($UPLOAD['dossier_pieces_jointes'].$fichier['nom'].$fichier['ext']);
						}
					}
				}
				else{
					echo "erreur d'envoie de email".$MAIL['prepare_mail']->error;
				}
			}
			}
		}

?>