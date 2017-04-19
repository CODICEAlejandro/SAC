var nombreArchivo = "NULL";

function subirArchivoPorAJAX(){
	//Comprobar si existe archivo seleccionado
	var archivo = $("#archivo-adjunto")[0].files[0];

	if($("#archivo-adjunto").val().trim() == "")
		return true;

	var fileName = archivo.name;
	var extension = fileName.substring(fileName.lastIndexOf(".")+1);
	var fileSize = archivo.size;
	var fileType = archivo.type;

	if(fileSize > 3145728){
		alert("El tamaño máximo del archivo adjunto es 3MB");
		return false;
	}else{
		//Subir archivo
		var fd = new FormData($("#form-archivo-adjunto")[0]);
		//Hace un append del formulario puro en DOM

		$.ajax({
			url: baseURL+"index.php/Cotizacion/Crear_cotizacion_ctrl/subirArchivoAdjunto",
			method: 'post',
			dataType: 'text',
			data: fd,
			cache: false,
			contentType: false,
			processData: false,
			async: false,
			beforeSend: function(){
				$("#alert-subiendo-archivo").remove();
				$("#archivo-adjunto").after("<div id='alert-subiendo-archivo'>Subiendo archivo</div>");
			},
			success: function(r){
				if(r!="ERROR"){
					$("#alert-subiendo-archivo").html("Subido");
					nombreArchivo = r;
				}else{
					$("#alert-subiendo-archivo").html("ERROR");
				}
			},
			error: function(){
				alert("Ha ocurrido un error subiendo el archivo adjunto");
			}
		});
	}
}

