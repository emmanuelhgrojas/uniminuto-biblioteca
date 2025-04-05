<?php
session_start();
date_default_timezone_set("America/Bogota");

require_once('Conexion.php');
require_once('funciones.php');
require_once('ClassValidarDatos.php');
header('Content-Type: application/json');


if(!empty($_GET["opc"])){
  $operacionHttp = $_GET["opc"]; 
  
  switch ($operacionHttp) {
    case 'modificar':
      if(!empty($_POST["razonSocial"])){          
        $queryTransactionSuccess = true; 
        $mysqli = $QueryBD->Conectar_BD();
        $mysqli->autocommit(FALSE);
        $mysqli->query("UPDATE configuracion SET razonsocial = '".$_POST["razonSocial"]."', 
        direccion = '".$_POST["direccion"]."',         
        nit = '".$_POST["nit"]."', 
        telefono = '".$_POST["telefono"]."'") ? null : $queryTransactionSuccess = false;
        
        if($queryTransactionSuccess){
          $mysqli->commit();
          $request = array('estado' => "success", 'mensaje' => "Se ha actualizado con exito la configuracion.");
        }else{
          $mysqli->rollback(); 
          $request = array('estado' => "error", 'mensaje' => "No se ha logrado actualizar la configuracion.");
        }
        $mysqli->close();
      }else{
        $request = array('estado' => "error", 'mensaje' => $mensajeErrorCamposVaciosFormulario);    
      }    
      break;
    default:      
      break;
  }
}else{
   $request[] = array('estado' => "error", 'mensaje' => $mensajeErrorOpcionNull);
}
echo json_encode($request);  
?>