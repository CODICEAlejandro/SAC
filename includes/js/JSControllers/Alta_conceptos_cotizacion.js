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
				clasificacion: current.find("#clasificacion-concepto").val()
			};

			sc_fecha_factura = current.find("#append-section-fecha-factura .clone-section-fecha-factura");

			sc_fecha_factura.each(function(index){
				currentFecha = $(this);
				fecha_factura = {
					importe : currentFecha.find("#importe-fecha-factura").val(),
					referencia : currentFecha.find("#referencia-fecha-factura").val(),
					nota : currentFecha.find("#nota-fecha-factura").val(),
					fecha : currentFecha.find("#fecha-factura-alt").val()
				};

				concepto.fechasFactura.push(fecha_factura);
			});

			conceptos.push(concepto);
		});

		$.ajax({
			url: baseURL+"index.php/Alta_conceptos_cotizacion_ctrl/guardarCotizacion",
			method: 'post',
			dataType: 'json',
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

	$(".valor-unitario-concepto, .cantidad-concepto, .iva").change(function(){
		var sender = $(this);
		var parent_section = sender.closest(".clone-section-concepto").first();

		var valor_unitario = parseFloat(parent_section.find("#valor-unitario-concepto").val());
		var cantidad = parseInt(parent_section.find("#cantidad-concepto").val());
		var importeObj = parent_section.find("#importe-concepto");
		var totalObj = parent_section.find("#total-concepto");
		var iva = (parseFloat(parent_section.find("#iva").val()) / 100) + 1;

		var importe, total;

		importe = valor_unitario * cantidad;
		total = importe * iva;

		if(isNaN(importe) || isNaN(total)){
			importe = "";
			total = "";
		}else{
			importe = importe.toFixed(2);
			total = total.toFixed(2);
		}

		importeObj.val(importe);
		totalObj.val(total);
	});


	initDatepicker("#fecha_junta_arranque", "#alt_fecha_junta_arranque", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fecha_venta", "#alt_fecha_venta", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fecha_inicio_proyecto", "#alt_fecha_inicio_proyecto", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fecha_fin_proyecto", "#alt_fecha_fin_proyecto", "dd/mm/yy", "yy-mm-dd");
});