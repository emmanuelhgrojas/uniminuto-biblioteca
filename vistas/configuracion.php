<?php
session_start();
echo '
<div class="box box-color-main">
  <div class="box-header with-border">
    <h3 class="box-title">Configuración</h3>
		<div class="box-tools pull-right">      
      </div>
  </div>
  <div class="box-body">
		<div class="row">
			<form role="form" name="FormConfiguracion" id="FormConfiguracion">
        <div class="col-md-6">              
          <div class="form-group">
            <label for="razonSocial">Razón Social:</label>
            <textarea class="form-control" rows="3" id="razonSocial" onkeyup="this.value=this.value.toUpperCase();" name="razonSocial" placeholder="Introduce Razón Social">'.$configuracionApp["razonsocial"].'</textarea>
          </div>
          <div class="form-group">
            <label for="direccion">Dirección:</label>
            <textarea class="form-control" rows="3" id="direccion"  name="direccion" onkeyup="this.value=this.value.toUpperCase();" placeholder="Introduce Dirección">'.$configuracionApp["direccion"].'</textarea>
          </div>           
        </div>
        <div class="col-md-6">              
          <div class="form-group">
            <label for="nit">NIT:</label>
            <input type="text" class="form-control" id="nit"  name="nit" value="'.$configuracionApp["nit"].'"  placeholder="Introduce Razón Social">
          </div>
          <div class="form-group">
            <label for="telefono">Telefono:</label>
            <input type="text" class="form-control" id="telefono"  name="telefono" value="'.$configuracionApp["telefono"].'"  placeholder="Introduce Telefono">
          </div>           
          <button id="btn_GuardarConfiguracion" class="btn btn-primary">Guardar</button>
        </div>						
			</form>
		</div>
  </div>
  <div class="box-footer">
  </div>
</div>';
?>