function guardarCotizacion(){
	var cliente = $("#id-cliente").val();
	var contacto = $("#id-contacto").val();
	var tituloCotizacion = $("#titulo-cotizacion").val();
	var formaDePago = $("#id-forma-de-pago").val();
	var objetivoCotizacion = $("#objetivo-cotizacion").val();
	var introduccionCotizacion = $("#introduccion-cotizacion").val();
	var notasCotizacion = $("#nota-cotizacion").val();

	//Empaquetar los alcances
	var alcances = new Array();
	var objAlcances = $("#append-section-alcance .clone-section-alcance");
	var tmpAlcance, tmpDescripcion, objDescripciones;
	var fechaInicioServicioCotizacion = null, fechaFinServicioCotizacion = null;
	var k, n, a;
	var resultFechaString, resultFechaDate, tmpFechaString, tmpFechaDate;
	var resultFechaStringCotizacion;
	var arrResult, arrTmp;

	resultFechaStringCotizacion = null;

	objAlcances.each(function(index){
		var sender = $(this);
		var cArrDate, cDate, arrDate, tmpDate, minDate;

		tmpAlcance = {
			titulo: sender.find("#titulo-alcance").val(),
			orden: sender.find("#orden-alcance").val(),
			servicio: sender.find("#id-servicio-alcance").val(),
			clasificacion: sender.find("#id-servicio-alcance").val(),
			descripciones: new Array(),
			requerimientos: sender.find("#requerimientos-alcance").val(),
			entregables: sender.find("#entregables-alcance").val(),
			fechaInicioServicio: sender.find(".fecha-inicio-servicio-alt").first().val()
		};

		//Encontrar la fecha mínima de todos los alcances para asignarla a la fecha de inicio de servicio de la cotización
		if(!resultFechaStringCotizacion){
			resultFechaStringCotizacion = tmpAlcance.fechaInicioServicio;
		}else{
			arrResult = resultFechaStringCotizacion.split("-");	//Forma: yyyy-mm-dd
			resultFechaDate = new Date(arrResult[0], parseInt(arrResult[1])-1, arrResult[2]);

			arrTmp = tmpAlcance.fechaInicioServicio.split("-");
			tmpFechaDate = new Date(arrTmp[0], parseInt(arrTmp[1])-1, arrTmp[2]);

			if(tmpFechaDate.getTime() < resultFechaDate.getTime()){
				resultFechaStringCotizacion = tmpAlcance.fechaInicioServicio;
			}
		}
		fechaInicioServicioCotizacion = resultFechaStringCotizacion;

		//Empaquetar las descripciones del alcance actual
		objDescripciones = sender.find("#append-section-descripcion .clone-section-descripcion");
		objDescripciones.each(function(index){
			var sender = $(this);
			tmpDescripcion = {
				titulo: sender.find("#titulo-descripcion").val(),
				descripcion: sender.find("#contenido-descripcion").val()
			}

			tmpAlcance.descripciones.push(tmpDescripcion);
		});

		//Empaquetar los datos acorde a si es pago fijo o recurrente
		//** Pagos recurrentes
		if(formaDePago == 1){
			tmpAlcance.periodicidad = sender.find("#bloque-pagos-recurrentes #id-periodicidad").val();
			tmpAlcance.numeroParcialidades = sender.find("#bloque-pagos-recurrentes #numero-parcialidades").val();
			tmpAlcance.montoParcialidad = sender.find("#bloque-pagos-recurrentes #monto-parcialidad").val();
			//El cálculo de la fecha de término se realiza con base en el multilicador del catálogo y se hace en el controlador
		}else if(formaDePago == 2){
		//** Pagos fijos
			tmpAlcance.precioTotal = sender.find("#bloque-pagos-fijos #precio-total").val();
			tmpAlcance.porcentajeAnticipo = sender.find("#bloque-pagos-fijos #porcentaje-anticipo").val();
			tmpAlcance.montoAnticipo = sender.find("#bloque-pagos-fijos #monto-anticipo").val();

			//Empaquetar parcialidades
			var parcialidades = new Array();
			var parcialidadesObj = sender.find("#append-section-parcialidad .clone-section-parcialidad");
			var tmpParcialidad;
			var fechaFinServicioAlcance = null;

			resultFechaString = null;

			parcialidadesObj.each(function(index){
				var sender = $(this);
				tmpParcialidad = {
					concepto: sender.find("#concepto-parcialidad").val(),
					fecha: sender.find(".fecha-parcialidad-alt").first().val(),
					porcentaje: sender.find("#porcentaje-parcialidad").val(),
					monto: sender.find("#monto-parcialidad").val()
				}

				//Calcular la fecha del fin de servicio del alcance (max(parcialidad.fecha))
				if(!resultFechaString){
					resultFechaString = tmpParcialidad.fecha;
				}else{
					arrTmp = tmpParcialidad.fecha.split("-");
					tmpFechaDate = new Date(arrTmp[0], parseInt(arrTmp[1])-1, arrTmp[2]);

					arrResult = resultFechaString.split("-");
					resultFechaDate = new Date(arrResult[0], parseInt(arrResult[1])-1, arrResult[2]);
					
					if(tmpFechaDate.getTime() > resultFechaDate.getTime()){
						resultFechaString = tmpParcialidad.fecha;
					}
				}

				fechaFinServicioAlcance = resultFechaString;
				parcialidades.push(tmpParcialidad);
			});

			tmpAlcance.parcialidades = parcialidades;

			if(fechaFinServicioAlcance){
				tmpAlcance.fechaFinServicio = fechaFinServicioAlcance;
			}
			else{
				tmpAlcance.fechaFinServicio = tmpAlcance.fechaInicioServicio;
				fechaFinServicioAlcance = tmpAlcance.fechaInicioServicio;
			}

			// **** Falta revisar que sucede con la fecha de fin de servicio del alcance para cuando éste no tiene parcialidades
			// **** Esto para pagos fijos
			// **** o ¿Es forzoso que haya por lo menos una parcialidad más el anticipo?

			// Calcular la fecha fin máxima de los alcances para obtener la fecha de fin de servio de la cotización
			// Solo es posible calcular para cuando la forma de pago es pagos fijos, pues sí contamos con la fecha final
			if(!fechaFinServicioCotizacion){
				fechaFinServicioCotizacion = fechaFinServicioAlcance;
			}else{
				resultFechaString = fechaFinServicioCotizacion;
				arrResult = resultFechaString.split("-");
				resultFechaDate = new Date(arrResult[0], parseInt(arrResult[1])-1, arrResult[2]);
	
				arrTmp = fechaFinServicioAlcance.split("-");
				tmpFechaDate = new Date(arrTmp[0], parseInt(arrTmp[1])-1, arrTmp[2]);

				if(tmpFechaDate.getTime() > resultFechaDate.getTime()){
					fechaFinServicioCotizacion = fechaFinServicioAlcance;
				}
			}
		}

		alcances.push(tmpAlcance);
	});

	var cotizacion = {
		cliente: cliente,
		contacto: contacto,
		titulo: tituloCotizacion,
		formaDePago: formaDePago,
		objetivo: objetivoCotizacion,
		introduccion: introduccionCotizacion,
		notas: notasCotizacion,
		alcances: alcances,
		nombreArchivo: nombreArchivo,
		fechaInicioServicio: fechaInicioServicioCotizacion,
		fechaFinServicio: fechaFinServicioCotizacion
	}

	$.ajax({
		url: baseURL+'index.php/Cotizacion/Crear_cotizacion_ctrl/guardarCotizacion',
		data: {'cotizacion': cotizacion},
		dataType: 'json',
		method: 'post',
		async: false,
		success: function(r){
			alert("Cotización guardada con éxito.");
		},
		error: function(){
			alert("Ha ocurrido un error al guardar. Intente de nuevo, por favor.");
		}
	});

}

