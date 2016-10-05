function retrieveData(){
	var idCliente = $("#idCliente").val();
	var idRazonSocial = $("#idRazonSocial").val();
	var idCotizacion = $("#idCotizacion").val();
	var table = $("#main-data-tbl tbody");

	var fechaFacturaDesde = $("#fechaFacturaDesdeAlt").val();
	var fechaFacturaHasta = $("#fechaFacturaHastaAlt").val();

	var fechaPagoDesde = $("#fechaPagoDesdeAlt").val();
	var fechaPagoHasta = $("#fechaPagoHastaAlt").val();

	var idEstadoFactura = $("#idEstadoFactura").val();

	if(!($("#filterByFechaFactura").is(":checked"))){
		fechaFacturaDesde = "none";
		fechaFacturaHasta = "none";
	}

	if(!($("#filterByFechaPago").is(":checked"))){
		fechaPagoDesde = "none";
		fechaPagoHasta = "none";
	}

	table.find("*").remove();

	$.ajax({
		url: baseURL+"index.php/Reporte_master_ctrl/getContentAJAX",
		data: {"idCliente": idCliente, "idRazonSocial": idRazonSocial, "idCotizacion": idCotizacion, "facturaDesde": fechaFacturaDesde, "facturaHasta": fechaFacturaHasta, "pagoDesde": fechaPagoDesde, "pagoHasta": fechaPagoHasta, "idEstadoFactura": idEstadoFactura},
		dataType: 'json',
		method: 'post',
		success: function(response){
			var k, n, lastRow;

			var mainData = response['mainData'];
			var analytics = response['analytics'];

			$("#numeroCotizaciones").html(analytics['numeroCotizaciones']);
			$("#numeroConceptosFacturados").html(analytics['numeroConceptosFacturados']);
			$("#numeroConceptosSinFactura").html(analytics['numeroConceptosSinFacturar']);

			for(k=0, n=mainData.length; k<n; k++){
				table.append("<tr></tr>");
				lastRow = table.find("tr:last-child");

				lastRow.append("<td>"+mainData[k].estadoFactura+"</td>");
				lastRow.append("<td>"+mainData[k].folio+"</td>");
				lastRow.append("<td>"+mainData[k].total+"</td>");
				lastRow.append("<td>"+mainData[k].fechaPago+"</td>");
				lastRow.append("<td>"+mainData[k].cliente+"</td>");
				lastRow.append("<td>"+mainData[k].id+"</td>");
				lastRow.append("<td>"+mainData[k].subtotal+"</td>");
				lastRow.append("<td>"+mainData[k].moneda+"</td>");
				lastRow.append("<td>"+mainData[k].fechaFactura+"</td>");
				lastRow.append("<td>"+mainData[k].ordenCompra+"</td>");
				lastRow.append("<td>"+mainData[k].tipoConcepto+"</td>");
				lastRow.append("<td>"+mainData[k].referencia+"</td>");
				lastRow.append("<td>"+mainData[k].descripcion+"</td>");
				lastRow.append("<td>"+mainData[k].tituloCotizacion+"</td>");
				lastRow.append("<td>"+mainData[k].fechaInicio+"</td>");
				lastRow.append("<td>"+mainData[k].fechaFin+"</td>");
				lastRow.append("<td>"+mainData[k].razonSocial+"</td>");
				lastRow.append("<td>"+mainData[k].fechaVenta+"</td>");
				lastRow.append("<td>"+mainData[k].fechaJuntaArranque+"</td>");
				lastRow.append("<td>"+mainData[k].cerrador+"</td>");
				lastRow.append("<td>"+mainData[k].responsable+"</td>");
				lastRow.append("<td>"+mainData[k].accountManager+"</td>");
				lastRow.append("<td>"+mainData[k].iva+"</td>");
				lastRow.append("<td>"+mainData[k].montoIVA+"</td>");
				lastRow.append("<td>"+mainData[k].importeEfectivo+"</td>");
				lastRow.append("<td>"+mainData[k].fechaCancelacion+"</td>");
				lastRow.append("<td>"+mainData[k].contrato+"</td>");
				lastRow.append("<td>"+mainData[k].nota+"</td>");
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function retrieveRazonesSociales(){
	var id = $("#idCliente").val();
	var appendSection = $("#idRazonSocial");

	appendSection.find("*").remove();
	appendSection.append("<option value='-1'>Mostrar todas</option>")

	$.ajax({
		url: baseURL+'index.php/Reporte_master_ctrl/getRazonesSociales/',
		dataType: 'json',
		data: {"idCliente" : id},
		method: 'post',
		success: function(response){
			var k, n;

			for(k=0, n=response.length; k<n; k++){
				appendSection.append("<option value="+response[k].id+">"+response[k].razonSocial+"</option>");
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function retrieveCotizaciones(){
	var id = $("#idRazonSocial").val();
	var appendSection = $("#idCotizacion");

	appendSection.find("*").remove();
	appendSection.append("<option value='-1'>Mostrar todas</option>")

	$.ajax({
		url: baseURL+'index.php/Reporte_master_ctrl/getCotizaciones/',
		dataType: 'json',
		data: {"idRazonSocial" : id},
		method: 'post',
		success: function(response){
			var k, n;

			for(k=0, n=response.length; k<n; k++){
				appendSection.append("<option value="+response[k].id+">"+response[k].folio+"</option>");
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

$(function(){
	$("#btn-consultar").click(function(event){
		event.preventDefault();

		retrieveData();
	});

	$("#idCliente").change(function(){
		retrieveRazonesSociales();
	});

	$("#idRazonSocial").change(function(){
		retrieveCotizaciones();
	});

	initDatepicker("#fechaPagoDesde", "#fechaPagoDesdeAlt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaCancelacionDesde", "#fechaCancelacionDesdeAlt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaFacturaDesde", "#fechaFacturaDesdeAlt", "dd/mm/yy", "yy-mm-dd");

	initDatepicker("#fechaPagoHasta", "#fechaPagoHastaAlt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaCancelacionHasta", "#fechaCancelacionHastaAlt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaFacturaHasta", "#fechaFacturaHastaAlt", "dd/mm/yy", "yy-mm-dd");
});