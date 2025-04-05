<?php
session_start();
$homeurl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';

if(!empty($_SESSION['usuarioId']) && !empty($_SESSION['usuario']) && !empty($_SESSION['rolId'] && !$_SESSION['bibliotecaId'])){
  header('Location: '.$homeurl);
}
echo '<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Software Biblioteca</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="dist/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/main_stilos.css">
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet" type="text/css" href="dist/plugins/datatables/datatables.min.css"/>
  <link rel="stylesheet" type="text/css" href="dist/plugins/sweetalert/sweetalert.css"/>
</head>
  <body>
  <div class="row wrapperlogin">
    <div class="col-sm-5 col-md-4">
      <div class="login-box">
        <div class="login-logo">
          <a href="index.php"><b>Biblioteca</b></a>
        </div>
        <div id="mensaje" role="alert"></div>
        <div class="login-box-body">
          <p class="login-box-msg">Iniciar Sesión en Biblioteca</p>
          <form method="post">          
            <div class="form-group has-feedback">
              <input type="text" id="usuario" name="usuario" onkeyup="this.value=this.value.toUpperCase();" class="form-control" placeholder="Dijite Usuario" required>
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
              <input type="password" id="password" name="password" class="form-control" placeholder="Dijite Contraseña" required>
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <button type="submit" class="btn btn-color-main btn-block btn-flat" id="btn_login">Entrar</button>
              </div>
            </div>
          </form>
        </div>
      </div>      
    </div>  
    <div class="col-sm-7 col-md-8 fondoLogin">      
    </div>  
  </div>
  <script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script type="text/javascript" src="dist/plugins/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
  <script type="text/javascript" src="dist/plugins/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
  <script type="text/javascript" src="dist/plugins/datatables/datatables.min.js"></script>
  <script type="text/javascript" src="dist/plugins/sweetalert/sweetalert.min.js"></script>
  <script src="dist/bootstrap/js/bootstrap.min.js"></script>
  <script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script src="dist/js/app.min.js"></script>
  <script src="dist/js/aplicacion.js"></script>
  </body>
</html>';
