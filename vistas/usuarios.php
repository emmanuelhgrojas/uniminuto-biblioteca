<?php
echo '<div class="box box-color-main">
  <div class="box-header with-border">
    <h3 class="box-title">Usuarios</h3>
    <div class="box-tools pull-right">      
    </div>
  </div>
  <div class="box-body">
    <div class="row"><div class="col-lg-2 col-xs-3 alinearDerecha"><button type="button" class="btn btn-block btn-primary btn-lg" id="mostrarModalNuevoUsuario"><i class="fa fa-plus"></i> Usuarios</button></div></div>';
$sqlListaUsuarios = "SELECT u.*, r.* FROM usuarios AS u INNER JOIN roles AS r ON r.rol_id = u.rol_id WHERE u.estado = 1 ORDER BY usuario ASC";
$querySearch = $QueryBD->GetQuery($sqlListaUsuarios);
$totalUsuarios = $QueryBD->NumRowsQuery($sqlListaUsuarios);
          if($QueryBD->NumRowsQuery($sqlListaUsuarios) > 0){
            echo '<h4>Total Usuarios: '.$totalUsuarios.'</h4>';
            echo '<div class="table-responsive no-padding">
            <table class="table table-hover display" style="width:100%" id="tablaUsuarios" >
            <thead>
              <tr>
                <td><b>ID</b></td>
                <td><b>Usuario</b></td>
                <td><b>Nombres</b></td>
                <td><b>Apellidos</b></td>
                <td><b>Rol</b></td>
                <td class="text-center"><b>Accion</b></td>
              </tr>
            </thead>';
            $queryListaUsuarios = $QueryBD->GetQuery($sqlListaUsuarios);
            while ($usuario = $queryListaUsuarios->fetch_array()){ 
              echo '<tr id="IdFila_'.$usuario['usuario_id'].'">
                <td>'.$usuario['usuario_id'].'</td>                
                <td>'.$usuario['usuario'].'</td>
                <td>'.$usuario['nombres'].'</td>
                <td>'.$usuario['apellidos'].'</td>
                <td>'.$usuario['rol'].'</td>
                <td class="text-center">
                  <a href="#'.$usuario['usuario_id'].'" title="Modificar Usuario" class="btn btn-primary btn_ModificarUsuario"><i class="fa fa-edit" aria-hidden="true"></i></a>
                  <a href="#'.$usuario['usuario_id'].'" title="Eliminar Usuario" class="btn btn-danger btn_EliminarUsuario"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
              </tr>';
            } 
            echo '</table>';            
          }else{
            echo '<br><br><div class="alert alert-danger" role="alert">No existen usuarios registrados en el sistema.</div>';
          }        
      echo '</div>
  </div>
</div>';

echo '<div class="modal fade" id="modalFormUsuario">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Información Usuario</h4>
    </div>
    <div class="modal-body">
    <form role="form" name="FormUsuario" id="FormUsuario">
    <input type="hidden" name="tipoFormulario" id="tipoFormulario">
    <input type="hidden" name="usuarioId" id="usuarioId">
    <div class="box-body">
      <div class="row">
        <div class="col-md-12" id="mensajeModal"></div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="usuario">Usuario (*)</label>
            <input type="text" class="form-control" onkeyup="this.value=this.value.toUpperCase();" name="usuario" id="usuario" placeholder="Dijite Usuario">
          </div>
          <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Dijite Contraseña">
          </div>
          <div class="form-group">
            <label for="repetircontrasena">Repetir Contraseña</label>
            <input type="password" class="form-control" name="repetircontrasena" id="repetircontrasena" placeholder="Dijite Contraseña">
          </div>
          <div class="form-group">
            <label for="rolId">Rol (*)</label>
            <select class="form-control" name="rolId" id="rolId">
            <option value="">Seleccione Rol</option>';
            $sqlListaRoles = "SELECT * FROM roles ORDER BY rol ASC";
            $queryListaRoles = $QueryBD->GetQuery($sqlListaRoles);
            $totalRoles = $QueryBD->NumRowsQuery($sqlListaRoles);
            if($QueryBD->NumRowsQuery($sqlListaRoles) > 0){
              while ($rol = $queryListaRoles->fetch_array()){ 
                echo '<option value="'.$rol['rol_id'].'">'.$rol['rol'].'</option>';
              }
            }
          echo '</select>
          </div>    
          <div class="form-group">
            <label for="bibliotecaId">Biblioteca (*)</label>
            <select class="form-control" name="bibliotecaId" id="bibliotecaId">
            <option value="">Seleccione Biblioteca</option>';
            $sqlListaBibliotecas = "SELECT * FROM bibliotecas ORDER BY nombre ASC";
            $queryListaBibliotecas = $QueryBD->GetQuery($sqlListaBibliotecas);
            $totalBibliotecas = $QueryBD->NumRowsQuery($sqlListaBibliotecas);
            if($QueryBD->NumRowsQuery($sqlListaBibliotecas) > 0){
              while ($item = $queryListaBibliotecas->fetch_array()){ 
                echo '<option value="'.$item['id'].'">'.$item['nombre'].'</option>';
              }
            }
          echo '</select>
          </div>          
          <div class="form-group">
            <label for="nombres_usuario">Nombres (*)</label>
            <input type="text" class="form-control" onkeyup="this.value=this.value.toUpperCase();"  name="nombres_usuario" id="nombres_usuario" placeholder="Dijite Nombres del Usuario">
          </div>              
          <div class="form-group">
            <label for="apellidos_usuario">Apellidos (*)</label>
            <input type="text" class="form-control"  onkeyup="this.value=this.value.toUpperCase();" name="apellidos_usuario" id="apellidos_usuario" placeholder="Dijite Apellidos del Usuario">
          </div>
        </div>
      </div>
    </div>
  </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
      <button type="button" class="btn btn-primary" id="btn_NuevoUsuario">Guardar</button>
    </div>
  </div>
</div>
</div>';
