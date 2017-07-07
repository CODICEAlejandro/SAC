
function buscaRegistros() {
	
	$("#btn-search").click(function (event) {
		event.preventDefault();

		var parametro=$("#search").val();

		if (parametro=="") {
			alert("Debes ingresar los parámetros de búsqueda.");
		}else{

			//location.href = "Listado_cotizacion_ctrl/busquedaRegistros";
			
			$.ajax({
				url: baseURL+"index.php/Cotizacion/Listado_cotizacion_ctrl/busquedaRegistros",
				method: "post",
				dataType:"json",
				data:{ "parametro":parametro},
				success: function (r) {
					actualizaTabla(r);
				},
				error: function(response){
					alert("Hubo un error al realizar la búsqueda");
				}
			});
			
		}
	});
	
}

function filtroBusqueda() {
	$("#filtrado").change(function() {
		var busqueda_slc,i,n,a,fun;
		var filtro = $("#filtrado").val();
		
		$("#busqueda_slc").remove();
		$("#fechas_busqueda").remove();

		if(filtro=="1"){
			fun = "traeClientes";
		}else if(filtro=="2"){
			fun = "traeAccounts";
		}else if(filtro=="3"){
			fun= "traeStatus";
		}


		if (filtro=="1" || filtro=="2" || filtro =="3") {

			$.ajax({
				url: baseURL+'index.php/Cotizacion/Listado_cotizacion_ctrl/'+fun,
				method: 'post',
				dataType: 'json',
				success: function(r) {
					$('<select class="form-control" name="busqueda_slc" id="busqueda_slc"></select>').insertAfter("#filtrado");
					busqueda_slc = $("#busqueda_slc");
					busqueda_slc.append("<option value=-1>Seleccione una opción</option>");
					for(i=0,n=r.length;i<n;i++){
						a = r[i];
						busqueda_slc.append('<option value='+a.id+'>'+a.nombre+'</option');
					}
				},
				error: function(r) {
					alert("Ha ocurrido un error al realizar la solicitud");
				}
			});

		}else if(filtro=="4"){


			var sel_fechas = '<div id="fechas_busqueda" style="margin-top: 10px; margin-bottom: 10px; "><div class="col-sm-6 col-md-6 col-lg-6 form-group"><label for="fecha_inicio">Fecha de inicio: </label><input type="text" id="fecha_inicio" class="form-control"/></div><div class="col-sm-6 col-md-6 col-lg-6 form-group"><label for="fecha_fin">Fecha de fin: </label><input type="text" id="fecha_fin" class="form-control"/></div></div>';

			$(sel_fechas).insertAfter("#filtrado");
			var dia,mes,anio, fecha = new Date();
			var fecha_inicio = $("#fecha_inicio");
			var fecha_fin = $("#fecha_fin");
			dia = fecha.getUTCDate();
			if(fecha.getUTCMonth()+1 < 10){ mes= "0"+(fecha.getUTCMonth()+1); }else{ mes= fecha.getUTCMonth()+1;}
			anio = fecha.getUTCFullYear();
			fecha = dia+"-"+mes+"-"+anio;
			fecha_inicio.val(fecha);
			fecha_fin.val(fecha);
			fecha_inicio.datepicker({dateFormat: "dd-mm-yy"});
			fecha_fin.datepicker({dateFormat: "dd-mm-yy"});

		}
	});
}

