var estadosFactura = new Array();

function retrieveABill(){
	var folio = $("#buscadorFolio").val();
	var table = $("#main-data-tbl tbody");
	table.find("*").remove();

	$.ajax({
		url: baseURL+"index.php/Reporte_master_ctrl/getABillAJAX",
		data: {'folio': folio},
		dataType: 'json',
		method: 'post',
		beforeSend: function(){
			$("#cont-charge-bar").show();
			$("#cont-data-area").hide();
		},
		complete: function(){
			$("#cont-charge-bar").hide();
			$("#cont-data-area").show();
		},
		success: function(response){
			var k, n, lastRow;

			var mainData = response['mainData'];
			var analytics = response['analytics'];
			var appendedEstadoFactura = "<select id='estadoFacturaSelect' class='form-control'  style='width: 150px;'>";

			for(k=0, n=estadosFactura.length; k<n; k++){
				appendedEstadoFactura += "<option value="+estadosFactura[k].id+">"+estadosFactura[k].descripcion+"</option>";
			}

			appendedEstadoFactura += "</select>";

			$("#numeroCotizaciones").html(analytics['numeroCotizaciones']);
			$("#numeroConceptosFacturados").html(analytics['numeroConceptosFacturados']);
			$("#numeroConceptosSinFactura").html(analytics['numeroConceptosSinFacturar']);

			$("#importeNoFacturadoPesos").html(analytics['importeNoFacturadoPesos']);
			$("#importeNoFacturadoDolares").html(analytics['importeNoFacturadoDolares']);
			$("#importeFacturadoPesos").html(analytics['importeFacturadoPesos']);
			$("#importeFacturadoDolares").html(analytics['importeFacturadoDolares']);

			$("#totalPesos").html( (analytics['importeNoFacturadoPesos'] + analytics['importeFacturadoPesos']) + " MXN" );
			$("#totalDolares").html( (analytics['importeNoFacturadoDolares'] + analytics['importeFacturadoDolares']) + " USD");

			for(k=0, n=mainData.length; k<n; k++){
				table.append("<tr></tr>");
				lastRow = table.find("tr:last-child");

				lastRow.append("<td id='col-estadoFactura'>"+appendedEstadoFactura+"</td>");
				lastRow.append("<td id='col-folio'>"+mainData[k].folio+"</td>");
				lastRow.append("<td id='col-total'>"+mainData[k].total+"</td>");
				lastRow.append("<td id='col-fechaPago'>"+mainData[k].fechaPago+"</td>");
				lastRow.append("<td id='col-cliente'>"+mainData[k].cliente+"</td>");
				lastRow.append("<td id='col-id'>"+mainData[k].id+"</td>");
				lastRow.append("<td id='col-subtotal'>"+mainData[k].subtotal+"</td>");
				lastRow.append("<td id='col-moneda'>"+mainData[k].moneda+"</td>");
				lastRow.append("<td id='col-fechaFactura'>"+mainData[k].fechaFactura+"</td>");
				lastRow.append("<td id='col-ordenCompra'>"+mainData[k].ordenCompra+"</td>");
				lastRow.append("<td id='col-tipoConcepto'>"+mainData[k].tipoConcepto+"</td>");
				lastRow.append("<td id='col-referencia'>"+mainData[k].referencia+"</td>");
				lastRow.append("<td id='col-descripcion'>"+mainData[k].descripcion+"</td>");
				lastRow.append("<td id='col-tituloCotizacion'>"+mainData[k].tituloCotizacion+"</td>");
				lastRow.append("<td id='col-fechaInicio'>"+mainData[k].fechaInicio+"</td>");
				lastRow.append("<td id='col-fechaFin'>"+mainData[k].fechaFin+"</td>");
				lastRow.append("<td id='col-razonSocial'>"+mainData[k].razonSocial+"</td>");
				lastRow.append("<td id='col-fechaVenta'>"+mainData[k].fechaVenta+"</td>");
				lastRow.append("<td id='col-fechaJuntaArranque'>"+mainData[k].fechaJuntaArranque+"</td>");
				lastRow.append("<td id='col-cerrador'>"+mainData[k].cerrador+"</td>");
				lastRow.append("<td id='col-responsable'>"+mainData[k].responsable+"</td>");
				lastRow.append("<td id='col-accountManager'>"+mainData[k].accountManager+"</td>");
				lastRow.append("<td id='col-iva'>"+mainData[k].iva+"</td>");
				lastRow.append("<td id='col-montoIVA'>"+mainData[k].montoIVA+"</td>");
				lastRow.append("<td id='col-importeEfectivo'>"+mainData[k].importeEfectivo+"</td>");
				lastRow.append("<td id='col-fechaCancelacion'>"+mainData[k].fechaCancelacion+"</td>");
				lastRow.append("<td id='col-contrato'>"+mainData[k].contrato+"</td>");
				lastRow.append('<td id="col-nota"><div class="input-group" id="fatherNote" style="width: 300px;"><textarea rows="4" style="width: 95%" id="nota" class="form-control notaConcepto">'+mainData[k].nota+'</textarea><span class="input-group-btn"><button class="btn btn-default" id="btn-save-note" data-id='+mainData[k].idConceptoCotizacion+' type="button"><span class="glyphicon glyphicon-floppy-disk"></span></button></span></div></td>');

				lastRow.find("#estadoFacturaSelect").val(mainData[k].estadoFactura);
				lastRow.find("#estadoFacturaSelect").attr('data-id', mainData[k].idConceptoCotizacion);
	
				lastRow.find("#btn-save-note").click(function(){
					var currentID = $(this).attr("data-id");
					var parent = $(this).closest("#fatherNote");
					var note = parent.find("#nota").val();

					var estado = saveNote(currentID, note);

					if(!estado){
						$(this).removeClass().addClass("btn btn-danger");
					}else{
						$(this).removeClass().addClass("btn btn-success");						
					}
				});


				lastRow.find("#nota").change(function(){
					var parent = $(this).parent();
					var button = parent.find("#btn-save-note");
					button.removeClass().addClass("btn btn-warning");
				});

				lastRow.find("#estadoFacturaSelect").change(function(){
					var currentID = $(this).attr("data-id");
					var currentEstadoFactura = $(this).val();

					saveEstadoFactura(currentID, currentEstadoFactura);
				});
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}		
	});
}

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
		beforeSend: function(){
			$("#cont-charge-bar").show();
			$("#cont-data-area").hide();
		},
		complete: function(){
			$("#cont-charge-bar").hide();
			$("#cont-data-area").show();
		},
		success: function(response){
			var k, n, lastRow;

			var mainData = response['mainData'];
			var analytics = response['analytics'];

			var appendedEstadoFactura = "<select id='estadoFacturaSelect' class='form-control' style='width: 150px;'>";

			for(k=0, n=estadosFactura.length; k<n; k++){
				appendedEstadoFactura += "<option value="+estadosFactura[k].id+">"+estadosFactura[k].descripcion+"</option>";
			}

			appendedEstadoFactura += "</select>";

			$("#numeroCotizaciones").html(analytics['numeroCotizaciones']);
			$("#numeroConceptosFacturados").html(analytics['numeroConceptosFacturados']);
			$("#numeroConceptosSinFactura").html(analytics['numeroConceptosSinFacturar']);

			$("#importeNoFacturadoPesos").html(analytics['importeNoFacturadoPesos']);
			$("#importeNoFacturadoDolares").html(analytics['importeNoFacturadoDolares']);
			$("#importeFacturadoPesos").html(analytics['importeFacturadoPesos']);
			$("#importeFacturadoDolares").html(analytics['importeFacturadoDolares']);

			$("#totalPesos").html( (analytics['importeNoFacturadoPesos'] + analytics['importeFacturadoPesos']) + " MXN" );
			$("#totalDolares").html( (analytics['importeNoFacturadoDolares'] + analytics['importeFacturadoDolares']) + " USD");

			for(k=0, n=mainData.length; k<n; k++){
				table.append("<tr></tr>");
				lastRow = table.find("tr:last-child");

				lastRow.append("<td id='col-estadoFactura'>"+appendedEstadoFactura+"</td>");
				lastRow.append("<td id='col-folio'>"+mainData[k].folio+"</td>");
				lastRow.append("<td id='col-total'>"+mainData[k].total+"</td>");
				lastRow.append("<td id='col-fechaPago'>"+mainData[k].fechaPago+"</td>");
				lastRow.append("<td id='col-cliente'>"+mainData[k].cliente+"</td>");
				lastRow.append("<td id='col-id'>"+mainData[k].id+"</td>");
				lastRow.append("<td id='col-subtotal'>"+mainData[k].subtotal+"</td>");
				lastRow.append("<td id='col-moneda'>"+mainData[k].moneda+"</td>");
				lastRow.append("<td id='col-fechaFactura'>"+mainData[k].fechaFactura+"</td>");
				lastRow.append("<td id='col-ordenCompra'>"+mainData[k].ordenCompra+"</td>");
				lastRow.append("<td id='col-tipoConcepto'>"+mainData[k].tipoConcepto+"</td>");
				lastRow.append("<td id='col-referencia'>"+mainData[k].referencia+"</td>");
				lastRow.append("<td id='col-descripcion'>"+mainData[k].descripcion+"</td>");
				lastRow.append("<td id='col-tituloCotizacion'>"+mainData[k].tituloCotizacion+"</td>");
				lastRow.append("<td id='col-fechaInicio'>"+mainData[k].fechaInicio+"</td>");
				lastRow.append("<td id='col-fechaFin'>"+mainData[k].fechaFin+"</td>");
				lastRow.append("<td id='col-razonSocial'>"+mainData[k].razonSocial+"</td>");
				lastRow.append("<td id='col-fechaVenta'>"+mainData[k].fechaVenta+"</td>");
				lastRow.append("<td id='col-fechaJuntaArranque'>"+mainData[k].fechaJuntaArranque+"</td>");
				lastRow.append("<td id='col-cerrador'>"+mainData[k].cerrador+"</td>");
				lastRow.append("<td id='col-responsable'>"+mainData[k].responsable+"</td>");
				lastRow.append("<td id='col-accountManager'>"+mainData[k].accountManager+"</td>");
				lastRow.append("<td id='col-iva'>"+mainData[k].iva+"</td>");
				lastRow.append("<td id='col-montoIVA'>"+mainData[k].montoIVA+"</td>");
				lastRow.append("<td id='col-importeEfectivo'>"+mainData[k].importeEfectivo+"</td>");
				lastRow.append("<td id='col-fechaCancelacion'>"+mainData[k].fechaCancelacion+"</td>");
				lastRow.append("<td id='col-contrato'>"+mainData[k].contrato+"</td>");
				lastRow.append('<td id="col-nota"><div class="input-group" id="fatherNote" style="width: 300px;"><textarea rows="4" style="width: 95%" id="nota" class="form-control notaConcepto">'+mainData[k].nota+'</textarea><span class="input-group-btn"><button class="btn btn-default" id="btn-save-note" data-id='+mainData[k].idConceptoCotizacion+' type="button"><span class="glyphicon glyphicon-floppy-disk"></span></button></span></div></td>');

				lastRow.find("#estadoFacturaSelect").val(mainData[k].estadoFactura);
				lastRow.find("#estadoFacturaSelect").attr('data-id', mainData[k].idConceptoCotizacion);
	
				lastRow.find("#btn-save-note").click(function(){
					var currentID = $(this).attr("data-id");
					var parent = $(this).closest("#fatherNote");
					var note = parent.find("#nota").val();

					var estado = saveNote(currentID, note);

					if(!estado){
						$(this).removeClass().addClass("btn btn-danger");
					}else{
						$(this).removeClass().addClass("btn btn-success");						
					}
				});


				lastRow.find("#nota").change(function(){
					var parent = $(this).parent();
					var button = parent.find("#btn-save-note");
					button.removeClass().addClass("btn btn-warning");
				});

				lastRow.find("#estadoFacturaSelect").change(function(){
					var currentID = $(this).attr("data-id");
					var currentEstadoFactura = $(this).val();

					saveEstadoFactura(currentID, currentEstadoFactura);
				});
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function saveNote(idConceptoCotizacion, nota){
	var estadoPeticion = 1;

	$.ajax({
		url: baseURL+'index.php/Reporte_master_ctrl/saveNote',
		method: 'post',
		data: {'idConceptoCotizacion': idConceptoCotizacion, 'nota': nota},
		error: function(){
			estadoPeticion = 0;
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});

	return estadoPeticion;
}

function saveEstadoFactura(idConceptoCotizacion, idEstadoFactura){
	var estadoPeticion = 1;

	$.ajax({
		url: baseURL+'index.php/Reporte_master_ctrl/saveEstadoFactura',
		method: 'post',
		data: {'idConceptoCotizacion': idConceptoCotizacion, 'idEstadoFactura': idEstadoFactura},
		error: function(){
			estadoPeticion = 0;
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});

	return estadoPeticion;
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

function retrieveEstadosFactura(){
	$.ajax({
		url: baseURL+'index.php/Reporte_master_ctrl/getEstadosFactura/',
		dataType: 'json',
		method: 'post',
		success: function(response){
			estadosFactura = response;
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function getExcelData(){
	var k, n, cR;
	var fCols;
	var fRows;

	var result = new Array();

	var table = $("#main-data-tbl tbody");
	var rows = table.find("tr");

	var cR;

	fRows = "[";

	for(k=0, n=rows.length; k<n; k++){
		cR = $(rows[k]);

		fCols += "{";

		fCols += "'estadoFactura':'"+cR.find("#col-estadoFactura").html()+"',";
		fCols += "'folio':'"+cR.find("#col-folio").html()+"',";
		fCols += "'total':'"+cR.find("#col-total").html()+"',";
		fCols += "'fechaPago':'"+cR.find("#col-fechaPago").html()+"',";
		fCols += "'cliente':'"+cR.find("#col-cliente").html()+"',";
		fCols += "'id':'"+cR.find("#col-id").html()+"',";
		fCols += "'subtotal':'"+cR.find("#col-subtotal").html()+"',";
		fCols += "'moneda':'"+cR.find("#col-moneda").html()+"',";
		fCols += "'fechaFactura':'"+cR.find("#col-fechaFactura").html()+"',";
		fCols += "'ordenCompra':'"+cR.find("#col-ordenCompra").html()+"',";
		fCols += "'tipoConcepto':'"+cR.find("#col-tipoConcepto").html()+"',";
		fCols += "'referencia':'"+cR.find("#col-referencia").html()+"',";
		fCols += "'descripcion':'"+cR.find("#col-descripcion").html()+"',";
		fCols += "'tituloCotizacion':'"+cR.find("#col-tituloCotizacion").html()+"',";
		fCols += "'fechaInicio':'"+cR.find("#col-fechaInicio").html()+"',";
		fCols += "'fechaFin':'"+cR.find("#col-fechaFin").html()+"',";
		fCols += "'razonSocial':'"+cR.find("#col-razonSocial").html()+"',";
		fCols += "'fechaVenta':'"+cR.find("#col-fechaVenta").html()+"',";
		fCols += "'fechaJuntaArranque':'"+cR.find("#col-fechaJuntaArranque").html()+"',";
		fCols += "'cerrador':'"+cR.find("#col-cerrador").html()+"',";
		fCols += "'responsable':'"+cR.find("#col-responsable").html()+"',";
		fCols += "'accountManager':'"+cR.find("#col-accountManager").html()+"',";
		fCols += "'iva':'"+cR.find("#col-iva").html()+"',";
		fCols += "'montoIVA':'"+cR.find("#col-montoIVA").html()+"',";
		fCols += "'importeEfectivo':'"+cR.find("#col-importeEfectivo").html()+"',";
		fCols += "'fechaCancelacion':'"+cR.find("#col-fechaCancelacion").html()+"',";
		fCols += "'contrato':'"+cR.find("#col-contrato").html()+"',";
		fCols += "'nota':'"+cR.find("#col-nota").html()+"'";

		fCols += "}";

		if(k < (n-1)) fCols += ",";

		fRows += fCols;
	}

	fRows += "]";

	return fRows;
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

	$("#btn-consultar-folio-factura").click(function(){
		retrieveABill();
	});

	$("#btn-export-xls").click(function(event){
		event.preventDefault();
		//$("#form-excel").find("#dataXLS").val($("#table-main-content").html());
		var excelData = getExcelData();
		alert(excelData);

		//$("#form-excel").submit();
	});

	$("#cont-charge-bar").hide();
	$("#cont-data-area").hide();

	initDatepicker("#fechaPagoDesde", "#fechaPagoDesdeAlt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaCancelacionDesde", "#fechaCancelacionDesdeAlt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaFacturaDesde", "#fechaFacturaDesdeAlt", "dd/mm/yy", "yy-mm-dd");

	initDatepicker("#fechaPagoHasta", "#fechaPagoHastaAlt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaCancelacionHasta", "#fechaCancelacionHastaAlt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaFacturaHasta", "#fechaFacturaHastaAlt", "dd/mm/yy", "yy-mm-dd");

	retrieveEstadosFactura();
});