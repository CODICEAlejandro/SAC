function retrieveRazonesSociales(idCliente){
	var k, n;
	var flag = 0;
	var appendedOption;
	var appendSection = $("#razonSocial");

	appendSection.find("*").remove();
	appendSection.append("<option value='-1'>Ninguna</option>");

	$.ajax({
		url: baseURL+"index.php/Form_carga_manual_factura_ctrl/getRazonesSociales/"+idCliente,
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
	var fechaDesde = $("#fecha_desde_alt").val();
	var fechaHasta = $("#fecha_hasta_alt").val();

	// Limpieza de los select
	appendSection.find("*").remove();
	appendSection.append('<option value="-1">Ninguno</option>');

	$.ajax({
		url: baseURL+'index.php/Lectura_factura_ctrl/getFechasFacturacion',
		method: 'post',
		data: {'idCliente': JSON.stringify(idCliente), 'fecha_desde':fechaDesde , 'fecha_hasta':fechaHasta},
		dataType: 'json',
		success: function(response){
			var k, n;
			var appendedDescription;

			totalFechasFactura = new Array();

			for(k=0, n=response.length; k<n; k++){
				appendedDescription =  '<option value="'+response[k].idFechaFactura+'">';
				appendedDescription += response[k].folioCotizacion+' - ';
				appendedDescription += response[k].referenciaFecha+' - ';
				appendedDescription += response[k].fechaFactura;
				appendedDescription += '</option>';

				totalFechasFactura.push(new Array(response[k].idFechaFactura, appendedDescription));
				appendSection.append(appendedDescription);
			}			
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function pintarFechasDisponibles(){
	var selects = $("#conceptos-tbl tbody tr #matchCol .clone-match-col select.idMatched");
	var cSelect;
	var respaldo;
	var matches = new Array();
	var k, n, flag;
	var i, j;
	var idFechaFactura, option;

	// matches = contiene la lista de id's de factura que ya han sido seleccionados
	// totalFechasFactura = arreglo de arreglos de la forma = { {idFechaFactura, HTMLDeOption}, ... }

	// Forma arreglo con matches
	selects.each(function(index){
		matches.push($(this).val());
	});

	// Forma options correspondientes con cada select, conservando la opción que actualmente está seleccionada
	selects.each(function(index){
		cSelect = $(this);
		respaldo = cSelect.val();

		//Limpia los options actuales
		cSelect.find("*").remove();
		cSelect.append('<option value="-1">Ninguno</option>');

		//Agregar opciones que no estén en la lista de matches + la opción que está actualmente seleccionada
		for(k=0, n=totalFechasFactura.length; k<n; k++){
			flag = true;	//Para flag = true => Agrega option a select

			//Verificar si el ID en cuestión es el seleccionado
			idFechaFactura = totalFechasFactura[k][0];
			option = totalFechasFactura[k][1];

			if(respaldo != idFechaFactura){
				//Verifica que el ID en cuestión no esté en la lista de no disponibles
				for(i=0, j=matches.length; i<j; i++){
					if(matches[i] == idFechaFactura){
						flag = false;
						break;
					}
				}
			}

			if(flag) cSelect.append(option);
		}

		cSelect.val(respaldo);
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
	initDatepicker("#fecha_desde", "#fecha_desde_alt", 'dd/mm/yy', 'yy-mm-dd');
	initDatepicker("#fecha_hasta", "#fecha_hasta_alt", 'dd/mm/yy', 'yy-mm-dd');

	$("#btn-agregar-cliente").click(function(event){
		event.preventDefault();

		var cloneSection = $("#main-select-cliente #cliente").clone(true);
		var appendSection = $("#append-section-cliente");

		cloneSection.removeAttr("id");
		cloneSection.val("-1");

		appendSection.append(cloneSection);
	});

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
				pintarFechasDisponibles();
			});

			matchClon.find("#contraer-item").click(function(){
				var contraerSection = matchClon.find("#contraer-section");

				if(contraerSection.is(":visible")) contraerSection.hide();
				else contraerSection.show();
			});

			pintarFechasDisponibles();
		});
	});

	//Función de reacción al cambio del combo principal del cliente actual, para traer las razones sociales
	//correspondientes
	$("#cliente").change(function(){
		var clientePrincipal = $("#cliente").val();

		retrieveRazonesSociales(clientePrincipal);
	});

	$(".slc-clienteAsignado, #fecha_desde, #fecha_hasta").change(function(){
		var idClientes = new Array();
		var sender;

		$(".slc-clienteAsignado").each(function(index){
			sender = $(this);
			idClientes.push(sender.val());
		});

		retrieveFechasFactura(idClientes);		
	});

	//Función para guardar la factura actual en la base de datos
	$("#btn-guardar-factura").click(function(event){
		event.preventDefault();

		if(!validate()) return;

		//Reacolección de los datos de cabecera
		var cliente = $("#cliente").val();
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
			url: baseURL+'index.php/Form_carga_manual_factura_ctrl/guardarFactura',
			method: 'post',
			data: {'cliente':cliente,'razonSocial':razonSocial,'rows':JSON.stringify(rows_t),'general':JSON.stringify(data_g)},
			dataType: 'text',
			success: function(response){
				alert("Operación realizada con éxito");
				location.href = baseURL+"index.php/Panel_control_ctrl";
			},
			error: function(){
				alert("Ha ocurrdio un error. Inténte de nuevo más tarde, por favor.");
			}
		});
	});

	$("select.idMatched").change(function(){
		pintarFechasDisponibles();
	});

	initDatepicker("#fechaDeExpedicion", "#fechaDeExpedicion-alt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaPago", "#fechaPagoAlt", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fechaCancelacion", "#fechaCancelacionAlt", "dd/mm/yy", "yy-mm-dd");
});