function revisarCampos(){
	var formaDePago = parseInt($("#id-forma-de-pago").val());
	var result = true; //Loops results

	//Revisión de generales
	if($("#id-cliente").val() == "-1"){
		alert("Indique el cliente al que le pertenece la cotización");
		return false;
	}else if($("#id-contacto").val() == "-1"){
		alert("Indique un contacto para el cliente seleccionado");
		return false;
	}else if($("#titulo-cotizacion").val().trim() == ""){
		alert("Especifique el título de la cotización");
		return false;
	}else if($("#id-forma-de-pago").val() == "-1"){
		alert("Indique la forma de pago");
		return false;
	}else if($("#objetivo-cotizacion").val().trim() == ""){
		alert("Esriba el objetivo de la cotización");
		return false;
	}else if($("#introduccion-cotizacion").val().trim() == ""){
		alert("Indique la introducción de la cotización");
		return false;
	}else if($("#append-section-alcance .clone-section-alcance").size() == 0){
		if(!confirm("Está a punto de guardar una cotización sin alcances. ¿Desea continuar?")){
			return false;
		}
	}

	//Revisión de opcionales
	//** Pagos recurrentes
	if(formaDePago == 1){
		if($(".bloque-pagos-recurrentes #id-periodicidad").val() == "-1"){
			alert("Falta especificar la periodicidad de la cotización");
			return false;
		}else if($(".bloque-pagos-recurrentes #numero-parcialidades").val() == ""){
			alert("Indique un valor válido para el número de parcialidades");
			return false;
		}else if($(".bloque-pagos-recurrentes #monto-parcialidad").val() == ""){
			alert("Indique un valor válido para el monto de la parcialidad");
			return false;
		}
	}else if(formaDePago == 2){
	//** Pagos fijos
		if($(".bloque-pagos-fijos #precio-total").val() == ""){
			alert("Indique un valor válido para el precio total de la cotización");
			return false;
		}else if($(".bloque-pagos-fijos #porcentaje-anticipo").val() == ""){
			alert("Indique un valor válido para el precio total de la cotización");
			return false;
		}else if($(".bloque-pagos-fijos #monto-anticipo").val() == ""){
			alert("Indique un valor válido para el precio total de la cotización");
			return false;
		}
	}

	//Revisión de campos de alcances y descripciones
	$("#append-section-alcance .clone-section-alcance").each(function(index){
		var sender = $(this);

		if(!result) return result;

		if(sender.find("#titulo-alcance").val().trim() == ""){
			alert("Falta el título de algún alcance");
			result = false;
		}else if(sender.find("#id-servicio-alcance").val() == "-1"){
			alert("Seleccione el servicio de todos los alcances");
			result = false;
		}else if(sender.find("#id-clasificacion-alcance").val() == "-1"){
			alert("Seleccione la clasificación de todos los alcances");
			result = false;
		}else if(sender.find("#requerimientos-alcance").val().trim() == ""){
			alert("Indique los requerimientos de todos los alcances");
			result = false;
		}else if(sender.find("#entregables-alcance").val().trim() == ""){
			alert("Indique los entregables de todos los alcance");
			result = false;
		}

		sender.find("#append-section-descripcion").find(".clone-section-descripcion").each(function(index){
			var descSender = $(this);

			if(!result) return result;

			if(descSender.find("#titulo-descripcion").val().trim() == ""){
				alert("Indique el título de todas las descripciones");
				result = false;
			}else if(descSender.find("#contenido-descripcion").val().trim() == ""){
				alert("Indique la descripción de cada descripción");
				result = false;
			}
		});
	});


	$("#append-section-parcialidad .clone-section-parcialidad").each(function(index){
		var sender = $(this);

		if(!result) return result;

		if(sender.find("#concepto-parcialidad").val().trim() == ""){
			alert("Indique el concepto de cada parcialidad");
			result = false;
		}else if(sender.find("#porcentaje-parcialidad").val() == ""){
			alert("Indique un porcentaje válido para cada parcialidad");
			result = false;
		}else if(sender.find("#monto-parcialidad").val() == ""){
			alert("Indique un monto válido para cada parcialidad");
			result = false;
		}
	});

	return result;
}

