<?php
class Conexion extends mysqli{

	private $hostname = "localhost";
	private $user_bd = "root";
	private $pass_bd = "";
	private $name_bd = "biblioteca";

    public function __construct(){

        /*parent::__construct($this->hostname,$this->user_bd,$this->pass_bd,$this->name_bd);
        $this->query("SET NAMES 'utf-8';");
        $this->connect_errno ?  die ("Error con la conexion") : $msg = 'Conectado';
        echo $msg;
        unset($msg); */
        $this->conexion = self::Conectar_BD();

	
    }
    public function Conectar_BD(){
        $conexion = new mysqli($this->hostname,$this->user_bd,$this->pass_bd,$this->name_bd);
        //$zonaHoraria = (new DateTime('now', new DateTimeZone('America/Bogota')))->format('P'); 
        //$conexion->query("SET time_zone='$zonaHoraria';"); 
        $conexion->query("SET NAMES 'utf8';");
        $this->connect_errno ? die ("Error con la conexion") : $msg = 'Conectado';
        //echo $msg;
        return $conexion;
    }    
    public function GetQuery($query_sql){
        $query = $this->conexion->query($query_sql);
        print($this->conexion->error);
        return $query;    
    }   
    //Obtengo Numero de resultados de la consulta
    public function NumRowsQuery($query_sql){
        $query = $this->conexion->query($query_sql);
        $num_rows = $query->num_rows;
        return $num_rows;        
    } 
}
$QueryBD = new Conexion();

/*$query = $QueryBD->GetQuery("select cod_perfil from perfil_tienda");

while ($fila = $query->fetch_array())
{
   $id = $fila['cod_perfil'];
}
echo $id; */
?>