<?php
//on inclut la classe PHP qui permet d'envoyer le mail avec une pièce jointe
//include "../model/C2Mail.class.php";
require_once("../model/C2Mail.class.php");

class SendEmailUploadfile{
   
   private $files;
   private $post;
   
   
    private $Mail_prepare_mail;
    private $Mail_send_to;
    private $Mail_subject;
    private $Mail_sujet;
    private $Mail_mail_envoyeur;
    private $Mail_mail_texte; 	
    private $Mail_mail_html;		
    private $Mail_envoyer_a;

    //vous pouvez paramètrer ci-dessous:
    private $UpLoadFichierMax=1;//nombre de fichier maximum pouvant être proposé
    private $UpLoadFichierMin=0;//nombre de fichier minimum devant être proposé (0 = le formulaire peut être envoyé même si il y aucun fichier de proposé
    private $UpLoadDirectoryPiecesJointes='../pieces-jointes/';//dossier de destination des fichiers, avec le / à la fin
    private $UpLoadTailleFichierMax=5;//en Mo (peut être utilisé avec un point pour une valeure en dessous de 1mo, exemple pour 500ko: 0.5
    private $UpLoadKeepName=1;//si le nom doit être remplacé par un nom aleatoire ou si il doit être identique à l'original (1=oui, 0=non)
    private $UpLoadCleanName=1;//si les caractères spéciaux doivent être remplacés par un - et les majuscules en minuscules (1=oui, 0=non)
    private $UpLoadKeepFile=1;//si le fichier doit être gardé sur le serveur, si oui, un timestamp sera ajouté au nom pour pas qu'il soit écrasé si il y a une autre pièce jointe avec le même nom(1=oui, 0=non)



    //laissez les extensions que vous souhaitez seulement autoriser,
    // une liste exhaustive officielle pour l'ensemble des types MIME est disponible à l'adresse suivante :
    // https://www.iana.org/assignments/media-types/media-types.xhtml
    private $UpLoadExtensionsAvailables=array(
        ".pdf"=>"application/pdf",
        ".png"=>"image/png",
        ".jpg"=>"image/jpg",
        ".jpeg"=>"image/jpeg",
        );

    //variable d'initiation, ne pas toucher
    private $UpLoadNbFiles=0;
    private $UpLoadError=0;
    private $UpLoadErrorMessage="";
    private $UpLoadFiles=array();
    private $ArrayNomFichier=[];//pour éviter l'ajout d'un fichier deux fois
    //on fait la verif des photos si il y en a:        

    public function __construct( $post= array(), $files = array()){
        
        $this->post = $post;
        
        // cela ne sert à rien car $_FILES est superglobale et accessible partout
        $this->files = $files;
    }
/**
 * get_data()
 */
    public function get_data(){
        echo "<pre>";
        echo "SendEmailUploadfile get_data ";
          //  var_dump($this->post);
        echo "</pre>";
        return $this->post;
    }
/**
 * get_files()
 */
    public function get_files(){
        echo "<pre>";
        echo "**** SendEmailUploadfile get_data ";
          //  var_dump($this->files);
        echo "</pre>";
        return $this->post;
    }

    /**
     * fonction qui envoie le email
     */
    public function Send(){
        $this->uploadInit();
        $this->uploadfiles();
        return $this->sendEmail();
        }

