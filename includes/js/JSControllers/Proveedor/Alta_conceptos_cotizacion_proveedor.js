function retrieveRazonesSociales(idCliente){
	var k, n;
	var appendSection = $("#id-razon-social");
	appendSection.find("*").remove();


	$.ajax({
		url: baseURL+"index.php/Alta_conceptos_cotizacion_ctrl/getRazonesSociales/"+idCliente,
		method: 'post',
		dataType: 'json',
		success: function(response){
			appendSection.append("<option value='-1'>Ninguna</option>");
			for(k=0, n=response.length; k<n; k++){
				appendSection.append("<option value='"+response[k].id+"'>"+response[k].razonSocial+"</option>");
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function retrieveCotizaciones(idRazonSocial){
	var k, n;
	var appendSection = $("#id-cotizacion");
	appendSection.find("*").remove();

	$.ajax({
		url: baseURL+"index.php/Alta_conceptos_cotizacion_ctrl/getCotizaciones/"+idRazonSocial,
		method: 'post',
		dataType: 'json',
		success: function(response){
			appendSection.append("<option value='-1'>Ninguna</option>");
			for(k=0, n=response.length; k<n; k++){
				appendSection.append("<option value='"+response[k].id+"'>"+response[k].folio+"</option>");
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});	
}

function retrieveClasificaciones(sender){
	var idServicio = sender.val();
	var appendSection = sender.closest(".clone-section-concepto").first().find("#clasificacion-concepto");

	$.ajax({
		url: baseURL+"index.php/Alta_conceptos_cotizacion_ctrl/getClasificaciones",
		method: 'post',
		dataType: 'json',
		data: {'idServicio': idServicio},
		success: function(response){
			appendSection.find("option").remove();

			appendSection.append("<option value='-1'>Seleccione una opción</option>");
			for(k=0, n=response.length; k<n; k++){
				appendSection.append("<option value='"+response[k].id+"'>"+response[k].clave+"</option>");
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}		
	});
}

function revisarCamposObligatorios(){
	var message = "";
	var errores = 0;

	var folioCotizacion = $("#folio-cotizacion").val();
	var accountManager = $("#id_account_manager").val();
	var cerrador = $("#id_cerrador").val();

	var servicio = $("#append-section-concepto .servicio-concepto");
	var clasificacion = $("#append-section-concepto .clasificacion-concepto");

	var importe = $(".append-section-fecha-factura .importe-fecha-factura");

	if(folioCotizacion.trim() == ""){
		errores++;
		message = "El folio de la cotiación es un campo obligatorio";
	}else if(accountManager == "-1"){
		errores++;
		message = "Ingrese al account manager que llevará la cotización";
	}else if(cerrador == "-1"){
		errores++;
		message = "Ingrese al cerrador de la cotización";
	}

	servicio.each(function(index){
		if($(this).val() == "-1"){
			errores++;
			message = "Debe seleccionar el servicio correspondiente a cada concepto";
		}
	});

	clasificacion.each(function(index){
		if($(this).val() == "-1"){
			errores++;
			message = "Debe seleccionar la categoria correspondiente a cada concepto";
		}
	});

	importe.each(function(index){
		if($(this).val().trim() == ""){
			errores++;
			message = "Debe ingresar el importe correspondiente a cada fecha de facturación";
		}
	});

	if(errores > 0){
		alert(message);
		return false;
	}else return true;
}

$(function(){
	$("#id-cliente").change(function(event){
		event.preventDefault();

		retrieveRazonesSociales($(this).val());
	});

	$(".servicio-concepto").change(function(){
		retrieveClasificaciones($(this));
	});

	$("#btn-add-concepto").click(function(event){
		event.preventDefault();

		var appendSection = $("#append-section-concepto");
		var cloneSection = $("#clone-section-concepto").clone(true);

		cloneSection.attr("id", "clone-section-concepto-"+appendSection.find(".clone-section-concepto").size());
		cloneSection.show();

		cloneSection.find("#btn-add-fecha-factura").click(function(event){
			event.preventDefault();

			var sender = $(this);
			var parent_section = sender.closest(".clone-section-concepto").first();
			var append_section_fecha = parent_section.find("#append-section-fecha-factura");

			var clon = jCloneSection($("#clone-section-fecha-factura"), append_section_fecha);
			jInitDatepicker(clon.find("#fecha-factura"), clon.find("#fecha-factura-alt"), "dd/mm/yy", "yy-mm-dd");

			// Asociar evento de cambio de importe, para recalcular totales de concepto padre
			clon.find("#importe-fecha-factura").change(function(){
				var importes = append_section_fecha.find(".importe-fecha-factura");
				var importeSuma = 0;
				var totalSuma = 0;
				var padre = $(this).closest(".clone-section-concepto").first();
				var importeImp = padre.find("#importe-concepto");
				var totalImp = padre.find("#total-concepto");
				var iva = (parseFloat(padre.find("#iva").val()) / 100) + 1;
				var valor = 0;

				importes.each(function(i){
					valor = $(this).val();
					if(valor == "") valor = 0;
					else valor = parseFloat(valor);

					importeSuma += valor;
				});

				totalSuma = importeSuma * iva;
				importeImp.val(importeSuma.toFixed(2));
				totalImp.val(totalSuma.toFixed(2));
			});

			clon.find("#btn-destroy-fecha").click(function(){
				clon.remove();
			});
		});

		cloneSection.find("#iva, #importe-concepto").change(function(){
			var importe = cloneSection.find("#importe-concepto").val();
			var totalImp = cloneSection.find("#total-concepto");
			var iva = (parseFloat(cloneSection.find("#iva").val()) / 100) + 1;

			if(importe == "") importe = 0;
			else importe = parseFloat(importe);

			totalImp.val((importe*iva).toFixed(2));
		});

		cloneSection.find("#btn-destroy-concepto").click(function(){
			cloneSection.remove();
		});


		appendSection.append(cloneSection);
	});

	$("#id-cotizacion").change(function(){
		var cVal = $(this).val();

		if(cVal == -1){
			$("#folio-cotizacion").show();
		}else{
			$("#folio-cotizacion").hide();
		}
	});

	$("#btn-save-cotizacion").click(function(event){
		event.preventDefault();

		if(!revisarCamposObligatorios()) return false;

		var idCliente = $("#id-cliente").val();
		var folioCotizacion = $("#folio-cotizacion").val();
		var notaCotizacion  = $("#nota-cotizacion").val();
		var fechaJuntaArranque = $("#alt_fecha_junta_arranque").val();
		var fechaVenta = $("#alt_fecha_venta").val();
		var fechaInicioProyecto = $("#alt_fecha_inicio_proyecto").val();
		var fechaFinProyecto = $("#alt_fecha_fin_proyecto").val();
		var idCerrador = $("#id_cerrador").val();
		var accountManager = $("#id_account_manager").val();
		var tituloCotizacion = $("#titulo_cot").val();
		
		// Asociar los conceptos con sus fechas de facturación
		// array( array("concepto", array({FECHA_1}, ...)), ... )
		var conceptos = new Array();
		var sc_conceptos = $("#append-section-concepto .clone-section-concepto");
		var concepto, fechaFactura;
		var sc_fecha_factura;
		var current, currentFecha;
		var cConcepto;
		var i, k;

		sc_conceptos.each(function(index){
			current = $(this);

			concepto = {
				descripcion : current.find("#descripcion-concepto").val(),
				referencia : current.find("#referencia-concepto").val(),
				nota : current.find("#nota-concepto").val(),
				importe : current.find("#importe-concepto").val(),
				total : current.find("#total-concepto").val(),
				fechasFactura : new Array(),
				servicio: current.find("#servicio-concepto").val(),
				clasificacion: current.find("#clasificacion-concepto").val(),
				iva: current.find("#iva").val()
			};

			sc_fecha_factura = current.find("#append-section-fecha-factura .clone-section-fecha-factura");

			sc_fecha_factura.each(function(index){
				currentFecha = $(this);
				fecha_factura = {
					importe : currentFecha.find("#importe-fecha-factura").val(),
					referencia : currentFecha.find("#referencia-fecha-factura").val(),
					nota : currentFecha.find("#nota-fecha-factura").val(),
					fecha : currentFecha.find("input[name='fecha-factura-alt[]']").val()
				};

				concepto.fechasFactura.push(fecha_factura);
			});

			conceptos.push(concepto);
		});

		$.ajax({
			url: baseURL+"index.php/Alta_conceptos_cotizacion_ctrl/guardarCotizacion",
			method: 'post',
			dataType: 'text',
			data: {"conceptos":JSON.stringify(conceptos),"idCliente":idCliente,"folioCotizacion":folioCotizacion,"notaCotizacion":notaCotizacion,"fechaJuntaArranque":fechaJuntaArranque,"fechaVenta":fechaVenta,"fechaInicioProyecto":fechaInicioProyecto,"fechaFinProyecto":fechaFinProyecto,"idCerrador":idCerrador,"accountManager":accountManager,"tituloCotizacion":tituloCotizacion},
			success: function(response){
				alert("Operación realizada con éxito");
				//window.location.replace(baseURL+"index.php/Panel_control_ctrl");
			},
			error: function(){
				alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			}	
		});
	});

	initDatepicker("#fecha_junta_arranque", "#alt_fecha_junta_arranque", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fecha_venta", "#alt_fecha_venta", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fecha_inicio_proyecto", "#alt_fecha_inicio_proyecto", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fecha_fin_proyecto", "#alt_fecha_fin_proyecto", "dd/mm/yy", "yy-mm-dd");
});