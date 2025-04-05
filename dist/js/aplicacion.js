$(document).ready( function() { 
	var totalTiempoMensaje = 10000;
	var totalRegistrosPaginados = 50;

	$.get = function(key)   {  
		key = key.replace(/[\[]/, '\\[');  
		key = key.replace(/[\]]/, '\\]');  
		var pattern = "[\\?&]" + key + "=([^&#]*)";  
		var regex = new RegExp(pattern);  
		var url = unescape(window.location.href);  
		var results = regex.exec(url);  
		if (results === null) {  
				return null;  
		} else {  
				return results[1];  
		}  
	} 

	let urlCarpeta = "biblioteca";	
	var accion = $.get("accion");

	


	/* Teclas directas para las paginas del aplicativo */
	$("body").keydown(function(e){		
		var keyCode = e.keyCode || e.which;
		if(keyCode == 113){
			location.href = "index.php?accion=inventario";
		}else if(keyCode == 114){
			location.href = "index.php?accion=usuarios";
		}else if(keyCode == 115){
			location.href = "index.php?accion=configuracion";
		}
});


/*
      ##########################################################
                      Funciones Sesion
      ##########################################################
*/ 
$( "#btn_login" ).click(function(e) {
	e.preventDefault();
	var usuario = $("#usuario").val();
	var clave = $("#password").val();
	$.ajax({
		url : 'usuarios.php?opc=login',
		type : 'POST',
		data: 'usuario='+usuario+'&password='+clave,
		dataType : 'json',
		success : function(response){
			if(response.estado == "success"){
				setTimeout(function(){
					location.href = "index.php";
				}, 3000);
			}
			swal('Información', response.mensaje, response.estado);
		},
		error : function(request, status, error){
			mostrarErrorEnConsola(request, status, error);
		}
	})
});

/*
      ##########################################################
                      Funciones Inventario
      ##########################################################
*/ 
$("#mostrarModalNuevoMaterial").click(function(e){
	$('#FormInventario')[0].reset();		
	$("#tipoFormulario").val('nuevo');
  $("#usuarioId").val('');
  $("#modalFormInventario").modal('show');
});
$( "#btn_NuevoInventario" ).click(function(e) {
	e.preventDefault();
	var formData = new FormData($("#FormInventario")[0]);
	var tipoFormulario = $("#tipoFormulario").val();
	  var urlPeticion = (tipoFormulario == "nuevo") ? 'inventarios.php?opc=nuevo' : 'inventarios.php?opc=modificar';
	  
	$.ajax({
	  url : urlPeticion,
	  type: "POST",
	  data: formData,
	  cache: false,
	  contentType: false,
	  datatype: 'JSON',
	  processData: false,
	  success : function(response){
		showMensaje(response.mensaje,totalTiempoMensaje,response.estado,"mensajeModal",1,"msg");
		if(response.estado == "success"){
		  $('#FormInventario')[0].reset();
		  setTimeout(function(){
			$("#modalFormInventario").modal('hide');
			location.href= "index.php?accion=inventario";
		  }, 1000);		
		}
	  },
	  error : function(request, status, error){
		mostrarErrorEnConsola(request, status, error);
	  }
	});
  });
  $(".btn_VerInventario").click(function(e){
	e.preventDefault();
	var IdElement = ($(this).attr('href')).split("#");
	var inventarioId = IdElement[1];
	$('#FormInventario')[0].reset();	
	$('#btn_NuevoInventario').hide();
	$('#FormInventario input').attr('readonly', 'readonly');
	$('#FormInventario select').attr('readonly', 'readonly');
	$('#valorTotalMaterial').show();	
	if(!isNaN(inventarioId)){
	  $.ajax({
			url : 'inventarios.php?opc=buscar',
			type : 'GET',
			data: 'inventario_id='+inventarioId,
			dataType : 'json',
			success : function(response){
			  showMensaje(response.mensaje,totalTiempoMensaje,response.estado,"mensajeModal",1,"msg");
			  if(response.estado == "success"){
				$("#tipoFormulario").val('editar');
				$("#inventarioId").val(response.info['id']);
				$("#titulo").val(response.info['titulo']);
				$("#isbn").val(response.info['isbn']);
				$("#issn").val(response.info['issn']);
				$("#descripcion").val(response.info['descripcion']);
				$("#codigo_referencia").val(response.info['codigo_referencia']);
				$("#cantidad").val(response.info['cantidad']);
				$("#valor").val(response.info['valor_monetario']);
				$("#seccionId").val(response.info['seccion_id']);
				$("#tipoMaterialId").val(response.info['tipo_material_id']);
				$("#valorTotal").val(response.info['valor_monetario'] * response.info['cantidad']);
				console.log(response.info['valor_monetario'] * response.info['cantidad']);
				const select = document.getElementById('autorId');
				const autoresSeleccionados = response.autores;
				for (let i = 0; i < select.options.length; i++) {
				const option = select.options[i];
				option.selected = autoresSeleccionados.includes(parseInt(option.value));
				}
				$("#modalFormInventario").modal('show');
			  }
			},
			error : function(request, status, error){
			  mostrarErrorEnConsola(request, status, error);
			}
	  });
	}else{
	  showMensaje("Seleccione el material que desea modificar la información.",totalTiempoMensaje,'error',"mensajeSistema",1,"msg");   
	}  
  });

  $(".btn_ModificarInventario").click(function(e){
	e.preventDefault();
	var IdElement = ($(this).attr('href')).split("#");
	var inventarioId = IdElement[1];
	$('#FormInventario')[0].reset();
	$('#btn_NuevoInventario').show();	
	$('#FormInventario input').attr('readonly', false);
	$('#FormInventario select').attr('readonly', false);	
	$('#valorTotalMaterial').hide();		
	if(!isNaN(inventarioId)){
	  $.ajax({
			url : 'inventarios.php?opc=buscar',
			type : 'GET',
			data: 'inventario_id='+inventarioId,
			dataType : 'json',
			success : function(response){
			  showMensaje(response.mensaje,totalTiempoMensaje,response.estado,"mensajeModal",1,"msg");
			  if(response.estado == "success"){
				$("#tipoFormulario").val('editar');
				$("#inventarioId").val(response.info['id']);
				$("#titulo").val(response.info['titulo']);
				$("#isbn").val(response.info['isbn']);
				$("#issn").val(response.info['issn']);
				$("#descripcion").val(response.info['descripcion']);
				$("#codigo_referencia").val(response.info['codigo_referencia']);
				$("#cantidad").val(response.info['cantidad']);
				$("#valor").val(response.info['valor_monetario']);
				$("#seccionId").val(response.info['seccion_id']);
				$("#tipoMaterialId").val(response.info['tipo_material_id']);
				const select = document.getElementById('autorId');
				const autoresSeleccionados = response.autores;
				for (let i = 0; i < select.options.length; i++) {
				const option = select.options[i];
				option.selected = autoresSeleccionados.includes(parseInt(option.value));
				}
				$("#modalFormInventario").modal('show');
			  }
			},
			error : function(request, status, error){
			  mostrarErrorEnConsola(request, status, error);
			}
	  });
	}else{
	  showMensaje("Seleccione el material que desea modificar la información.",totalTiempoMensaje,'error',"mensajeSistema",1,"msg");   
	}  
  });
  
  $(".btn_EliminarInventario").click(function(e) {
	e.preventDefault();
	var IdElement = ($(this).attr('href')).split("#");
	  var inventarioId = parseInt(IdElement[1]);
	  
	if(!isNaN(inventarioId)){
	  swal({   
		title: "¿Desea eliminar este material?",   
		text: "No podras deshacer este paso",   
		type: "warning",   
		showCancelButton: true,
		cancelButtonText: "Cancelar!",   
		confirmButtonColor: "#DD6B55",   
		confirmButtonText: "Continuar!",   
		closeOnConfirm: false },
		function(){   
		  $.ajax({
			url : 'inventarios.php?opc=eliminar',
			type : 'POST',
			data: 'inventarioId='+inventarioId,
			dataType : 'json',
			success : function(json){
			  showMensaje(json.mensaje,totalTiempoMensaje,json.estado,"mensajeSistema",1,"msg");   
			  if(json.estado == "success"){
				$("#IdFila_"+inventarioId).remove();  		
			  }
			  swal.close(); 
			},
			error : function(request, status, error){
			  mostrarErrorEnConsola(request, status, error);
			}
		  });
	  });
	}else{
	  showMensaje("Seleccione el material que desea eliminar.",totalTiempoMensaje,'error',"mensajeSistema",1,"msg");   
	}
  });

  $('#tablaInventario').DataTable({
	initComplete: function () {		
},
	"iDisplayLength": totalRegistrosPaginados,
	"aLengthMenu": [[10, 50, 100,500, -1], [10, 50, 100,500, "Todos"]],
	"bLengthChange":true,
	"bPaginate":true,
	select: true,
	"language": {
		"url": "dist/plugins/datatables/Spanish.json",
		"select": {
						rows: {
								_: "[%d]",
								0: " ",
								1: "[1]"
						}
				}
	},
	dom: "<'row' <'form-inline' <'col-sm-2 col-md-2 col-lg-2'l>"
											+"<'col-sm-7 col-md-7 col-lg-7'B>"
											+"<'col-sm-3 col-md-3 col-lg-3'f>>>"
											+"<rt>"
											+"<'row'<'form-inline'"
											+"<'col-sm-4 col-md-4 col-lg-4'i>"
											+"<'col-sm-2 col-md-2 col-lg-2'R>"
											+"<'col-sm-6 col-md-6 col-lg-6'p>>>",//'Bfrtip',
								 buttons: [
										 {
												 extend:    'print',
												 text:      '<i class="fa fa-print"></i> Imprimir',
												 titleAttr: 'Imprimir',
												 title: 'Lista de Usuarios',
												 exportOptions: {
															 modifier: {
																	 page: 'current'
																	 }
													}
										 },
										 {
												 extend: 'collection',
												 text: '<i class="fa fa-wrench"></i> Herramientas',
												 buttons: [{
													 extend:    'copyHtml5',
													 text:      '<a type="button" id="copiar" class="copiar" title="copiar"><i class="fa fa-files-o"></i> Copiar datos</a>',
													 titleAttr: 'Copiar',
													 title: 'Lista de Usuarios',
													 exportOptions: {
															 modifier: {
																	 page: 'current'
																	 }
															 }
													 },
													 {
													 extend:    'excelHtml5',
													 text:      '<a type="button" id="permisoExcel" titleAttr="Exportar Excel" class="excel" title="excel"><i class="fa fa-file-excel-o"></i> Exportar a Excel </a>',
													 fileName : "ListaUsuarios.xls",
													 title: 'Lista de Usuarios',
													 exportOptions: {
															 modifier: {
																	 page: 'current'
																	 }
															 }
													 },
													 {
													 extend:    'pdfHtml5',
													 text:      '<a type="button" id="permisoPdf" titleAttr="Exportar Excel" class="pdf" title="pdf"><i class="fa fa-file-pdf-o"></i> Exportar a PDF </a>',
													 fileName : "ListaUsuarios.pdf",
													 title: 'Lista de Usuarios',
													 exportOptions: {
															 modifier: {
																	 page: 'current'
																	 }
															 }
													 }
													 ],
													 titleAttr: 'Herramientas'
										 }
									 ],
									 "bDestroy": true,
									 "bJQueryUI":true
}).order( [ 1, 'desc' ] );









/*
      ##########################################################
                      Funciones Usuarios
      ##########################################################
*/ 
$("#mostrarModalNuevoUsuario").click(function(e){
	$('#FormUsuario')[0].reset();		
	$("#tipoFormulario").val('nuevo');
  $("#usuarioId").val('');
  $("#modalFormUsuario").modal('show');
});

$( "#btn_NuevoUsuario" ).click(function(e) {
  e.preventDefault();
  var formData = new FormData($("#FormUsuario")[0]);
  var tipoFormulario = $("#tipoFormulario").val();
	var urlPeticion = (tipoFormulario == "nuevo") ? 'usuarios.php?opc=nuevo' : 'usuarios.php?opc=modificar';
	
  $.ajax({
    url : urlPeticion,
    type: "POST",
    data: formData,
    cache: false,
    contentType: false,
    datatype: 'JSON',
    processData: false,
    success : function(response){
      showMensaje(response.mensaje,totalTiempoMensaje,response.estado,"mensajeModal",1,"msg");
      if(response.estado == "success"){
        $('#FormUsuario')[0].reset();
        setTimeout(function(){
          $("#modalFormUsuario").modal('hide');
          location.href= "index.php?accion=usuarios";
        }, 1000);		
      }
    },
    error : function(request, status, error){
      mostrarErrorEnConsola(request, status, error);
    }
  });
});

$(".btn_ModificarUsuario").click(function(e){
  e.preventDefault();
  var IdElement = ($(this).attr('href')).split("#");
  var usuarioId = IdElement[1];
  $('#FormUsuario')[0].reset();		
  if(!isNaN(usuarioId)){
    $.ajax({
          url : 'usuarios.php?opc=buscar',
          type : 'GET',
          data: 'usuario_id='+usuarioId,
          dataType : 'json',
          success : function(response){
            showMensaje(response.mensaje,totalTiempoMensaje,response.estado,"mensajeModal",1,"msg");
            if(response.estado == "success"){
							$("#tipoFormulario").val('editar');
							$("#usuarioId").val(response.usuario['usuario_id']);
							$("#usuario").val(response.usuario['usuario']);
							$("#rolId").val(response.usuario['rol_id']);
							$("#bibliotecaId").val(response.usuario['biblioteca_id']);
							$("#nombres_usuario").val(response.usuario['nombres']);
							$("#apellidos_usuario").val(response.usuario['apellidos']);
							$("#modalFormUsuario").modal('show');
            }
          },
          error : function(request, status, error){
            mostrarErrorEnConsola(request, status, error);
          }
    });
  }else{
    showMensaje("Seleccione el usuario que desea modificar la información.",totalTiempoMensaje,'error',"mensajeSistema",1,"msg");   
  }  
});

$(".btn_EliminarUsuario").click(function(e) {
  e.preventDefault();
  var IdElement = ($(this).attr('href')).split("#");
	var usuarioId = parseInt(IdElement[1]);
	
  if(!isNaN(usuarioId)){
    swal({   
      title: "¿Desea eliminar este usuario?",   
      text: "No podras deshacer este paso",   
      type: "warning",   
      showCancelButton: true,
      cancelButtonText: "Cancelar!",   
      confirmButtonColor: "#DD6B55",   
      confirmButtonText: "Continuar!",   
      closeOnConfirm: false },
      function(){   
        $.ajax({
          url : 'usuarios.php?opc=eliminar',
          type : 'POST',
          data: 'idUsuario='+usuarioId,
          dataType : 'json',
          success : function(json){
            showMensaje(json.mensaje,totalTiempoMensaje,json.estado,"mensajeSistema",1,"msg");   
            if(json.estado == "success"){
              $("#IdFila_"+usuarioId).remove();  		
            }
            swal.close(); 
          },
          error : function(request, status, error){
            mostrarErrorEnConsola(request, status, error);
          }
        });
    });
  }else{
    showMensaje("Seleccione el usuario que desea eliminar.",totalTiempoMensaje,'error',"mensajeSistema",1,"msg");   
  }
});

$('#tablaUsuarios').DataTable({
	initComplete: function () {
},
	"iDisplayLength": totalRegistrosPaginados,
	"aLengthMenu": [[10, 50, 100,500, -1], [10, 50, 100,500, "Todos"]],
	"bLengthChange":true,
	"bPaginate":true,
	select: true,
	"language": {
		"url": "dist/plugins/datatables/Spanish.json",
		"select": {
						rows: {
								_: "[%d]",
								0: " ",
								1: "[1]"
						}
				}
	},
	dom: "<'row' <'form-inline' <'col-sm-2 col-md-2 col-lg-2'l>"
											+"<'col-sm-7 col-md-7 col-lg-7'B>"
											+"<'col-sm-3 col-md-3 col-lg-3'f>>>"
											+"<rt>"
											+"<'row'<'form-inline'"
											+"<'col-sm-4 col-md-4 col-lg-4'i>"
											+"<'col-sm-2 col-md-2 col-lg-2'R>"
											+"<'col-sm-6 col-md-6 col-lg-6'p>>>",//'Bfrtip',
								 buttons: [
										 {
												 extend:    'print',
												 text:      '<i class="fa fa-print"></i> Imprimir',
												 titleAttr: 'Imprimir',
												 title: 'Lista de Usuarios',
												 exportOptions: {
															 modifier: {
																	 page: 'current'
																	 }
													}
										 },
										 {
												 extend: 'collection',
												 text: '<i class="fa fa-wrench"></i> Herramientas',
												 buttons: [{
													 extend:    'copyHtml5',
													 text:      '<a type="button" id="copiar" class="copiar" title="copiar"><i class="fa fa-files-o"></i> Copiar datos</a>',
													 titleAttr: 'Copiar',
													 title: 'Lista de Usuarios',
													 exportOptions: {
															 modifier: {
																	 page: 'current'
																	 }
															 }
													 },
													 {
													 extend:    'excelHtml5',
													 text:      '<a type="button" id="permisoExcel" titleAttr="Exportar Excel" class="excel" title="excel"><i class="fa fa-file-excel-o"></i> Exportar a Excel </a>',
													 fileName : "ListaUsuarios.xls",
													 title: 'Lista de Usuarios',
													 exportOptions: {
															 modifier: {
																	 page: 'current'
																	 }
															 }
													 },
													 {
													 extend:    'pdfHtml5',
													 text:      '<a type="button" id="permisoPdf" titleAttr="Exportar Excel" class="pdf" title="pdf"><i class="fa fa-file-pdf-o"></i> Exportar a PDF </a>',
													 fileName : "ListaUsuarios.pdf",
													 title: 'Lista de Usuarios',
													 exportOptions: {
															 modifier: {
																	 page: 'current'
																	 }
															 }
													 }
													 ],
													 titleAttr: 'Herramientas'
										 }
									 ],
									 "bDestroy": true,
									 "bJQueryUI":true
}).order( [ 1, 'desc' ] );

/*
      ##########################################################
                      Funciones Configuracion
      ##########################################################
	*/ 
	$("#btn_GuardarConfiguracion").click(function(e) {
		e.preventDefault();
		var formData = new FormData($("#FormConfiguracion")[0]);
		$.ajax({
			url : "configuracion.php?opc=modificar",
			type: "POST",
			data: formData,
			cache: false,
			contentType: false,
			datatype: 'JSON',
			processData: false,
			success : function(response){
				showMensaje(response.mensaje,totalTiempoMensaje,response.estado,"mensajeSistema",1,"msg");
				if(response.estado == "success"){
					$('#FormConfiguracion')[0].reset();
				}
			},
			error : function(request, status, error){
				mostrarErrorEnConsola(request, status, error);
			}
		});
	});



	function escapeRegExp(string) {
		return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); 
	}
	
	$.get = function(key)   {  
        key = key.replace(/[\[]/, '\\[');  
        key = key.replace(/[\]]/, '\\]');  
        var pattern = "[\\?&]" + key + "=([^&#]*)";  
        var regex = new RegExp(pattern);  
        var url = unescape(window.location.href);  
        var results = regex.exec(url);  
        if (results === null) {  
            return null;  
        } else {  
            return results[1];  
        }  
    } 










//Date picker
	$('#filtroReporteEstadiaPorFecha').datepicker({autoclose: true });
} ); 



