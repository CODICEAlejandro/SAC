function retrieveRazonesSociales(idCliente){
	var k, n;
	var flag = 0;
	var appendedOption;
	var appendSection = $("#razonSocial");

	appendSection.find("*").remove();
	appendSection.append("<option value='-1'>Ninguna</option>");

	$.ajax({
		url: baseURL+"index.php/Form_carga_manual_factura_proveedor_ctrl/getRazonesSociales/"+idCliente,
		method: 'post',
		dataType: 'json',
		success: function(response){
			var result = response;

			for(k=0, n=result.length; k<n; k++){
				appendedOption = "<option value='"+result[k].id+"' ";
				appendedOption += ">"+result[k].razonSocial+"</option>"
				appendSection.append(appendedOption);
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function retrieveFechasFactura(idCliente){
	// Appends Section en este caso apunta al select donde se hacen
	// los matches correspondientes.
	var appendSection = $('select.idMatched');

	// Limpieza de los select
	appendSection.find("*").remove();
	appendSection.append('<option value="-1">Ninguno</option>');

	$.ajax({
		url: baseURL+'index.php/Form_carga_manual_factura_proveedor_ctrl/getFechasFacturacion/'+idCliente,
		method: 'post',
		data: {'idCliente': idCliente},
		dataType: 'json',
		success: function(response){
			var k, n;
			var appendedDescription;

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

function validate(){
	$(".AErrorMessage").remove();

	var result = areSelected($("select.obligatorio, .append-section-matchCol select.matchObligatorio"), "-1", true, "Seleccione una opción.");
	var flag = result.status;

	result = areNumeric($(".numericoObligatorio"), true, "El campo debe ser numérico.");
	flag += result.status;

	if(flag > 0) return false;
	else return true;
}

$(function(){
	//Función de append de conceptos en la tabla principal
	$("#btn-add-concepto-factura").click(function(event){
		event.preventDefault();

		var clon = jCloneSection($("#clone-section-concepto-factura"), $("#append-section-concepto-factura"), true);

		//Función de appens de impuestos en la columna del renglón correspondiente de la tabla principal
		clon.find("#btn-add-impuestoCol").click(function(event){
			event.preventDefault();

			var impuestoClon = jCloneSection($("#clone-section-impuesto"), clon.find("#append-section-impuestoCol"), true);

			impuestoClon.find("#delete-item").click(function(){
				impuestoClon.remove();
			});

			impuestoClon.find("#contraer-item").click(function(){
				var contraerSection = impuestoClon.find("#contraer-section");

				if(contraerSection.is(":visible")) contraerSection.hide();
				else contraerSection.show();
			});
		});

		//Función de append del match en la columna del renglón correspondiente de la tabla principal
		clon.find("#btn-add-matched-select").click(function(event){
			event.preventDefault();

			var matchClon = jCloneSection($("#clone-match-col"), clon.find("#append-section-matchCol"), true);

			matchClon.find("#delete-item").click(function(){
				matchClon.remove();
			});

			matchClon.find("#contraer-item").click(function(){
				var contraerSection = matchClon.find("#contraer-section");

				if(contraerSection.is(":visible")) contraerSection.hide();
				else contraerSection.show();
			});
		});
	});

	//Función de reacción al cambio del combo principal del cliente actual, para traer las razones sociales
	//correspondientes
	$("#proveedor").change(function(){
		retrieveRazonesSociales($(this).val());
	});

	//Función de reacción al cambio de razón social general, para actualizar las fechas de facturación en 
	//la tabla principal
	$("#razonSocial").change(function(){
		retrieveFechasFactura($(this).val());
	});

	//Función para guardar la factura actual en la base de datos
	$("#btn-guardar-factura").click(function(event){
		event.preventDefault();

		if(!validate()) return;

		//Reacolección de los datos de cabecera
		var proveedor = $("#proveedor").val();
		var razonSocial = $("#razonSocial").val();

		//Recolección de los datos de la tabla principal
		var t = $("#append-section-concepto-factura");

		//Eliminar el primer concepto, pues es el pattern para los clones
		t.find("#clone-section-concepto-factura").remove();
		$("#btn-add-concepto-factura").remove();

		//Formación de objetos de la tabla principal
		var rows_t = new Array();
		var oRows = $(".clone-section-concepto-factura");
		var current;
		var row;
		var impuesto;
		var match;

		var cantidad, unidadDeMedida, descripcion, valorUnitario, importe, precioLista;
		var importeLista, importeTotal, monto, textosDePosicion, notas;

		var impuestos;
		var matches;

		var contexto, operacion, codigo, base, tasa, monto;
		var idMatch;

		oRows.each(function(index){
			current = $(this);

			cantidad = current.find("#cantidadCol #cantidad").val();
			unidadDeMedida = current.find("#unidadDeMedidaCol #unidadDeMedida").val();
			descripcion = current.find("#descripcionCol #descripcion").val();
			valorUnitario = current.find("#valorUnitarioCol #valorUnitario").val();
			importe = current.find("#importeCol #importe").val();
			precioLista = current.find("#precioListaCol #precioLista").val();
			importeLista = current.find("#importeListaCol #importeLista").val();
			importeTotal = current.find("#importeTotalCol #importeTotal").val();
			monto = current.find("#montoCol #monto").val();
			textosDePosicion = current.find("#textosDePosicionCol #textosDePosicion").val();
			notas = current.find("#notasCol #notas").val();

			impuestos = current.find("#impuestosCol .row-impuesto");
			matches = current.find("#matchCol .clone-match-col");

			row = {
					'cantidad':cantidad,
					'unidadDeMedida':unidadDeMedida,
					'descripcion':descripcion,
					'valorUnitario':valorUnitario,
					'importe':importe,
					'precioLista':precioLista,
					'importeLista':importeLista,
					'importeTotal':importeTotal,
					'monto':monto,
					'textosDePosicion':textosDePosicion,
					'notas':notas,
					'impuestos': new Array(),
					'matches': new Array()
				};

			impuestos.each(function(index){
				contexto = $(this).find("#contexto").val();
				operacion = $(this).find("#operacion").val();
				codigo = $(this).find("#codigo").val();
				base = $(this).find("#base").val();
				tasa = $(this).find("#tasa").val();
				monto = $(this).find("#monto").val();

				impuesto = {
							'contexto':contexto,
							'operacion':operacion,
							'codigo':codigo,
							'base':base,
							'tasa':tasa,
							'monto':monto
						};

				row.impuestos.push(impuesto);
			});

			matches.each(function(index){
				idMatch = $(this).find("#idMatched").val();
				importe = $(this).find("#importeFechaFactura").html();
				match = {'id':idMatch, 'importe': importe};

				row.matches.push(match);
			});

			rows_t.push(row);
		});

		//Recolección de la información de la data general
		var fechaExpedicion = $("#fechaDeExpedicion-alt").val();
		var moneda = $("#moneda").val();
		var tipoDeCambioVenta = $("#tipoDeCambioVenta").val();
		var subtotalFactura = $("#subtotal").val();
		var totalFactura = $("#total").val();
		var totalEnLetra= $("#totalEnLetra").val();
		var formaDePago = $("#formaDePago").val();
		var totalTrasladosFederales = $("#totalTrasladosFederales").val();
		var totalIVATrasladado = $("#totalIVATrasladado").val();
		var totalIEPSTrasladado = $("#totalIEPS").val();
		var totalRetencionesFederales = $("#totalRetencionesFederales").val();
		var totalISRRetenido = $("#totalISRRetenido").val();
		var totalIVARetenido = $("#totalIVARetenido").val();
		var totalTrasladosLocales = $("#totalTrasladosLocales").val();
		var totalRetencionesLocales = $("#totalRetencionesLocales").val();
		var subtotalBruto = $("#subtotalBruto").val();
		var folioFactura = $("#folioFactura").val();
		var estadoFactura = $("#estadoFactura").val();
		var fechaDePago = $("#fechaPagoAlt").val();
		var fechaDeCancelacion = $("#fechaCancelacionAlt").val();
		var ordenDeCompra = $("#ordenCompra").val();
		var ivaFactura = $("#ivaFactura").val();
		var importeFactura = $("#importeFactura").val();
		var notasFactura = $("#notasFactura").val();

		var data_g = {
						'fechaExpedicion':fechaExpedicion,
						'moneda':moneda,
						'tipoDeCambioVenta':tipoDeCambioVenta,
						'subtotalFactura':subtotalFactura,
						'totalFactura':totalFactura,
						'totalEnLetra':totalEnLetra,
						'formaDePago':formaDePago,
						'totalTrasladosFederales':totalTrasladosFederales,
						'totalIVATrasladado':totalIVATrasladado,
						'totalIEPSTrasladado':totalIEPSTrasladado,
						'totalRetencionesFederales':totalRetencionesFederales,
						'totalISRRetenido':totalISRRetenido,
						'totalIVARetenido':totalIVARetenido,
						'totalTrasladosLocales':totalTrasladosLocales,
						'totalRetencionesLocales':totalRetencionesLocales,
						'subtotalBruto':subtotalBruto,
						'folioFactura':folioFactura,
						'estadoFactura':estadoFactura,
						'fechaDePago':fechaDePago,
						'fechaDeCancelacion':fechaDeCancelacion,
						'ordenDeCompra':ordenDeCompra,
						'ivaFactura':ivaFactura,
						'importeFactura':importeFactura,
						'notasFactura':notasFactura
					};

		//Mandar la petición para guardar la factura
		$.ajax({
			url: baseURL+'index.php/Form_carga_manual_factura_proveedor_ctrl/guardarFactura',
			method: 'post',
			data: {'proveedor':proveedor,'razonSocial':razonSocial,'rows':JSON.stringify(rows_t),'general':JSON.stringify(data_g)},
			dataType: 'text',
			success: function(response){
				alert("Operación realizada con éxito");
			},
			error: function(){
				alert("Ha ocurrdio un error. Inténte de nuevo más tarde, por favor.");
			}
		});
	});

	initDatepicker("#fechaDeExpedicion", "#fechaDeExpedicion-alt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaPago", "#fechaPagoAlt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaCancelacion", "#fechaCancelacionAlt", "dd/mm/yy", "yy-mm-dd");
});