<?php
session_start();
date_default_timezone_set("America/Bogota");

echo '<div class="box box-color-main">
  <div class="box-header with-border">
    <h3 class="box-title">Materiales</h3>
    <div class="box-tools pull-right">      
    </div>
  </div>
  <div class="box-body">
    <div class="row"><div class="col-lg-2 col-xs-3 alinearDerecha"><button type="button" class="btn btn-block btn-primary btn-lg" id="mostrarModalNuevoMaterial"><i class="fa fa-plus"></i> Material</button></div></div>';

$bibliotecaId = $_SESSION['bibliotecaId'];  
$sqlLista = "SELECT
m.id,
m.titulo,
m.descripcion,
m.isbn,
m.issn,
m.codigo_referencia,
m.cantidad,
m.valor_monetario,
s.nombre AS seccion,
b.nombre AS biblioteca
FROM materiales m
JOIN secciones s ON m.seccion_id = s.id
JOIN bibliotecas b ON m.biblioteca_id = b.id
WHERE b.id = '$bibliotecaId' AND m.estado = 1 ORDER BY m.titulo ASC";
$querySearch = $QueryBD->GetQuery($sqlLista);
$totalInventario = $QueryBD->NumRowsQuery($sqlLista);
          if($QueryBD->NumRowsQuery($sqlLista) > 0){
            echo '<h4>Total Materiales: '.$totalInventario.'</h4>';
            echo '<div class="table-responsive no-padding">
            <table class="table table-hover display" style="width:100%" id="tablaInventario" >
            <thead>
              <tr>
                <td><b>ID</b></td>
                <td><b>Titulo</b></td>
                <td><b>Descripción</b></td>
                <td><b>Cod Ref</b></td>
                <td><b>isbn</b></td>
                <td><b>issn</b></td>
                <td><b>Sección</b></td>
                <td><b>Biblioteca</b></td>
                <td><b>Cantidad</b></td>
                <td><b>Valor</b></td>
                <td class="text-center"><b>Accion</b></td>
              </tr>
            </thead>';
            $queryListaInventario = $QueryBD->GetQuery($sqlLista);
            while ($inventario = $queryListaInventario->fetch_array()){ 
              echo '<tr id="IdFila_'.$inventario['id'].'">
                <td>'.$inventario['id'].'</td>                
                <td>'.$inventario['titulo'].'</td>
                <td>'.$inventario['descripcion'].'</td>
                <td>'.$inventario['codigo_referencia'].'</td>
                <td>'.$inventario['isbn'].'</td>
                <td>'.$inventario['issn'].'</td>
                <td>'.$inventario['seccion'].'</td>
                <td>'.$inventario['biblioteca'].'</td>
                <td>'.$inventario['cantidad'].'</td>
                <td>$'.number_format($inventario['valor_monetario'], 0, ',', '.').'</td>
                <td class="text-center">
                  <a href="#'.$inventario['id'].'" title="Ver Material" class="btn btn-primary btn_VerInventario"><i class="fa fa-eye" aria-hidden="true"></i></a>
                  <a href="#'.$inventario['id'].'" title="Modificar Material" class="btn btn-info btn_ModificarInventario"><i class="fa fa-edit" aria-hidden="true"></i></a>
                  <a href="#'.$inventario['id'].'" title="Eliminar Material" class="btn btn-danger btn_EliminarInventario"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
              </tr>';
            } 
            echo '</table>';            
          }else{
            echo '<br><br><div class="alert alert-danger" role="alert">No existen materiales registrados en el sistema.</div>';
          }        
      echo '</div>
  </div>
