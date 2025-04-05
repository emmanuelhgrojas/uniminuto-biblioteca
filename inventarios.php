<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
session_start();
date_default_timezone_set("America/Bogota");

require_once('Conexion.php');
require_once('funciones.php');
require_once('ClassValidarDatos.php');
header('Content-Type: application/json');

$mensajeErrorInventario404 = "El material con el identificador suministrado no se ha encontrado.";
$mensajeErrorDeleteInventario = "El material no se ha podido eliminar.";
$mensajeErrorInventarioIdNull = "Falta el identificador del material para poder continuar.";
$mensajeErrorInsertInventario = "El inventario no se ha podido registrar.";
$mensajeSuccessDeleteInventario = "Se ha eliminado con exito el inventario.";
$mensajeSuccessInsertInventario = "Se ha registrado con exito el inventario.";
$mensajeSuccessUpdateInventario = "Se ha actualizado con exito el inventario.";
$mensajeErrorUpdateInventario = "El inventario no se ha podido acualizar.";
$mensajeSuccessInfoInventario = "Se ha cargado la informacion del inventario con exito.";


$mensajeErrorOpcionNull = "No se ha especificado la operación.";
$mensajeErrorCamposVaciosFormulario = "Se han encontrados campos del formulario sin llenar que son obligatorios.";








function validarUsuario($filtro, $QueryBD){
  $sqlUsuario = "SELECT * FROM usuarios WHERE usuario = '$filtro' LIMIT 0,1"; 
  return ($QueryBD->NumRowsQuery($sqlUsuario) > 0) ? true : false;
}

function obtenerUsuarioId($filtro, $QueryBD){
  $sqlInfoUsuario = "SELECT * FROM usuarios WHERE usuario_id = $filtro AND estado = 1 LIMIT 0,1";
  $queryInfoUsuario = $QueryBD->GetQuery($sqlInfoUsuario);
  return ($QueryBD->NumRowsQuery($sqlInfoUsuario) > 0) ? $queryInfoUsuario->fetch_array() : null;
}

$usuarioEnLinea = (!empty($_SESSION['usuarioId'])) ? $_SESSION['usuarioId'] : null;
$bibliotecaId = $_SESSION['bibliotecaId'];  

