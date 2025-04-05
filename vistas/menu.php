<?php
echo '<aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu">
        <li class="header">MENU PRINCIPAL</li>';        
        if($_SESSION['rolId'] == 1){ 
          echo '          
          <li><a href="index.php?accion=inventario"><i class="fa fa-book"></i> <span>Inventario</span></a></li>
          <li><a href="index.php?accion=usuarios"><i class="fa fa-users"></i> <span>Usuarios</span></a></li>
          <li class=""><a href="index.php?accion=configuracion"><i class="fa fa-cog"></i> <span>Configuracion</span></a></li>';
        }
echo '
        <li><a href="usuarios.php?opc=logout"><i class="fa fa-sign-out"></i> <span>Cerrar Sesion</span></a></li>
      </ul>
    </section>
  </aside>';