function cargarContactos(idCliente){
	$.ajax({
		method: 'post',
		data: {'idCliente': idCliente},
		dataType: 'json',
		url: baseURL+'index.php/Cotizacion/Crear_cotizacion_ctrl/traerContactos',
		success: function(response){
			var select = $("#id-contacto");
			var k, n = response.length;
			var text;

			select.find("*").remove();
			select.append("<option value='-1'>Seleccione una opción</option>");

			for(k=0; k<n; k++){
				text = "<option value='"+response[k].id+"'>";
				text += response[k].nombre+" "+response[k].apellido+" ("+response[k].tipo+") ";
				text += "</option>";

				select.append(text);
			}
		}
	});
}

function agregarAlcance(){
	var cloneSection = $("#clone-sections #clone-section-alcance").clone(true);
	var appendSection = $("#append-section-alcance");

	//Crear el campo de fecha
	jInitDatepicker(cloneSection.find("#fecha-inicio-servicio"), cloneSection.find("#fecha-inicio-servicio-alt"),"dd/mm/yy","yy-mm-dd");

	cloneSection.removeAttr("id");
	appendSection.append(cloneSection);
}

function crearOrdenAlcances(){
	$("#append-section-alcance .clone-section-alcance").each(function(index){
		$(this).find("#orden-alcance").val(index+1);
	});
}

function minusAlcance(padre){
	var minusSection = padre.find("#minus-section");
	var status = minusSection.data("visible");

	if(status==1){
		minusSection.data("visible", 0);
		minusSection.hide();
	}else{
		minusSection.data("visible", 1);
		minusSection.show();
	}
}

function deleteAlcance(padre){
	if(confirm("¿Está seguro que desea eliminar este alcance?")){
		padre.remove();
		crearOrdenAlcances();
	}
}

function subirAlcance(padre){
	var prevSibbling = padre.prev(".clone-section-alcance");

	if(!jQuery.isEmptyObject(prevSibbling)){
		prevSibbling.before(padre);
		crearOrdenAlcances();
	};
}

function bajarAlcance(padre){
	var nextSibbling = padre.next(".clone-section-alcance");

	if(!jQuery.isEmptyObject(nextSibbling)){
		nextSibbling.after(padre);
		crearOrdenAlcances();
	}
}

function agregarDescripcion(padre){
	var cloneSection = $("#clone-section-descripcion").clone(true);
	var appendSection = padre.find("#append-section-descripcion");

	cloneSection.removeAttr("id");
	appendSection.append(cloneSection);
}

function minusDescripcion(padre){
	var minusSection = padre.find("#minus-section");
	var status = minusSection.data("visible");

	if(status==1){
		minusSection.data("visible", 0);
		minusSection.hide();
	}else{
		minusSection.data("visible", 1);
		minusSection.show();
	}
}

function deleteDescripcion(padre){
	if(confirm("¿Está seguro que desea eliminar esta descripción?")){
		padre.remove();
	}
}

function deleteParcialidad(padre){
	if(confirm("¿Está seguro que desea eliminar esta parcialidad?")){
		padre.remove();
	}
}

function agregarParcialidad(padre){
	var cloneSection = $("#clone-section-parcialidad").clone(true);
	var appendSection = padre.find("#append-section-parcialidad");

	//Crear el campo de fecha
	jInitDatepicker(cloneSection.find("#fecha-parcialidad"), cloneSection.find("#fecha-parcialidad-alt"),"dd/mm/yy","yy-mm-dd");

	cloneSection.removeAttr("id");
	appendSection.append(cloneSection);
}


