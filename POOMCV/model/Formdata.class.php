<?php 

class Formdata{

    private $post_data;

    /**
     * @param $surround ex 'p' or 'div'
     */
    public $surround = 'p';

    public function __construct($post_data = array()){
        $this->post_data = $post_data;
    }

    /**
     * surrond encadre html avec le tag $surround
     * @param html 
     */
    private function surround($html){
        return "<{$this->surround}>{$html}</{$this->surround}>";
    }

    public function getPostData(){
        return $this->getPostData;
    }
    public function setPostData($post_data){
        $this->post_data = $post_data;
    }

    /**
     * getvalue
     * vérifie si la donnée existe avant de la renvoyer, sinon renvoie null
     * @param index de la donnée;
     */
    private function getvalue($key){
        return isset($this->post_data[$key]) ? $this->post_data[$key] : null;
        
    }

    /**
     * input renvoie un champ de formulaire input avec le nom $name et la valeur $name contenu dans le array $data
     * @param $name nom du champs input
     */
    public function input($name,  $label="",$required=false){
        $name = htmlentities($name ,ENT_QUOTES,"UTF-8");
        $html =  '<div id="'.$name.'" class="'.$name.'" >
            <label class="formlabel">'.$label.'</label>';
           
        
        if($required==false){
            $html .= '<input type="text" name="'.$name. '" value="'. $this->getvalue($name).'">
                </div>';
           
        } 
        else {
            $html .= '<input type="text" name="'.$name. '" value="'. $this->getvalue($name).'" required="required">
                </div>';

        }
        
        return $this->surround($html);
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
        $name = htmlentities($name ,ENT_QUOTES,"UTF-8");
        return $this->surround(
            '<label class="formlabel">'.$label.'</label>
            <textarea 
            id="'.$name. '" 
            class="'.$name. '" 
            name="'.$name. '" 
            rows="'.$rows. '" 
            cols="'.$cols. '">'. 
            $this->getvalue($name). 
            '</textarea >'
        );
    }

    /**
     * inputfile ajoute un champ pour ajouter un fichiers en upload
     * 
    */
    public function inputfile($label=""){           
        return $this->surround( '
        <label class="formlabel">'.$label.'</label>
        <input type="file" name="fichiers[]">'
    );
    }
/**
 * Ajoute un champs Option à partir d'un array
 * Si le champs l'array est nul il peut remplit par un javascript
 * @param idname l'id et name du select
 * @param title titre ou question du select
 * @param arraylist array pour créer les options du select ex: ['str1', 'str2', 'str3', 'str4'];
 * return html
 */
    public function SelectWithOptions( $idname, $title="", $arraylist=null){
/**
 * <select name="select">
* Elle est où la poulette ?
 * <option value="value1">Avec les lapins</option>
 * <option value="value2" selected>Avec les canards</option>
 * <option value="value3">Pas là</option>
 *</select>
 */
        $html = '';
        $html.=  '<div class="Title" name="'.$title.'">'.$title.'</div>';
        $html.=  '<select id="'.$idname.'" name="'.$idname.'">';
        if(isset($arraylist)){
            foreach($arraylist as  $key => $value){
                $html.= '<option value="'.$value.'">'.$key.' +'.$value.'</option>';
            }
        }
        $html.= '</select>';
        return $html;
    }

    /**
     * submit renvoie le html d'un button submit encadré par la balise contenu dans $surround
     */
    public function submit(){
        return $this->surround('<button type="submit">Envoyer</button>');
        
    }

    /**
     *  convertir le array countrycode;
     * On pourrait mettre cette fonction ailleurs
     */
    public function  GetCountryCode(){
        require_once('../resources/php/countrycode.php');
        $arrayindicatif = array();
        foreach($countryArray as $key => $value){
            $arrayindicatif[$value['name']] = $value['code'];  
        }
        return $arrayindicatif;

}

}

?>