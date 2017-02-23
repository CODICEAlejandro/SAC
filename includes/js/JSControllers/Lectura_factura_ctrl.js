function retrieveRazonesSociales(idCliente){
	var k, n;
	var appendSection = $("#razonSocialAsociada");
	appendSection.find("*").remove();

	$.ajax({
		url: baseURL+"index.php/Lectura_factura_ctrl/getRazonesSociales/"+idCliente,
		method: 'post',
		dataType: 'json',
		success: function(response){
			var razonSugerida = $("#razonSocialSugerida").html();
			var appendedOption;
			var flag = 0;

			appendSection.append("<option value='-1'>Ninguna</option>");
			for(k=0, n=response.length; k<n; k++){
				appendedOption = "<option value='"+response[k].id+"' ";

				if(flag != 1)
					if( (response[k].razonSocial.indexOf(razonSugerida) >= 0) || (razonSugerida.indexOf(response[k].razonSocial)>=0) ){
						appendedOption += " selected ";
						flag = 1;
					}

				appendedOption += ">"+response[k].razonSocial+"</option>"

				appendSection.append(appendedOption);
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function retrieveFechasFactura(idCliente){
	// Appens Section en este caso apunta al select donde se hacen
	// los matches correspondientes.
	var appendSection = $('#conceptos-tbl tbody select.idMatched');
	var fecha_desde = $("#fecha_desde_alt").val();
	var fecha_hasta = $("#fecha_hasta_alt").val();

	$.ajax({
		url: baseURL+'index.php/Lectura_factura_ctrl/getFechasFacturacion/'+idCliente,
		method: 'post',
		data: {'idCliente': idCliente, 'fecha_desde': fecha_desde, 'fecha_hasta': fecha_hasta},
		dataType: 'json',
		success: function(response){
			var k, n;
			var appendedDescription;

			// Limpieza de los select
			appendSection.find("*").remove();

			appendSection.append('<option value="-1">Ninguno</option>');

			for(k=0, n=response.length; k<n; k++){
				appendedDescription =  '<option value="'+response[k].idFechaFactura+'">';
				appendedDescription += response[k].folioCotizacion+' - ';
				appendedDescription += response[k].referenciaFecha+' - ';
				appendedDescription += response[k].fechaFactura;
				appendedDescription += '</option>';

				appendSection.append(appendedDescription);
			}			
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function isFill(){
	//Campos obligatorios
	var idCotizacion = parseInt( $("#cotizacionAsociada").val() );
	var folio = $("#folioFactura").val();
	var estadoFactura = parseInt( $("#estadoFactura").val() );

	var matchedElements = $(".idMatched");

	var flag = true;

	if(
		idCotizacion==-1 
		|| folio.trim()==""
		|| estadoFactura==-1
	)
		flag = false;

	if(flag){
		matchedElements.each(function(i){
			var sender = $(this);
			var value = parseInt( sender.val() );

			if( flag ){
				if(value == -1) flag = false;
			}
		});
	}

	$("#btn-guardar-factura").prop("disabled", !flag);
}

function addMatchedSelect(sender){
	var parentRow = sender.closest("tr");

	var cloneSection = parentRow.find(".clone-match-col").first().clone(true);
	var appendSection = parentRow.find("#append-section-matchCol");

	var importe = cloneSection.find("span#importeFechaFactura");
	var nota = cloneSection.find("span#notaFechaFactura");
	var servicio = cloneSection.find("span#servicioConcepto");


	cloneSection.attr("id", "clone-match-col"+( $(".clone-match-col").size() )+(new Date()).getTime() );
	appendSection.append(cloneSection);

	//Limpiar campos
	importe.html("");
	nota.html("");
	servicio.html("");
}

function deleteMatch(sender){
	var parentRow = sender.closest("tr");
	var currentMatches = parentRow.find("#append-section-matchCol").find(".clone-match-col").size();
	if(currentMatches > 1){
		sender.closest(".clone-match-col").remove();
	}else alert("Por lo menos debe existir una fecha de factura asociada para cada concepto");
}

$(function(){
	initDatepicker("#fechaPago", "#fechaPagoAlt", 'dd/mm/yy', 'yy-mm-dd');
	initDatepicker("#fechaCancelacion", "#fechaCancelacionAlt", 'dd/mm/yy', 'yy-mm-dd');
	initDatepicker("#fecha_desde", "#fecha_desde_alt", 'dd/mm/yy', 'yy-mm-dd');
	initDatepicker("#fecha_hasta", "#fecha_hasta_alt", 'dd/mm/yy', 'yy-mm-dd');

	$(".btn-delete-match").click(function(event){
		event.preventDefault();
		deleteMatch($(this));
	});

	$(".btn-add-matched-select").click(function(event){
		event.preventDefault();
		addMatchedSelect($(this));
	});

	$("#folioFactura").change(function(){
		factura.folio = $(this).val();
		isFill();
	});

	$("#estadoFactura").change(function(){
		factura.idEstadoFactura = $(this).val();
		isFill();
	});

	$("#fechaPago").change(function(){
		factura.fechaPago = $("#fechaPagoAlt").val();
	});

	$("#fechaCancelacion").change(function(){
		factura.fechaCancelacion = $("#fechaCancelacionAlt").val();
	});

	$("#ordenCompra").change(function(){
		factura.ordenCompra = $(this).val();
	});

	$("#clienteAsociado").change(function(){
		retrieveRazonesSociales($(this).val());
		retrieveFechasFactura($(this).val());
	});

	$(".idMatched").change(function(){
		var sender = $(this);
		var idSel = sender.val();
		var currentRow = sender.closest(".clone-match-col").first();

		//ProxID es el índice en el arreglo de conceptos asociados con la factura
		//Dicho dato no es almacenado en la BD, solo posiciona cada elemento en dicho conjunto
		var proxID = parseInt(sender.closest("tr").find("#id").html());

		//Elements apunta a cada uno de las fechas de facturación en el set del renglón actual
		var elements = sender.closest("#matchCol").find(".clone-match-col");
		
		var importeFechaFactura;
		var k, n;

		// Vaciar arreglo de matches actuales para el concepto en cuestión
		// para evitar duplicación de claves
		factura.conceptos[proxID].idMatched = new Array();

		// Asociar las fechas de factura del cada renglón con el concepto 
		// correspondiente de la factura
		elements.each(function(index){
			importeFechaFactura = $(this).find("#importeFechaFactura").html();
			factura.conceptos[proxID].idMatched.push([$(this).find("#idMatched").val(), importeFechaFactura]);
		});

		$.ajax({
			url: baseURL+'index.php/Lectura_factura_ctrl/getFechaFacturacion/'+idSel,
			method: 'post',
			dataType: 'json',
			success: function(response){
				var importe = currentRow.find("span#importeFechaFactura");
				var nota = currentRow.find("span#notaFechaFactura");
				var servicio = currentRow.find("span#servicioConcepto");

				importe.html(response.importeFecha);
				nota.html(response.notaFecha);
				servicio.html(response.claveServicio);

				// Comprobar si el monto de la fecha factura seleccionada difiere en más o menos de 1
				// var toleranciaSuperior = 1;
				// var toleracionInferior = 1;
				// var comparativo = parseFloat(sender.closest("tr").find("#importeCol").html());

				// if( response.importeFecha>(comparativo+toleranciaSuperior) && response.importeFecha<(comparativo-toleranciaInferior) ){
				// 	alert("Los importes difieren es más de "+toleranciaSuperior+" o en menos de "+toleracionInferior);
				// }
			},
			error: function(){
				alert("Error. Intente de nuevo, por favor.");				
			}
		});		

		isFill();
	});

	$(".notasTextarea").change(function(){
		var sender = $(this);
		var proxID = parseInt(sender.closest("tr").find("#id").html());

		factura.conceptos[proxID].nota = sender.val();
	});

	$("#notasFactura").change(function(){
		factura.nota = $(this).val();
	});

	$("#estaCancelada").click(function(){
		if($(this).is(":checked")){
			factura.estaCancelada = 1;
		}
		else
			factura.estaCancelada = 0;
	});

	$("#btn-guardar-factura").click(function(event){
		event.preventDefault();

		$.ajax({
			url: baseURL+'index.php/Lectura_factura_ctrl/guardarFactura',
			method: 'post',
			data: {"mainData" : JSON.stringify(factura)},
			beforeSend: function(){
			},
			success: function(response){
				alert("Factura generada");
				window.location.replace(baseURL+"index.php/Panel_control_ctrl");
			},
			error: function(){
				alert("Error. Intente de nuevo, por favor.");
			}
		});
	});

	$("#ivaFactura").attr("readonly", true);
	$("#importeFactura").attr("readonly", true);

	factura.iva = $("#ivaFactura").val();
	factura.importe = $("#importeFactura").val();
	$("#estadoFactura").val(24);
	$("#btn-guardar-factura").prop("disabled", true);
	$("#fechaPago").change();
	$("#fechaCancelacion").change();
	$("#estadoFactura").change();
});