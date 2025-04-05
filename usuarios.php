<?php
session_start();
date_default_timezone_set("America/Bogota");

require_once('Conexion.php');
require_once('funciones.php');
require_once('ClassValidarDatos.php');
header('Content-Type: application/json');


$mensajeErrorUsuarioEnLinea = "No esta permitido eliminar el usuario que esta utilizando.";
$mensajeErrorContrasenas = "Las contraseñas no coinciden.";
$mensajeErrorOpcionNull = "No se ha especificado la operación.";
$mensajeErrorUsuarioDuplicado = "Se ha encontrado un usuario registrado en el sistema.";
$mensajeErrorUsuario404 = "El usuario con el identificador suministrado no se ha encontrado.";
$mensajeErrorUsuarioIdNull = "Falta el identificador del usuario para poder continuar.";
$mensajeErrorRol404 = "El rol con el identificador suministrado no se ha encontrado.";
$mensajeErrorRolIdNull = "Falta el identificador del rol para poder continuar.";
$mensajeErrorInsertUsuario = "El usuario no se ha podido registrar.";
$mensajeErrorCamposVaciosFormulario = "Se han encontrados campos del formulario sin llenar que son obligatorios.";
$mensajeErrorUpdateUsuario = "El usuario no se ha podido acualizar.";
$mensajeErrorDeleteUsuario = "El usuario no se ha podido eliminar.";

$mensajeSuccessInfoUsuario = "Se ha cargado la informacion del usuario con exito.";
$mensajeSuccessInsertUsuario = "Se ha registrado con exito el usuario.";
$mensajeSuccessUpdateUsuario = "Se ha actualizado con exito el usuario.";
$mensajeSuccessDeleteUsuario = "Se ha eliminado con exito el usuario.";

function validarTipoDocumento($filtro, $QueryBD){
  $sqlTipoDocumento = "SELECT * FROM tipo_documentos WHERE (tipodocumento_id =  $filtro OR tipo_documento = $filtro) AND estado = 1 LIMIT 0,1";
  return ($QueryBD->NumRowsQuery($sqlTipoDocumento) > 0) ? true : false;
}

function validarUsuario($filtro, $QueryBD){
  $sqlUsuario = "SELECT * FROM usuarios WHERE usuario = '$filtro' LIMIT 0,1"; 
  return ($QueryBD->NumRowsQuery($sqlUsuario) > 0) ? true : false;
}

function validarRolId($rolId, $QueryBD){
  $sqlRol = "SELECT * FROM roles WHERE rol_id = $rolId LIMIT 0,1"; 
  return ($QueryBD->NumRowsQuery($sqlRol) > 0) ? true : false;
}

function obtenerUsuarioId($filtro, $QueryBD){
  $sqlInfoUsuario = "SELECT * FROM usuarios WHERE usuario_id = $filtro AND estado = 1 LIMIT 0,1";
  $queryInfoUsuario = $QueryBD->GetQuery($sqlInfoUsuario);
  return ($QueryBD->NumRowsQuery($sqlInfoUsuario) > 0) ? $queryInfoUsuario->fetch_array() : null;
}

$usuarioEnLinea = (!empty($_SESSION['usuarioId'])) ? $_SESSION['usuarioId'] : null;