$(function(){
	$("#id-cliente").change(function(){
		var idCliente = $(this).val();
		cargarContactos(idCliente);
	});

	$("#btn-agregar-alcance").click(function(event){
		event.preventDefault();
		agregarAlcance();
		crearOrdenAlcances();
	});

	$("#btn-minus-alcance").click(function(event){
		event.preventDefault();
		var padre = $(this).closest(".clone-section-alcance");
		minusAlcance(padre);
	});

	$("#btn-delete-alcance").click(function(event){
		event.preventDefault();
		var padre = $(this).closest(".clone-section-alcance");
		deleteAlcance(padre);
	});

	$("#btn-up-alcance").click(function(event){
		event.preventDefault();
		var padre = $(this).closest(".clone-section-alcance");
		subirAlcance(padre);		
	});

	$("#btn-down-alcance").click(function(event){
		event.preventDefault();
		var padre = $(this).closest(".clone-section-alcance");
		bajarAlcance(padre);		
	});

	$("#btn-agregar-descripcion").click(function(event){
		event.preventDefault();
		var padre = $(this).closest(".clone-section-alcance");
		agregarDescripcion(padre);		
	});

	$("#btn-minus-descripcion").click(function(event){
		event.preventDefault();
		var padre = $(this).closest(".clone-section-descripcion");
		minusDescripcion(padre);
	});

	$("#btn-delete-descripcion").click(function(event){
		event.preventDefault();
		var padre = $(this).closest(".clone-section-descripcion");
		deleteDescripcion(padre);
	});

	$("#id-forma-de-pago").change(function(){
		var value = $(this).val();

		if(value == 2){
			//Pagos fijos
			$(".bloque-pagos-recurrentes").hide();
			$(".bloque-pagos-fijos").show();
		}else if(value == 1){
			//Pagos recurrentes
			$(".bloque-pagos-recurrentes").show();
			$(".bloque-pagos-fijos").hide();
		}else{
			$(".bloque-pagos-recurrentes").hide();
			$(".bloque-pagos-fijos").hide();
		}
	});

	$(".btn-delete-parcialidad").click(function(event){
		event.preventDefault();
		var padre = $(this).closest(".clone-section-parcialidad");
		deleteParcialidad(padre);	
	});

	$(".btn-agregar-parcialidad").click(function(event){
		event.preventDefault();
		var padre = $(this).closest("#bloque-pagos-fijos");

		agregarParcialidad(padre);
	});

	// Cálculos automáticos
	$("#porcentaje-anticipo, #precio-total").change(function(){
		var porcentaje = $("#porcentaje-anticipo");
		var monto = $("#monto-anticipo");
		var precio = $("#precio-total");

		var pre = parseFloat(precio.val());
		var p = parseFloat(porcentaje.val());

		if(p<0 || p>100){
			alert("El porcentaje debe estar en el rango de 0 a 100");
			porcentaje.val("0");
			monto.val("0");
		}else if(!$.isNumeric(pre) || !$.isNumeric(p)){
			alert("El monto y porcentaje deben ser valores numéricos");
			porcentaje.val("0");
			monto.val("0");
		}else{
			monto.val(pre*(p/100));
		}

		$(".porcentaje-parcialidad").change();
	});

	$("#monto-anticipo").change(function(){
		var val = $(this).val();

		if(!$.isNumeric(val)){
			alert("El monto debe ser un valor numérico");
			$(this).val("0");
		}
	});

	$("#porcentaje-parcialidad").change(function(){
		var padre = $(this).closest(".clone-section-parcialidad");
		var precioTotal_v = $("#precio-total").val();
		var porcentaje_v = $(this).val();
		var montoParcialidad = padre.find("#monto-parcialidad");

		montoParcialidad.val(precioTotal_v*(porcentaje_v/100.0));
	});	

	$("#id-servicio-alcance").change(function(){
		var padre = $(this).closest(".clone-section-alcance");
		var appendSection = padre.find("#id-clasificacion-alcance");
		var idServicio = $(this).val();
		appendSection.find("*").remove();

		$.ajax({
			url: baseURL+'index.php/Cotizacion/Crear_cotizacion_ctrl/traerClasificaciones',
			method: 'post',
			data: {'idServicio': idServicio},
			dataType: 'json',
			success: function(response){
				var k, n=response.length;
				appendSection.append("<option value='-1'>Seleccione una opción</option>");
				for(k=0; k<n; k++){
					appendSection.append("<option value='"+response[k].id+"'>"+response[k].clave+"</option>");
				}
			},
			error: function(){
				alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			}
		});
	});

	$("#btn-guardar-cotizacion").click(function(event){
		event.preventDefault();
		if(revisarCampos()){
			subirArchivoPorAJAX();
			guardarCotizacion();
		}
	});

	$(".bloque-pagos-recurrentes").hide();
	$(".bloque-pagos-fijos").hide();
});