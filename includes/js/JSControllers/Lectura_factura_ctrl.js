function retrieveRazonesSociales(idCliente){
	var k, n;
	var appendSection = $("#razonSocialAsociada");
	appendSection.find("*").remove();

	$.ajax({
		url: baseURL+"index.php/Lectura_factura_ctrl/getRazonesSociales/"+idCliente,
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
	var appendSection = $("#cotizacionAsociada");
	appendSection.find("*").remove();

	$.ajax({
		url: baseURL+"index.php/Lectura_factura_ctrl/getCotizaciones/"+idRazonSocial,
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

function retrieveConceptosCotizacion(idCotizacion){
	var k, m, n;
	var appendSection = $("#conceptos-tbl tbody tr");

	$.ajax({
		url: baseURL+"index.php/Lectura_factura_ctrl/getConceptosCotizacion/"+idCotizacion,
		method: 'post',
		dataType: 'json',
		success: function(response){
			appendSection.each(function(index){
				k = $(this).find("#idMatched");
				k.find("*").remove();
				
				k.append("<option value='-1'>Seleccione una opción</option>");
				for(m=0, n=response.length; m<n; m++){
					k.append("<option value='"+response[m].id+"'>"+response[m].descripcion+"</option>");
				}
			});
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

	var matchedElements = $(".idMatched, .tipoConcepto");
	var tiposConcepto = $(".tipoConcepto");

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

function addMatchedSelect(){
	var cloneSection = $("#idMatched").clone(true);
	var appendSection = $("#append-matchCol");

	cloneSection.attr("id", "idMatched"+( $(".idMatched").size() ) );
	cloneSection.val("-1");

	appendSection.append(cloneSection);
}

$(function(){
	initDatepicker("#fechaPago", "#fechaPagoAlt", 'dd/mm/yy', 'yy-mm-dd');
	initDatepicker("#fechaCancelacion", "#fechaCancelacionAlt", 'dd/mm/yy', 'yy-mm-dd');

	$("#btn-add-matched-select").click(function(event){
		event.preventDefault();
		addMatchedSelect();
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

	$("#ivaFactura").change(function(){
		factura.iva = $(this).val();
	});

	$("#importeFactura").change(function(){
		factura.importe = $(this).val();
	});

	$("#clienteAsociado").change(function(event){
		event.preventDefault();

		retrieveRazonesSociales($(this).val());
	});

	$("#razonSocialAsociada").change(function(event){
		event.preventDefault();

		retrieveCotizaciones($(this).val());
	});

	$("#cotizacionAsociada").change(function(event){
		event.preventDefault();
		factura.idCotizacion = $(this).val();

		retrieveConceptosCotizacion($(this).val());
		isFill();
	});

	$(".idMatched").change(function(){
		var sender = $(this);
		var proxID = parseInt(sender.closest("tr").find("#id").html());

		factura.conceptos[proxID].idMatched = sender.val();
		isFill();
	});

	$(".tipoConcepto").change(function(){
		var sender = $(this);
		var proxID = parseInt(sender.closest("tr").find("#id").html());

		factura.conceptos[proxID].idTipoConcepto = sender.val();
		isFill();
	});

	$(".notasTextarea").change(function(){
		var sender = $(this);
		var proxID = parseInt(sender.closest("tr").find("#id").html());

		factura.conceptos[proxID].nota = sender.val();
	});

	$(".importeEfectivo").change(function(){
		var sender = $(this);
		var proxID = parseInt(sender.closest("tr").find("#id").html());

		factura.conceptos[proxID].montoEfectivo = sender.val();
	});

	$("#notasFactura").change(function(){
		factura.nota = $(this).val();
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
			},
			error: function(){
				alert("Error. Intente de nuevo, por favor.");
			}
		});
	});

	$("#btn-guardar-factura").prop("disabled", true);
	$("#fechaPago").change();
	$("#fechaCancelacion").change();
	$(".montoEfectivo").change();
});