if(!empty($_GET["opc"])){
  $operacionHttp = $_GET["opc"]; 
  
  switch ($operacionHttp) {
    case 'login':
        if(!empty($_POST['usuario']) && !empty($_POST['password'])){
          $usuario = $_POST['usuario']; 
          $clave = SHA1($_POST['password']);            
          $sqlValidarUsuario = "SELECT * FROM usuarios WHERE usuario = '$usuario' and contrasena = '$clave' AND estado = 1 LIMIT 0,1";
          $queryValidarUsuario = $QueryBD->GetQuery($sqlValidarUsuario);
          
          if($QueryBD->NumRowsQuery($sqlValidarUsuario) > 0){
            while ($usuario = $queryValidarUsuario->fetch_array()){ 
              $_SESSION['usuarioId'] = $usuario['usuario_id'];
              $_SESSION['usuario'] = $usuario['usuario'];
              $_SESSION['rolId'] = $usuario['rol_id'];
              $_SESSION['bibliotecaId'] = $usuario['biblioteca_id'];
            }                        
            $fechaActual = new DateTime();
            $fechaActual->modify('first day of this month');            
            $request = array('estado' => "success",'mensaje' => "Se ha verificado con exito el usuario, en un momento cargara el panel de administracion.");        
          }else{
              $request = array('estado' => "error", 'mensaje' => "Lo datos introducidos no corresponden a un usuario.");
          }
        }else{
          $request = array('estado' => "error", 'mensaje' => "Se han encontrados campos del formulario sin llenar que son obligatorios.");
        }
      break;
    case 'logout':
      session_destroy();
        if(!empty($_SESSION['usuarioId']) && !empty($_SESSION['usuario']) && !empty($_SESSION['rolId']) && !empty($_SESSION['bibliotecaId'])){          
          session_destroy();
        }        
        $homeurl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
        header('Location: ' . $homeurl);
      break;
    case 'buscar':
        if(!empty($_GET["usuario_id"]) && is_numeric($_GET["usuario_id"])){
          $usuarioId = $_GET["usuario_id"];

          $sqlInfoUsuario = "SELECT * FROM usuarios WHERE usuario_id =  $usuarioId AND estado = 1 LIMIT 0,1";
          $queryInfoUsuario = $QueryBD->GetQuery($sqlInfoUsuario);

          if($QueryBD->NumRowsQuery($sqlInfoUsuario) > 0){
            $infoUsuario = $queryInfoUsuario->fetch_array();
            $request = array( 'estado' => "success",
                                'usuario' => $infoUsuario, 
                                'mensaje' => $mensajeSuccessInfoUsuario);        
          }else{
            $request = array('estado' => "error", 'mensaje' => $mensajeErrorUsuario404);
          }
        }else{
          $request = array('estado' => "error", 'mensaje' => $mensajeErrorUsuarioIdNull);
        }
      break;
    case 'nuevo':
      if(!empty($_POST["bibliotecaId"]) && !empty($_POST["rolId"]) && is_numeric($_POST["rolId"]) &&
          !empty($_POST["usuario"]) && !empty($_POST["contrasena"]) && !empty($_POST["repetircontrasena"]) &&
          !empty($_POST["nombres_usuario"]) && !empty($_POST["apellidos_usuario"])){

          $bibliotecaId = $_POST["bibliotecaId"];
          $rolId = $_POST["rolId"];
          $nombresUsuario = $validarDatos->limpiarCadena(!empty($_POST["nombres_usuario"]) ? $_POST["nombres_usuario"] : null, "texto");
          $apellidosUsuario = $validarDatos->limpiarCadena(!empty($_POST["apellidos_usuario"]) ? $_POST["apellidos_usuario"] : null, "texto");
          $usuario = $validarDatos->limpiarCadena(!empty($_POST["usuario"]) ? $_POST["usuario"] : 0, "username");
          $repetirContrasena = $validarDatos->limpiarCadena(!empty($_POST["repetircontrasena"]) ? $_POST["repetircontrasena"] : 0, "");
          $contrasena = $validarDatos->limpiarCadena(!empty($_POST["contrasena"]) ? $_POST["contrasena"] : 0, "");
          $contrasenaSHA1 = SHA1($contrasena);
          $listaErroresValidacionForm = [];

          if(validarUsuario($usuario, $QueryBD)){
            array_push($listaErroresValidacionForm, $mensajeErrorUsuarioDuplicado);
          }
          if(!validarRolId($rolId, $QueryBD)){
            array_push($listaErroresValidacionForm, $mensajeErrorRol404);
          }
          if($contrasena != $repetirContrasena){
            array_push($listaErroresValidacionForm, $mensajeErrorContrasenas);
          }
          
          if(count($listaErroresValidacionForm) == 0){
            $queryTransactionSuccess = true; 
            $mysqli = $QueryBD->Conectar_BD();
            $mysqli->autocommit(FALSE);
            try{              
              $mysqli->query("INSERT INTO usuarios (usuario,contrasena,nombres,apellidos,rol_id, biblioteca_id) VALUES ('".$usuario."','".$contrasenaSHA1."','".$nombresUsuario."','".$apellidosUsuario."','".$rolId."','".$bibliotecaId."')") ? null : $queryTransactionSuccess = false;
              if(!empty($mysqli->error)){throw new Exception($mysqli->error);}
              if($queryTransactionSuccess){
                $mysqli->commit();
                $request = array('estado' => "success", 'mensaje' => $mensajeSuccessInsertUsuario);
              }else{
                $mysqli->rollback(); 
                $request = array('estado' => "error", 'mensaje' => $mensajeErrorInsertUsuario);
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
      if(!empty($_POST["usuarioId"]) && is_numeric($_POST["usuarioId"]) &&
          !empty($_POST["rolId"]) && is_numeric($_POST["rolId"]) &&
          !empty($_POST["usuario"]) && !empty($_POST["nombres_usuario"]) && !empty($_POST["apellidos_usuario"])){
          
          $bibliotecaId = $_POST["bibliotecaId"];
          $usuarioId = $_POST["usuarioId"];
          $rolId = $_POST["rolId"];
          $nombresUsuario = $validarDatos->limpiarCadena(!empty($_POST["nombres_usuario"]) ? $_POST["nombres_usuario"] : null, "texto");
          $apellidosUsuario = $validarDatos->limpiarCadena(!empty($_POST["apellidos_usuario"]) ? $_POST["apellidos_usuario"] : null, "texto");
          $usuario = $validarDatos->limpiarCadena(!empty($_POST["usuario"]) ? $_POST["usuario"] : 0, "username");
          $repetirContrasena = $validarDatos->limpiarCadena(!empty($_POST["repetircontrasena"]) ? $_POST["repetircontrasena"] : null, "");
          $contrasena = $validarDatos->limpiarCadena(!empty($_POST["contrasena"]) ? $_POST["contrasena"] : null, "");
          $infoUsuario = obtenerUsuarioId($usuarioId, $QueryBD);
          $listaErroresValidacionForm = [];

          if(!empty($infoUsuario)){            
            if($usuario != $infoUsuario["usuario"]){
              if(validarUsuario($usuario, $QueryBD)){
                array_push($listaErroresValidacionForm, $mensajeErrorUsuarioDuplicado);
              }              
            }
          }else{
            array_push($listaErroresValidacionForm, $mensajeErrorUsuarioIdNull);
          }
          
          if(!validarRolId($rolId, $QueryBD)){
            array_push($listaErroresValidacionForm, $mensajeErrorRol404);
          }
          if(!empty($contrasena) || !empty($repetirContrasena)){
            if($contrasena != $repetirContrasena){
              array_push($listaErroresValidacionForm, $mensajeErrorContrasenas);
            }
          }
          
          if(count($listaErroresValidacionForm) == 0){
            try{
              $contrasenaSHA1 = SHA1($contrasena);
              $queryTransactionSuccess = true; 
              $mysqli = $QueryBD->Conectar_BD();
              $mysqli->autocommit(FALSE); 
              $mysqli->query("UPDATE usuarios SET usuario = '".$usuario."', nombres = '".$nombresUsuario."', apellidos = '".$apellidosUsuario."' , rol_id = '".$rolId."', biblioteca_id = '".$bibliotecaId."' WHERE usuario_id = $usuarioId") ? null : $queryTransactionSuccess = false;
              if(!empty($mysqli->error)){throw new Exception($mysqli->error);}
              if(!empty($contrasena)){
                $mysqli->query("UPDATE usuarios SET contrasena = '".$contrasenaSHA1."' WHERE usuario_id = $usuarioId") ? null : $queryTransactionSuccess = false;  
                if(!empty($mysqli->error)){throw new Exception($mysqli->error);}
              }
              if($queryTransactionSuccess){
                $mysqli->commit();
                $request = array('estado' => "success", 'mensaje' => $mensajeSuccessUpdateUsuario);
              }else{
                $mysqli->rollback(); 
                $request = array('estado' => "error", 'mensaje' => $mensajeErrorUpdateUsuario);
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
      if(!empty($_POST['idUsuario']) && is_numeric($_POST['idUsuario'])){
          $usuarioId = $_POST['idUsuario'];          
          $listaErroresValidacionForm = [];

          if($usuarioId == $usuarioEnLinea){
            array_push($listaErroresValidacionForm, $mensajeErrorUsuarioEnLinea);
          }
          
          if(count($listaErroresValidacionForm) == 0){
            $queryTransactionSuccess = true; 
            $mysqli = $QueryBD->Conectar_BD();
            $mysqli->autocommit(FALSE);
            try{              
              $sqlBuscarUsuario =  "SELECT * FROM usuarios WHERE usuario_id = $usuarioId AND estado = '1' LIMIT 0,1";
              if($QueryBD->NumRowsQuery($sqlBuscarUsuario) > 0){
                $mysqli->query("UPDATE usuarios SET estado = 0 WHERE usuario_id = $usuarioId");
                if(!empty($mysqli->error)){throw new Exception($mysqli->error);}
                if($queryTransactionSuccess){
                  $mysqli->commit();
                  $request = array('estado' => "success", 'mensaje' => $mensajeSuccessDeleteUsuario); 
                }else{
                  $mysqli->rollback(); 
                  $request = array('estado' => "success", 'mensaje' => $mensajeErrorDeleteUsuario); 
                }
              }else{
                $request = array('estado' => "error", 'mensaje' => $mensajeErrorUsuario404);
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
         $request = array('estado' => "error", 'mensaje' => $mensajeErrorUsuarioIdNull);
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