function realizarBusquedaPorFiltro(){

	$("#btn-search-by-filter").click(function(event){

		event.preventDefault();

		var filtro = $("#filtrado").val();

		if(filtro !="-1"){
			if(filtro=="1"||filtro=="2"||filtro=="3"){
				var busqueda_slc = $("#busqueda_slc").val();
				var fun;

				if(filtro=="1"){ fun = "buscaPorCliente";}else if(filtro=="2"){fun="buscaPorAccount";}else if(filtro=="3"){fun="buscaPorStatus";}

				if(busqueda_slc!="-1"){

					$.ajax({
						url: baseURL+'index.php/Cotizacion/Listado_cotizacion_ctrl/'+fun,
						method: 'post',
						dataType: 'json',
						data: {'id':busqueda_slc},
						success: function(r) {
							actualizaTabla(r);
						},
						error: function(r) {
							alert("Ocurrió un error al realizar la búsqueda.");
						}
					});

				}else{
					alert("Debes seleccionar una opción de búsqueda");
				}
			}else if(filtro=="4"){
				var fecha_inicio = $("#fecha_inicio").val();
				var fecha_fin = $("#fecha_fin").val();

				$.ajax({
					url: baseURL+'index.php/Cotizacion/Listado_cotizacion_ctrl/buscaPorFechaAlta',
					method: 'post',
					dataType: 'json',
					data: {'fecha_inicio':fecha_inicio, 'fecha_fin': fecha_fin},
					success: function(r) {
						actualizaTabla(r);
					},
					error: function(r) {
						alert("Ocurrió un error al realizar la búsqueda.");
					}
				});
			}else if(filtro=="5"||filtro=="6"){
				var fun,dia,mes,anio,fecha= new Date();

				dia = fecha.getUTCDate();
				if(fecha.getUTCMonth()+1 < 10){ mes= "0"+(fecha.getUTCMonth()+1); }else{ mes= fecha.getUTCMonth()+1;}
				anio = fecha.getUTCFullYear();
				fecha = anio+"-"+mes+"-"+dia;

				if(filtro=="5"){fun="buscaPorFacturaVigente";}else if(filtro=="6"){fun= "buscaPorFacturaVencida";}
				
				$.ajax({
					url: baseURL+'index.php/Cotizacion/Listado_cotizacion_ctrl/'+fun,
					method: 'post',
					dataType: 'json',
					data: {'fecha':fecha},
					success: function(r) {
						actualizaTabla(r);
					},
					error: function(r) {
						alert("Ocurrió un error al realizar la búsqueda.");
					}
				});

			}else if(filtro=="7"){
				$.ajax({
					url: baseURL+'index.php/Cotizacion/Listado_cotizacion_ctrl/traeTodo',
					method: 'post',
					dataType: 'json',
					success: function(r) {
						actualizaTabla(r);
					},
					error: function(r) {
						alert("Ocurrió un error al realizar la búsqueda.");
					}
				});
			}
		}else{
			alert("Debes seleccionar una opción para filtrar.");
		}
	});
}

function editarCotizacion() {
	$("#cuerpoTabla").delegate(".btn-edit","click",function(event){
		event.preventDefault();

		var idCotizacion = $(this).attr("data-id");

		location.href = baseURL+"index.php/Cotizacion/Editar_cotizacion_ctrl/traeCotizacion/"+idCotizacion;
	});
}

function aprobarCotizacion(){
	$("#cuerpoTabla").delegate(".btn-aprobar","click", function(event){
		event.preventDefault();

		var btn_aprobar = $(this);
		var idCotizacion = btn_aprobar.attr("data-id");

		$.ajax({
			url: baseURL+'index.php/Cotizacion/Listado_cotizacion_ctrl/aprobarCotizacion',
			method: 'post',
			dataType: 'text',
			data: {'idCotizacion':idCotizacion},
			success: function(r) {
				//alert(btn_aprobar.closest("tr").find(".status_cot").html()+" Cotizacion_id: "+btn_aprobar.attr("data-id"));
				btn_aprobar.closest("tr").find(".status_cot").html("Aprobada");
			},
			error: function(r) {
				alert("Ocurrió un error al realizar la aprobación.");
			}
		});
	});
}

function cancelarCotizacion(){
	$("#cuerpoTabla").delegate(".btn-cancelar","click",function(event){
		event.preventDefault();

		var btn_cancelar = $(this);
		var idCotizacion = btn_cancelar.attr("data-id");

		$.ajax({
			url: baseURL+'index.php/Cotizacion/Listado_cotizacion_ctrl/cancelarCotizacion',
			method: 'post',
			dataType: 'text',
			data: {'idCotizacion':idCotizacion},
			success: function(r) {
				//alert(btn_cancelar.closest("tr").find(".status_cot").html()+" Cotizacion_id: "+btn_cancelar.attr("data-id"));
				btn_cancelar.closest("tr").find(".status_cot").html("Cancelada");
			},
			error: function(r) {
				alert("Ocurrió un error al realizar la cancelación.");
			}
		});
	});
}

