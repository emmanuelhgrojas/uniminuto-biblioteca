<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
error_reporting(7);
date_default_timezone_set("America/Bogota");
require_once('Conexion.php');
require_once('funciones.php');
require_once('ClassValidarDatos.php');

$homeUrlPath = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
$homeUrlLogin = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';

$sqlConfiguracionApp = "SELECT * FROM configuracion LIMIT 0,1";
$queryConfiguracionApp = $QueryBD->GetQuery($sqlConfiguracionApp);

if(!empty($_SESSION['usuarioId']) && !empty($_SESSION['usuario']) && !empty($_SESSION['rolId'])){
  $usuarioId = $_SESSION['usuarioId'];
  $usuario = $_SESSION['usuario'];
  $rolId = $_SESSION['rolId'];
  $sqlValidarUsuario = "SELECT * FROM usuarios WHERE usuario_id = '$usuarioId' AND usuario = '$usuario' AND rol_id = '$rolId' AND estado = 1 LIMIT 0,1";
  $queryValidarUsuario = $QueryBD->GetQuery($sqlValidarUsuario);

  if($QueryBD->NumRowsQuery($sqlValidarUsuario) == 0 || $QueryBD->NumRowsQuery($sqlConfiguracionApp) == 0){
    header('Location: ' . $homeUrlPath."usuarios.php?opc=logout");
  }
}else {
    header('Location: ' . $homeUrlLogin);
}
$configuracionApp = $queryConfiguracionApp->fetch_array(); 
echo '
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Software Biblioteca</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="dist/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="dist/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">  
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet" type="text/css" href="dist/plugins/datatables/datatables.min.css"/>
  <link rel="stylesheet" type="text/css" href="dist/plugins/sweetalert/sweetalert.css"/>
  <link rel="stylesheet" type="text/css" href="dist/plugins/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="dist/css/main_stilos.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">';

	require_once('vistas/header.php');
	require_once('vistas/menu.php');
echo' 
  <div class="content-wrapper">
    <section class="content-header">
      <h1><span id="hora_actual" class="center-block"></span></h1>
    </section>
    <section class="content">   
      <div class="row">
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Teclas Especiales</h3>
            </div>
            <div class="box-body text-center">
              <h5>F2: Inventario, F3: Usuarios, F4: Configuración</h5>
            </div>            
          </div>
        </div>
      </div> 	 
      <div>';
      echo '
      </div>      
      <div id="mensajeSistema"></div>';
      	if(!empty($_GET["accion"])){
          $modulo = $validarDatos->limpiarCadena(!empty($_GET["accion"]) ? $_GET["accion"] : 'home', "texto");          
          $sqlModulo = "SELECT * FROM modulos AS m INNER JOIN modulosxrol AS mr ON mr.modulo_id = m.modulo_id INNER JOIN roles AS r ON r.rol_id = mr.rol_id  WHERE r.rol_id = '$rolId' AND  m.modulo = '".$modulo."'";
          $queryModulo = $QueryBD->GetQuery($sqlModulo);
          $moduloView = $queryModulo->fetch_array();
          if($moduloView){
            if(file_exists($moduloView["nombrevista"])){
              require_once($moduloView["nombrevista"]);
            }else{
              require_once('vistas/404.php');
            }
          }else{
            require_once('vistas/404.php');
          }      	
      	}else{
          require_once('vistas/404.php');
        }
echo '
    </section>
  </div>
  <footer class="main-footer">
     <div class="pull-right hidden-xs">
      <strong>Desarrollado por <a href="https://www.uniminuto.edu/">Uniminuto</a></strong>
    </div>
     <strong>Licenciado a '.$configuracionApp["razonsocial"].'</strong>
    <strong></strong>
  </footer>
  <div class="control-sidebar-bg"></div>
</div>
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="dist/plugins/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="dist/plugins/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="dist/plugins/datatables/datatables.min.js"></script>
<script type="text/javascript" src="dist/plugins/sweetalert/sweetalert.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="dist/plugins/moment/min/moment.min.js"></script>
<script src="dist/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<script src="dist/js/app.min.js"></script>
<script src="dist/js/aplicacion.js"></script>
</body>
</html>';
