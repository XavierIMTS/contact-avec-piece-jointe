<?php

require_once('../model/Formdata.class.php');
require_once("../model/SendEmailUploadfile.class.php");

class SendEmailCtrl  { 
    /**
     * @param surround ex 'p' or 'div'
     * la variable surround peut être changer depuis la class appelante. 
     */
    public $surround = 'p';
    
    private $email_data;

    protected $formdata;

    public function __construct($email_data = array()){
        $this->email_data = $email_data;      
        if(isset($email_data)){
            $this->formdata  = new Formdata($email_data);   
            $this->formdata->surround = $this->surround;
        }
             
    }
/**
 * get_email_data 
 * @param  boolean state_debug
 * true debug on
 * false debug off
 * @return array of post $email_data
 */
    public function get_email_data($state_debug=false){
        if($state_debug==true){
        echo "<pre>";
        echo "getemail_data";
           // var_dump($this->email_data);
        echo "</pre>";
        }        
        return $this->email_data;
    }

        /**
     * surrond encadre html avec le tag $surround
     * @param html 
     */
    //private function surround($html){
      //  return "<{$this->surround}>{$html}</{$this->surround}>";
    //}

     /**
     * getvalue
     * vérifie si la donnée existe avant de la renvoyer, sinon renvoie null
     * @param index de la donnée;
     */
    private function getvalue($key){
        return isset($this->email_data[$key]) ? $this->email_data[$key] : null;
        
    }


   /**
     * input renvoie un champ de formulaire input avec le nom $name et la valeur $name contenu dans le array $data
     * @param $name nom du champs input
     * htmlentities('compagny',ENT_QUOTES,"UTF-8")
     */
    public function input($name, $label="", $required=false){
        
        $name = htmlentities($name ,ENT_QUOTES,"UTF-8");
        $html = $this->formdata->input($name, $label,$required);
      
        if($this->getvalue($name) !=""){ }
        else {
            $html = $this->addErrorEmptyField($html, $label);
            }
        return $html;
                
    }

    private function addErrorEmptyField($html, $name){
        return $html ='<div class="error_empty_field">Remplir le champs '.$name.'* </div>'.$html;
    }


    /**
     * textarea renvoie un champ de formulaire textarea avec le nom $name et la valeur $name contenu dans le array $data
     * @param name = name
     * @param label  duchamps
     * @param id = id pour css
     * @param class = class pour css
     * @param rows nb lignes
     * @param cols = nb colonnes
     * 
     */
    public function textarea($name, $label="", $rows=10, $cols=50, $id="", $class=""){

        $html = $this->formdata->textarea($name, $label="", $rows=10, $cols=50, $id="", $class="");
      
        if($this->getvalue($name) !=""){ }
        else {
            $html = $this->addErrorEmptyField($html, $label);
            }
        return $html;
    }


    /**
     * SelectWithOptions
     * @param string name 
     * @param string title
     * @param array arraylist 
     */

     public function SelectWithOptions($name, $title="", $arraylist=null){
        if(isset($name)){
            $html = $this->formdata->SelectWithOptions($name, $title, $arraylist);
        }
      
        return $html;

     }

    /**
     * inputfile ajoute un champ pour ajouter un fichiers en upload
     * 
    */
    public function inputfile($label=""){         
        return $this->formdata->inputfile($label);
     }

    /**
     * submit renvoie le html d'un button submit encadré par la balise contenu dans $surround
     */
    public function submit(){
        return $this->formdata->submit();
        
    }

    /**
     * controle si le le email et le message sont remplis
     * pour le reste c'est pas grave
     * si on veut vérifier tous les champs
     * todo le champs required est à gérer required="required"
     */
    public function sendemail(){
        var_dump($this->email_data);
        if(!empty($this->email_data['mail']) && !empty($this->email_data['message'])){
            $SendEmailUploadfile = new SendEmailUploadfile( $_POST, $_FILES);  
            //$SendEmailUploadfile->get_data();
            //$SendEmailUploadfile->get_files();
     
            return $SendEmailUploadfile->Send();
        }
    }

/**
 * Renvoie les indicatifs téléphoniques des pays 
 */
    public function getCountryCode(){
        return $this->formdata->getCountryCode();
    }


}


?>