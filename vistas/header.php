<?php
$usuarioHeader = $_SESSION["usuario"];
echo '
<header class="main-header">
  <a href="/" class="logo">
    <span class="logo-mini"><b>B</b></span>
    <span class="logo-lg">BIBLIOTECA</span>
  </a>
  <nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">              
            <span class="hidden-xs">'.$usuarioHeader.'</span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-footer">
                <a href="usuarios.php?opc=logout" class="btn"><i class="fa fa-sign-out"></i> Cerrar Sesión</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>';
?>