</div>';
echo '<div class="modal fade" id="modalFormInventario">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Información Inventario</h4>
    </div>
    <div class="modal-body">
    <form role="form" name="FormInventario" id="FormInventario">
    <input type="hidden" name="tipoFormulario" id="tipoFormulario">
    <input type="hidden" name="inventarioId" id="inventarioId">
    <div class="box-body">
      <div class="row">
        <div class="col-md-12" id="mensajeModal"></div>
        <div class="col-md-12">
          <div class="form-group">
            <label for="titulo">Titulo (*)</label>
            <input type="text" class="form-control" onkeyup="this.value=this.value.toUpperCase();" name="titulo" id="titulo" placeholder="Dijite Titulo">
          </div>
          <div class="form-group">
            <label for="ISBN">ISBN (*)</label>
            <input type="text" class="form-control" onkeyup="this.value=this.value.toUpperCase();" name="isbn" id="isbn" placeholder="Dijite ISBN">
          </div>
          <div class="form-group">
            <label for="issn">ISSN (*)</label>
            <input type="text" class="form-control" onkeyup="this.value=this.value.toUpperCase();" name="issn" id="issn" placeholder="Dijite ISSN">
          </div>
          <div class="form-group">
            <label for="codigo_referencia">Codigo Referencia (*)</label>
            <input type="text" class="form-control" onkeyup="this.value=this.value.toUpperCase();" name="codigo_referencia" id="codigo_referencia" placeholder="Dijite Codigo Referencia">
          </div>
          <div class="form-group">
            <label for="descripcion">Descripción (*)</label>
            <input type="text" class="form-control" onkeyup="this.value=this.value.toUpperCase();" name="descripcion" id="descripcion" placeholder="Dijite Descripción">
          </div>
          <div class="form-group">
            <label for="cantidad">Cantidad (*)</label>
            <input type="number" class="form-control" onkeyup="this.value=this.value.toUpperCase();"  name="cantidad" id="cantidad" placeholder="Dijite Cantidad">
          </div>  
          <div class="form-group">
            <label for="valor">Valor (*)</label>
            <input type="number" class="form-control" onkeyup="this.value=this.value.toUpperCase();"  name="valor" id="valor" placeholder="Dijite Valor">
          </div>  
          <div class="form-group" id="valorTotalMaterial">
            <label for="valorTotal">Valor Total</label>
            <input type="text" class="form-control" name="valorTotal" id="valorTotal">
          </div>  
          <div class="form-group">
            <label for="autorId">Autor (*)</label>
            <select multiple class="form-control" name="autorId[]" id="autorId">
            <option value="">Seleccione Autor</option>';
            $sqlListaAutores = "SELECT * FROM autores ORDER BY nombre ASC";
            $queryListaAutores = $QueryBD->GetQuery($sqlListaAutores);
            $totalAutores = $QueryBD->NumRowsQuery($sqlListaAutores);
            if($QueryBD->NumRowsQuery($sqlListaAutores) > 0){
              while ($item = $queryListaAutores->fetch_array()){ 
                echo '<option value="'.$item['id'].'">'.$item['nombre'].'</option>';
              }
            }
            echo '</select>
          </div>    
          <div class="form-group">
            <label for="seccionId">Sección (*)</label>
            <select class="form-control" name="seccionId" id="seccionId">
            <option value="">Seleccione Sección</option>';
            $sqlListaSecciones = "SELECT * FROM secciones ORDER BY nombre ASC";
            $queryListaSecciones = $QueryBD->GetQuery($sqlListaSecciones);
            $totalSecciones = $QueryBD->NumRowsQuery($sqlListaSecciones);
            if($QueryBD->NumRowsQuery($sqlListaSecciones) > 0){
              while ($item = $queryListaSecciones->fetch_array()){ 
                echo '<option value="'.$item['id'].'">'.$item['nombre'].'</option>';
              }
            }
            echo '</select>
          </div>    
          <div class="form-group">
            <label for="tipoMaterialId">Tipo (*)</label>
            <select class="form-control" name="tipoMaterialId" id="tipoMaterialId">
            <option value="">Seleccione Tipo</option>';
            $sqlListaTiposMateriales = "SELECT * FROM tipos_material ORDER BY nombre ASC";
            $queryListaTiposMateriales = $QueryBD->GetQuery($sqlListaTiposMateriales);
            $totalTiposMateriales = $QueryBD->NumRowsQuery($sqlListaTiposMateriales);
            if($QueryBD->NumRowsQuery($sqlListaTiposMateriales) > 0){
              while ($item = $queryListaTiposMateriales->fetch_array()){ 
                echo '<option value="'.$item['id'].'">'.$item['nombre'].'</option>';
              }
            }
            echo '</select>
          </div>    
          <div class="form-group">
            <label for="bibliotecaId">Biblioteca (*)</label>
            <select class="form-control" name="bibliotecaId" id="bibliotecaId" disabled>
            <option value="">Seleccione Biblioteca</option>';           
            $sqlListaBibliotecas = "SELECT * FROM bibliotecas WHERE id = '".$bibliotecaId."' ORDER BY nombre ASC";
            $queryListaBibliotecas = $QueryBD->GetQuery($sqlListaBibliotecas);
            $totalBibliotecas = $QueryBD->NumRowsQuery($sqlListaBibliotecas);
            if($QueryBD->NumRowsQuery($sqlListaBibliotecas) > 0){
              while ($item = $queryListaBibliotecas->fetch_array()){ 
                echo '<option value="'.$item['id'].'" selected>'.$item['nombre'].'</option>';
              }
            }
          echo '</select>
          </div>
        </div>
      </div>
    </div>
  </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
      <button type="button" class="btn btn-primary" id="btn_NuevoInventario">Guardar</button>
    </div>
  </div>
</div>
</div>';