function showMensaje(mensaje,tiempomsg,estadomsg,div,scroll,divscroll){ 
	if(estadomsg == "error"){
		$("#"+div).html("<div class='alert alert-danger' role='alert'><i class='fa fa-info-circle'></i> "+mensaje+"</div>").fadeIn();
	}
	if(estadomsg == "success"){
		$("#"+div).html("<div class='alert alert-success' role='alert'><i class='fa fa-check-circle'></i> "+mensaje+"</div>").fadeIn();
	}
	setTimeout(function(){ $("#"+div).fadeOut('slow'); }, tiempomsg);
	if(scroll == 1){
        movscroll(2,divscroll);    
    }else if(typeof scroll === "undefined" || scroll == 0){
        movscroll('0');
    } 
}

function mostrarErrorEnConsola(request, status, error){
  console.log(request);
}

function movscroll(opc,div){
    if(typeof opc !== "undefined" && opc != "" && opc == "0"){
        $("html, body").animate({scrollTop:"0px"},{duration:"slow"});
    }else if(opc == "1"){
        //obtenemos la altura del documento
        var altura = $(document).height()
        $("html, body").animate({scrollTop:altura+"px"},{duration:"slow"});
    }else if(opc == "2"){
        if($(div).length)
        {
            var target_offset = $(div).offset()
            var target_top = target_offset.top
            $('html,body').animate({scrollTop:target_top},{duration:"slow"})
        }
    }
}
function ConvertirHora(hora){
	var newhora = hora.split(":");
	var hours = newhora[0];
	var minutes = newhora[1];
	var seconds = newhora[2];
	var dn="PM"

	if(hours.length > 1){
		if(hours.substr(0,1) == 0){
			dn="AM"
		}else{
			if (hours<12)
			    dn="AM"
			if (hours>12)
			    hours=hours-12
			if (hours==0)
			 	hours=12
			if (hours<=9)
			   	hours="0"+hours
		}
	}else{
		dn="AM"
		hours="0"+hours
	}
	if(minutes.length == 1 && minutes <= 9){
		minutes="0"+minutes
	}
	if(seconds.length == 1 && seconds <= 9){
		seconds="0"+seconds
	}
    //change font size here to your desire
    myclock=""+hours+":"+minutes+":"+seconds+" "+dn
	return myclock;
}
function ObtenerFecha(){
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	if(dd<10) {
	    dd='0'+dd
	}
	if(mm<10) {
	    mm='0'+mm
	} 
	return today = dd+'/'+mm+'/'+yyyy;
}
function ObtenerHora(){
	var Digital=new Date()
	var hours=Digital.getHours()
	var minutes=Digital.getMinutes()
	var seconds=Digital.getSeconds()
	var dn="PM"
	if (hours<12)
	    dn="AM"
	if (hours>12)
	    hours=hours-12
	if (hours==0)
	 	hours=12
	if (hours<=9)
	   	hours="0"+hours
	if (minutes<=9)
	    minutes="0"+minutes
	if (seconds<=9)
	    seconds="0"+seconds
	    //change font size here to your desire
	    myclock=""+hours+":"+minutes+":"+seconds+" "+dn
	return myclock;
}