if(!empty($_GET["opc"])){
  $operacionHttp = $_GET["opc"]; 
  
  switch ($operacionHttp) {
    case 'buscar':
        if(!empty($_GET["inventario_id"]) && is_numeric($_GET["inventario_id"])){
          $inventarioId = $_GET["inventario_id"];

          $sqlInfoInventario = "SELECT * FROM materiales WHERE id =  $inventarioId AND estado = 1 LIMIT 0,1";
          $queryInfoInventario = $QueryBD->GetQuery($sqlInfoInventario);
          $sqlInfoAutorInventario = "SELECT autor_id FROM material_autor WHERE material_id = $inventarioId";
          $queryInfoAutorInventario = $QueryBD->GetQuery($sqlInfoAutorInventario);

          if($QueryBD->NumRowsQuery($sqlInfoInventario) > 0){
            $infoInventario = $queryInfoInventario->fetch_array();
            $autores = [];
            foreach ($queryInfoAutorInventario->fetch_all() as $nivel1) {
                foreach ($nivel1 as $id) {
                    $autores[] = (int) $id;
                }
            }
            $request = array( 'estado' => "success",
                                'info' => $infoInventario, 
                                'autores' => $autores, 
                                'mensaje' => $mensajeSuccessInfoInventario);        
          }else{
            $request = array('estado' => "error", 'mensaje' => $mensajeErrorInventario404);
          }
        }else{
          $request = array('estado' => "error", 'mensaje' => $mensajeErrorInventarioIdNull);
        }
      break;
    case 'nuevo':
      if(!empty($_POST["titulo"]) && !empty($_POST["isbn"]) && !empty($_POST["issn"]) &&
          !empty($_POST["codigo_referencia"]) && !empty($_POST["descripcion"]) && !empty($_POST["cantidad"]) &&
          !empty($_POST["valor"]) && !empty($_POST["seccionId"]) && !empty($_POST["tipoMaterialId"])){

          $titulo = $validarDatos->limpiarCadena(!empty($_POST["titulo"]) ? $_POST["titulo"] : null, "");
          $isbn = $validarDatos->limpiarCadena(!empty($_POST["isbn"]) ? $_POST["isbn"] : null, "");
          $issn = $validarDatos->limpiarCadena(!empty($_POST["issn"]) ? $_POST["issn"] : null, "");
          $codigo_referencia = $validarDatos->limpiarCadena(!empty($_POST["codigo_referencia"]) ? $_POST["codigo_referencia"] : null, "");
          $descripcion = $validarDatos->limpiarCadena(!empty($_POST["descripcion"]) ? $_POST["descripcion"] : null, "");
          $cantidad = $validarDatos->limpiarCadena(!empty($_POST["cantidad"]) ? $_POST["cantidad"] : 0, "numero");
          $valor = $validarDatos->limpiarCadena(!empty($_POST["valor"]) ? $_POST["valor"] : 0, "numero");
          $seccionId = $validarDatos->limpiarCadena(!empty($_POST["seccionId"]) ? $_POST["seccionId"] : null, "numero");
          $tipoMaterialId = $validarDatos->limpiarCadena(!empty($_POST["tipoMaterialId"]) ? $_POST["tipoMaterialId"] : null, "numero");
          $autorIds = $_POST["autorId"];
          $listaErroresValidacionForm = [];
          if(count($listaErroresValidacionForm) == 0){
            $queryTransactionSuccess = true; 
            $mysqli = $QueryBD->Conectar_BD();
            $mysqli->autocommit(FALSE);
            try{
              $mysqli->query("INSERT INTO materiales (titulo,
              isbn,
              issn,
              descripcion,
              codigo_referencia,
              cantidad,
              valor_monetario,
              biblioteca_id,
              seccion_id,
              tipo_material_id) VALUES ('".$titulo."','".$isbn."','".$issn."','".$descripcion."','".$codigo_referencia."','".$cantidad."','".$valor."','".$bibliotecaId."','".$seccionId."','".$tipoMaterialId."')") ? null : $queryTransactionSuccess = false;
             if(!empty($mysqli->error)){throw new Exception($mysqli->error);}
                $nuevoInventarioId = $mysqli->insert_id;
                for ($i = 0; $i < count($autorIds); $i++) {
                    $autorId = $autorIds[$i];
                    $mysqli->query("INSERT INTO material_autor (material_id, autor_id) VALUES ('".$nuevoInventarioId."','".$autorId."')") ? null : $queryTransactionSuccess = false;
                    if(!empty($mysqli->error)){throw new Exception($mysqli->error);}
                }
              if($queryTransactionSuccess){
                $mysqli->commit();
                $request = array('estado' => "success", 'mensaje' => $mensajeSuccessInsertInventario);
              }else{
                $mysqli->rollback(); 
                $request = array('estado' => "error", 'mensaje' => $mensajeErrorInsertInventario);
              }              
            }catch(\Exception $exception){
              $mysqli->rollback();
              $request = array('estado' => "error", 'mensaje' => $exception->getMessage());
            } 
            $mysqli->close();
          }else{
            $request = array('estado' => "error", 'mensaje' => mostrarArrayMensajeDeErrores($listaErroresValidacionForm));
          }          
      }else{
        $request = array('estado' => "error", 'mensaje' => $mensajeErrorCamposVaciosFormulario);    
      }       
      break;
    case 'modificar':
      if(!empty($_POST["inventarioId"]) && !empty($_POST["titulo"]) && !empty($_POST["isbn"]) && !empty($_POST["issn"]) &&
      !empty($_POST["codigo_referencia"]) && !empty($_POST["descripcion"]) && !empty($_POST["cantidad"]) &&
      !empty($_POST["valor"]) && !empty($_POST["seccionId"]) && !empty($_POST["tipoMaterialId"])){
            $inventarioId = $validarDatos->limpiarCadena(!empty($_POST["inventarioId"]) ? $_POST["inventarioId"] : 0, "numero");
            $titulo = $validarDatos->limpiarCadena(!empty($_POST["titulo"]) ? $_POST["titulo"] : null, "");
            $isbn = $validarDatos->limpiarCadena(!empty($_POST["isbn"]) ? $_POST["isbn"] : null, "");
            $issn = $validarDatos->limpiarCadena(!empty($_POST["issn"]) ? $_POST["issn"] : null, "");
            $codigo_referencia = $validarDatos->limpiarCadena(!empty($_POST["codigo_referencia"]) ? $_POST["codigo_referencia"] : null, "");
            $descripcion = $validarDatos->limpiarCadena(!empty($_POST["descripcion"]) ? $_POST["descripcion"] : null, "");
            $cantidad = $validarDatos->limpiarCadena(!empty($_POST["cantidad"]) ? $_POST["cantidad"] : 0, "numero");
            $valor = $validarDatos->limpiarCadena(!empty($_POST["valor"]) ? $_POST["valor"] : 0, "numero");
            $seccionId = $validarDatos->limpiarCadena(!empty($_POST["seccionId"]) ? $_POST["seccionId"] : null, "numero");
            $tipoMaterialId = $validarDatos->limpiarCadena(!empty($_POST["tipoMaterialId"]) ? $_POST["tipoMaterialId"] : null, "numero");
            $autorIds = $_POST["autorId"];

          $listaErroresValidacionForm = [];          
          
          if(count($listaErroresValidacionForm) == 0){
            try{              
              $queryTransactionSuccess = true; 
              $mysqli = $QueryBD->Conectar_BD();
              $mysqli->autocommit(FALSE); 
              $mysqli->query("UPDATE materiales SET titulo = '".$titulo."', isbn = '".$isbn."', issn = '".$issn."' , descripcion = '".$descripcion."', codigo_referencia = '".$codigo_referencia."', cantidad = '".$cantidad."', valor_monetario = '".$valor."', seccion_id = '".$seccionId."', tipo_material_id = '".$tipoMaterialId."' WHERE id = $inventarioId") ? null : $queryTransactionSuccess = false;
              if(!empty($mysqli->error)){throw new Exception($mysqli->error);}
              
              $mysqli->query("DELETE FROM material_autor WHERE material_id = $inventarioId") ? null : $queryTransactionSuccess = false;
              if(!empty($mysqli->error)){throw new Exception($mysqli->error);}

              for ($i = 0; $i < count($autorIds); $i++) {
                  $autorId = $autorIds[$i];
                  $mysqli->query("INSERT INTO material_autor (material_id, autor_id) VALUES ('".$inventarioId."','".$autorId."')") ? null : $queryTransactionSuccess = false;
                  if(!empty($mysqli->error)){throw new Exception($mysqli->error);}
              }
              
              if($queryTransactionSuccess){
                $mysqli->commit();
                $request = array('estado' => "success", 'mensaje' => $mensajeSuccessUpdateInventario);
              }else{
                $mysqli->rollback(); 
                $request = array('estado' => "error", 'mensaje' => $mensajeErrorUpdateInventario);
              }
            }catch(\Exception $exception){
              $mysqli->rollback();
              $request = array('estado' => "error", 'mensaje' => $exception->getMessage());
            }  
            $mysqli->close();
          }else{
            $request = array('estado' => "error", 'mensaje' => mostrarArrayMensajeDeErrores($listaErroresValidacionForm));
          }          
      }else{
        $request = array('estado' => "error", 'mensaje' => $mensajeErrorCamposVaciosFormulario);    
      } 
       break;
    case 'eliminar':
      if(!empty($_POST['inventarioId']) && is_numeric($_POST['inventarioId'])){
          $inventarioId = $_POST['inventarioId'];          
          $listaErroresValidacionForm = [];
          
          if(count($listaErroresValidacionForm) == 0){
            $queryTransactionSuccess = true; 
            $mysqli = $QueryBD->Conectar_BD();
            $mysqli->autocommit(FALSE);
            try{              
              $sqlBuscarInventario =  "SELECT * FROM materiales WHERE id = $inventarioId AND estado = '1' LIMIT 0,1";
              if($QueryBD->NumRowsQuery($sqlBuscarInventario) > 0){
                $mysqli->query("UPDATE materiales SET estado = 0 WHERE id = $inventarioId");
                if(!empty($mysqli->error)){throw new Exception($mysqli->error);}
                if($queryTransactionSuccess){
                  $mysqli->commit();
                  $request = array('estado' => "success", 'mensaje' => $mensajeSuccessDeleteInventario); 
                }else{
                  $mysqli->rollback(); 
                  $request = array('estado' => "success", 'mensaje' => $mensajeErrorDeleteInventario); 
                }
              }else{
                $request = array('estado' => "error", 'mensaje' => $mensajeErrorInventario404);
              }                                
            }catch(\Exception $exception){
              $mysqli->rollback();
              $request = array('estado' => "error", 'mensaje' => $exception->getMessage());
            } 
            $mysqli->close();
          }else{
            $request = array('estado' => "error", 'mensaje' => mostrarArrayMensajeDeErrores($listaErroresValidacionForm));
          }    
      }else{
         $request = array('estado' => "error", 'mensaje' => $mensajeErrorInventarioIdNull);
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