    private function uploadInit(){

                	//on préparer le mail:
    if(isset( $this->post['email_sender'])) {
        $this->$Mail_send_to= $this->post['email_sender'];//(destinataire) mettez votre adresse mail ou l'adresse mail d'un autre destinataire (pour envoyer à plusieurs dstinataires, séparez par une virgules les adresses)
    }
    
    if(isset($this->post['email_subject']) && isset($this->post['email_sender'])){
        $this->$Mail_subject= $this->post['email_subject']." de ".$this->$Mail_send_to;
    }

    	//on continue la préparation du mail:
		$this->Mail_send_to	= htmlentities($this->post['mail'],ENT_QUOTES,"UTF-8");//par sûreté, on utilise htmlentities...
		
		$this->Mail_mail_texte 	= "";//si vous voulez le mail au format texte, le contenu du mail "$_POST['message']" doit se placer ici
		$this->Mail_mail_html		= nl2br(htmlentities($this->post['message'],ENT_QUOTES,"UTF-8"));//on converti toutes les entités HTML avec htmlentities() ("<" devient "&lt;" par exemple) et on remplace les sauts de ligne par des <br> avec nl2br() sinon le texte reçu sera sur une seule ligne
		$this->Mail_prepare_mail	= new C2Mail($this->Mail_envoyer_a,$this->Mail_prepare_mail, $this->Mail_subject, $this->Mail_mail_texte, $this->Mail_mail_html);
        // todo : a factoriser
        //on passe aux pièces jointes si il y en a
		if(!empty($_FILES)){

			if(count($_FILES["fichiers"]['name'])>$this->UpLoadFichierMax){
				$this->UpLoadError=1;
				$this->UpLoadErrorMessage.="&bull; Nombre de fichier incorrect.<br />";
			} 
            else {
				for($i=0;$i<=count($_FILES["fichiers"]['name'])-1;$i++) {
					if($_FILES["fichiers"]['name'][$i]!=""){//si aucun fichier proposé on lance pas un for pour rien
						if(in_array($_FILES["fichiers"]['name'][$i],$this->ArrayNomFichier)){
							$this->UpLoadError=1;
							$this->UpLoadErrorMessage.="&bull; Des fichiers sont identiques.<br />";
						} 
                        else {
							$this->ArrayNomFichier[]=$_FILES["fichiers"]['name'][$i];
							if($_FILES["fichiers"]['error'][$i]!=4){// 4. Aucun fichier proposé
								if($_FILES["fichiers"]['error'][$i]==0){// 0. Aucune erreur, le téléchargement est correct. 
									if(!in_array($_FILES["fichiers"]['type'][$i],$this->UpLoadExtensionsAvailables)){
										$this->UpLoadError=1;
										$this->UpLoadErrorMessage.="&bull; Le format du fichier ".($i+1)." est incorrect (seulement les extensions suivantes: <i>".implode(", ",array_keys($UpLoadExtensionsAvailables))."</i>, sont acceptées).<br />";
									} 
                                    elseif(($_FILES["fichiers"]['size'][$i]/1000000)>$this->UpLoadTailleFichierMax){
										$this->UpLoadError=1;
										$this->UpLoadErrorMessage.="&bull; Le fichier ".($i+1)." excède la taille maximale de ".$this->UpLoadTailleFichierMax."Mo, veuillez diminuer sa taille ou en choisir un autre.<br />";
									} 
                                    else 
                                    {
										$ext=strtolower(substr($_FILES["fichiers"]['name'][$i],strrpos($_FILES["fichiers"]['name'][$i],"."))); //.ext
										if(!array_key_exists($ext,$this->UpLoadExtensionsAvailables)){
											$this->UpLoadError=1;
											$this->UpLoadErrorMessage.="&bull; Le format du fichier ".($i+1)." est incorrect (seulement les extensions suivantes: <i>".implode(", ",array_keys($UpLoadExtensionsAvailables))."</i>, sont acceptées).<br />";
										} 
                                        else {
											if($this->UpLoadKeepName==1){
												$nom=substr($_FILES["fichiers"]['name'][$i],0,strrpos($_FILES["fichiers"]['name'][$i],"."));
												$nom=$this->UpLoadCleanName==1?preg_replace("#[^a-z0-9]#","-",strtolower($nom)):$nom;
											} 
                                            else $nom=($i+1).substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,5);
											//OK!
											$this->UpLoadFiles[]=array(
															"ext"=>$ext,
															"nom"=>$nom,
															"tmp_name"=>$_FILES["fichiers"]['tmp_name'][$i],
															);
                                                            $this->UpLoadNbFiles++;
										}
									}
								} 
                                else {
									$this->UpLoadError=1;
									$this->UpLoadErrorMessage.="&bull; Le fichier "
                                    .($i+1)." ".($_FILES["fichiers"]['error'][$i]==1?" excède la taille maximale de "
                                    .$this->UpLoadTailleFichierMax."Mo, veuillez diminuer sa taille ou en choisir un autre. <small>La taille du fichier téléchargé excède la valeur de upload_max_filesize, configurée dans le php.ini. </small>":" comporte le code d'erreur "
                                    .htmlentities($_FILES["fichiers"]['error'][$i]).", le formulaire ne peut être validé. Si vous pensez que c'est une erreur, merci d'en informer le support technique s'il vous plaît.")."<br />";
								}
							}
						}
					}
				}
			}
        }

    }


/**
 * gestion des uploads
 */
    private function uploadfiles(){

        if($this->UpLoadError==1){
            echo $this->UpLoadErrorMessage;
        } else 
        {        
            if($this->UpLoadNbFiles!=0){
                //on upload les fichiers:
                for($i=0;$i<=count($this->UpLoadFiles)-1;$i++) {
                    $cheminVersFichier=$this->UpLoadDirectoryPiecesJointes.$this->UpLoadFiles[$i]['nom'].$this->UpLoadFiles[$i]['ext'];
                    if($this->UpLoadKeepFile==1)$cheminVersFichier=$this->UpLoadDirectoryPiecesJointes.$this->UpLoadFiles[$i]['nom']."-".time().$this->UpLoadFiles[$i]['ext'];
                    if(!move_uploaded_file($this->UpLoadFiles[$i]['tmp_name'],$cheminVersFichier)) {
                        $this->UpLoadError=1;
                        $this->UpLoadErrorMessage.="&bull; Désolé, une erreur est survenue lors de l'ajout du fichier ".($i+1)." sur le serveur, merci de réessayer ou merci d'en informer le support technique s'il vous plaît si le problème persiste.<br />";
                    } else {
                        echo "chemin de fichier ".$cheminVersFichier."<br>";
                        $this->Mail_prepare_mail->add_attachment($cheminVersFichier);
                    }
                }
            } 
            else {
                if($this->UpLoadFichierMin>0){
                    $this->UpLoadError=1;
                    $this->UpLoadErrorMessage.="&bull; Vous devez proposer un minimum de ".$this->UpLoadFichierMin." fichier(s).";
                }
            }
        }
    }

    private function sendEmail(){
        if($this->UpLoadError==1)
        {
            return $this->UpLoadErrorMessage;

        } 
        else {
            if($this->Mail_prepare_mail->send())
            {
            
            //$Mail_prepare_mail->send();
            return "<div class='success'> Succès! <br> votre message à été envoyé";

            $this->deleteUploadedFiles();
            }
            else
            {
               return $this->errormail();
            }
        }
    }

    private function deleteUploadedFiles(){
            //on supprime les fichiers uploadés si il y en a eu
            if($this->UpLoadNbFiles!=0 and $this->UpLoadKeepFile==0){
                foreach($this->UpLoadFiles as $fichier){
                    unlink($this->UpLoadDirectoryPiecesJointes.$fichier['nom'].$fichier['ext']);
                }
            }

    }

    private function errormail(){
        return "Erreur d'envoie de email";
        
    }

}
?>