function MostrarReloj(){
	    if (!document.layers&&!document.all&&!document.getElementById)
	        return
	        var Digital=new Date()
	        var hours=Digital.getHours()
	        var minutes=Digital.getMinutes()
	        var seconds=Digital.getSeconds()
	        var dn="PM"
	        if (hours<12)
		        dn="AM"
	        if (hours>12)
	    	    hours=hours-12
	        if (hours==0)
	        	hours=12
	        if (hours<=9)
	        	hours="0"+hours
	        if (minutes<=9)
		        minutes="0"+minutes
	        if (seconds<=9)
	    	    seconds="0"+seconds
	        //change font size here to your desire
	        myclock=""+hours+":"+minutes+":"+seconds+" "+dn
	        if (document.layers){
	        	document.layers.liveclock.document.write(myclock)
	        	document.layers.liveclock.document.close()
	        }
	        else if (document.all)
	        	liveclock.innerHTML=myclock
	        else if (document.getElementById)	        	
	        	setTimeout("MostrarReloj()",1000)
	}

	function resaltarFilasBloqueadas(divPadre, dataTable, numeroColumna){
		$(dataTable).DataTable();
		var listaEstadosAmarillos = ["Pendiente", "Abierta"];
		var listaEstadosVerdes = ["Facturado", "Vigente", "Activo", "Cerrada"];
		var listaEstadosRojo = ["Cancelado", "Cancelada", "Vencida", "Vencido"];
		$(divPadre + " " + dataTable + " tbody tr").each(function (index) {
				var textoCampoTd = $('td', this).eq(numeroColumna).text(); 
				if(listaEstadosVerdes.indexOf(textoCampoTd) != -1){
					$('td', this).css({"background": "rgb(208, 233, 198)", "color": "black"});
				}else if(listaEstadosRojo.indexOf(textoCampoTd) != -1){
					$('td', this).css({"background": "rgb(235, 204, 204)", "color": "black"});
				}else if(listaEstadosAmarillos.indexOf(textoCampoTd) != -1){
					$('td', this).css({"background": "#f39c12", "color": "black"});
				}
		});
	}

	MostrarReloj();
	
	

	function cargarListaOptions(url, elemento, elementoTitulo, selected, disabledCampo){
		var disabledCampo = (typeof disabledCampo === "undefined" || !disabledCampo) ? false : true;

		$.ajax({
			url : url,
			type : 'GET',
			dataType : 'json',
			success : function(data){
				if((data).hasOwnProperty('lista')){
					let lista = data.lista;
					$(elemento.toString()).html('<option value="" selected>SELECCIONE ' + elementoTitulo.toUpperCase() + '</option>').prop("disabled", false);
					if(lista.length > 0){
							$.each(lista, function(id, value) {
									$(elemento.toString()).append('<option value="'+value["elementoId"]+'">'+value["elemento"]+'</option>');
							});
							if(typeof selected === "object" && selected != null){
									$.each(elemento, function(elementoId, elementoActual) {
											$(elementoActual).val(selected[elementoId]).focus().prop("disabled", disabledCampo);
									});
							}else{
									$(elemento).val(selected).focus().prop("disabled", disabledCampo);
							}
					}else{
							if(typeof selected === "object" && selected != null){
									$.each(elemento, function(elementoId, elementoActual) {
											$(elementoActual).prop("disabled", true);
									});
							}else{
									$(elemento).prop("disabled", true);
							}
					}
				}else{
					$(elemento.toString()).html('<option value="" selected>SELECCIONE ' + elementoTitulo.toUpperCase() + '</option>').prop("disabled", true);
				}	
			},
			error : function(request, status, error){
				mostrarErrorEnConsola(request, status, error);
			}
		});
	}