function duplicarCotizacion(){
	$("#cuerpoTabla").delegate(".btn-duplicar","click",function(event){
		event.preventDefault();

		var btn_duplicar= $(this);
		var idCotizacion = btn_duplicar.attr("data-id");

		$.ajax({
			url: baseURL+'index.php/Cotizacion/Listado_cotizacion_ctrl/duplicarCotizacion',
			method: 'post',
			dataType: 'text',
			data: {'idCotizacion':idCotizacion},
			success: function(r) {
				//alert(r);
				location.reload();
			},
			error: function(r) {
				alert("Ocurrió un error al realizar la duplicación.");
			}
		});
	});
}

function generaPDF(){
	$("#cuerpoTabla").delegate(".btn-PDF","click",function(event) {
		event.preventDefault();

		var btn_pdf = $(this);
		var idCotizacion = btn_pdf.attr("data-id");

		$.ajax({
			url: baseURL+'index.php/Cotizacion/Listado_cotizacion_ctrl/generaPDF',
			method: 'post',
			dataType: 'text',
			data: {'idCotizacion':idCotizacion},
			success: function(r) {
				alert("¡PDF Generado con éxito!");
				window.open(baseURL+'img/'+r);
			},
			error: function(r) {
				alert("Ocurrió un error al realizar la generación del PDF.");
			}
		});

	});
}

function actualizaTabla(r){
	var tabla = $("#cuerpoTabla"),lastRow,a;
	tabla.find(".contenidoTabla").remove();


	for (var i = 0, n=r.length; i < n; i++) {
		a=r[i];

		if(a.fecha_alta=='00-00-0000'){ a.fecha_alta = 'Indefinida';}
		if(a.fecha_inicio=='00-00-0000'){ a.fecha_inicio = 'Indefinida';}
		if(a.fecha_fin=='00-00-0000'){ a.fecha_fin = 'Indefinida';}

		tabla.append("<tr class='contenidoTabla'></tr>");

		lastRow = tabla.find("tr:last-child");

		lastRow.append("<td id='folio'>"+a.folio+"</td>");
		lastRow.append("<td id='nombreCliente'>"+a.nombre_cli+"</td>");
		lastRow.append("<td id='titulo'>"+a.titulo+"</td>");
		lastRow.append("<td id='contacto'>"+a.nombre_con+" "+a.apellido_con+"<br>"+a.correo+"</td>");
		lastRow.append("<td id='importe_total'>"+a.importe_total+"</td>");
		lastRow.append("<td id='fecha_alta'>"+a.fecha_alta+"</td>");
		lastRow.append("<td id='fecha_inicio'>"+a.fecha_inicio+"</td>");
		lastRow.append("<td id='fecha_fin'>"+a.fecha_fin+"</td>");
		lastRow.append("<td id='nombreAcc'>"+a.nombre_acc+"</td>");
		lastRow.append("<td class='status_cot' id='status'>"+a.clave_status+"</td>");
		lastRow.append('<td><button class="btn btn-primary btn-PDF" data-id="'+a.id+'"><span class="glyphicon glyphicon-new-window"></span></button></td>');
		lastRow.append('<td><button class="btn btn-warning btn-edit" id="btn-editar" data-id="'+a.id+'"><span class="glyphicon glyphicon-edit"></span></button></td>');
		lastRow.append('<td><button class="btn btn-success btn-aprobar" data-id="'+a.id+'"><span class="glyphicon glyphicon-ok"></span></button></td>');
		lastRow.append('<td><button class="btn btn-danger btn-cancelar" data-id="'+a.id+'"><span class="glyphicon glyphicon-remove"></span></button></td>');
		lastRow.append('<td><button class="btn btn-primary btn-duplicar" data-id="'+a.id+'"><span class="glyphicon glyphicon-duplicate"></span></button></td>');

		/*
		editarCotizacion();
		aprobarCotizacion();
		cancelarCotizacion();
		duplicarCotizacion();
		generaPDF();
		*/
	}
}


window.onload=function() {
	buscaRegistros();
	editarCotizacion();
	filtroBusqueda();
	realizarBusquedaPorFiltro();
	aprobarCotizacion();
	cancelarCotizacion();
	duplicarCotizacion();
	generaPDF();
	
}



