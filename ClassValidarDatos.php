<?php
class ValidarDatos{

    public function ValidarDatos(){

    }
    public function ValidarEmail($dato,$text){
        if($dato == ""){
            return $msg = "Campo Vacio: ".$text;                
        }else{
            if(!filter_var($dato, FILTER_VALIDATE_EMAIL)){
                return $msg = "Escribio mal: ".$text;          
            }          
        } 
    }
    public function ValidarCampo($dato,$text){
        if(trim($dato) == ""){
            return $msg = "Campo Vacio: ".$text;
        }
    }
    public function ValidarEntero($dato,$text){
        if($dato == ""){
            return $msg = "Campo Vacio: ".$text;
        }else{            
            if(!is_numeric($dato)){
                return $msg = "Escribio mal: ".$text;
            }
        }
    }
    public function LimpiarArray($arrayText){
        $arrayText = $arrayText;
        $lengthArray = count($arrayText);

        for($i=0; $i<$lengthArray; $i++)
        {
            $cadena = $arrayText[$i];
            $text = self::LimpiarCadena($cadena,'0');
            $arrayText[$i] = $text;
        }
        return $arrayText;

    }
    public function QuitarEspacios($cadena){
        $cadena = str_replace(
            array(" "),'',$cadena
        );
        return $cadena;
    }    
    public function ConvertirCadena($cadena){ 
        $cadena = str_replace(
            array('%1%', '%2%', '%3%', '%4%','%5%'),
            array('-', '.', '_', '@','#'),
            $cadena
        );
        return $cadena;    
    }
    public function RecortarCadena($cadena,$maxCadena){
        $cadena = $cadena;
        $Lenght = strlen($cadena);
        $maxCadena = $maxCadena;
        if(($maxCadena+5) <= $Lenght){
            return $cadena = substr($cadena, -$maxCadena);
        }else{
            return $cadena;
        }
        
    }
    public function limpiarCadena($cadena,$type){ 
      $type = $type;  
      $cadena=strip_tags($cadena);
      $cadena = trim($cadena);
     
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );
     
        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena
        );
     
        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena
        );
     
        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena
        );
     
        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena
        );
     
        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $cadena
        );
        //Esta parte se encarga de eliminar cualquier caracter extraño
        $cadena = str_replace(
            array("\\", "¨","*","=", "º", "~",
                 "|", "!", "\"",
                 "·", "$", "%", "&", "/",
                 "(", ")", "?", " ' ", "¡",
                 "¿", "[", "^", "`", "]",
                 "+", "}", "{", "¨", "´",
                 ">", "<", ";", ",", ":","+","°"),
            '',
            $cadena
        );
        switch ($type) {
            case 'texto': 
                    $cadena = str_replace(array('-', '.', '_', '@','#'), '',$cadena);
                    $cadena = preg_replace('/[0-9]+/', '', $cadena);
                break;
            case 'numero': 
                    $cadena = str_replace(array('-', '.', '_', '@','#'), '',$cadena);
                    $cadena = preg_replace('/[a-zA-Z]+/', '', $cadena);
            break;
            case 'placa': 
                $cadena = str_replace(array('.', '_', '@','#'), '',$cadena);
            break;
            case 'email': 
                    $cadena = str_replace(array('-', '.', '_', '@'),array('%1%', '%2%', '%3%', '%4%'),$cadena);
                    $cadena = $this->QuitarEspacios($cadena);
                break;
            case 'username': 
                    $cadena = str_replace(array('-', '@','#'), '',$cadena);
                    $cadena = str_replace(array('.', '_' ), array('%2%', '%3%'),$cadena);
                    $cadena = $this->QuitarEspacios($cadena);
                break;                 
            default:
                # code...
                break;
        }
      return $cadena;    
    }

    
    

}
$validarDatos = new ValidarDatos();
?>