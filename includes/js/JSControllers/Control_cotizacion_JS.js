function retrieveQuotations(){
	var idCliente = $("#currentCliente").val();
	var idCotizacion = $("#currentCotizacion").val();

	$.ajax({
		url: 'Control_cotizacion_ctrl/retrieveQuotations_AJAX',
		method: 'post',
		dataType: 'json',
		data: {'idCliente': idCliente, 'idCotizacion': idCotizacion},
		beforeSend: function(){
			$("#tbl-razon-social tbody *").remove();
			$("#btn-submit-consulta").hide();
		},
		success: function(response){
			var k, n, m, i, j, x, lastRow;
			var table = $("#tbl-razon-social tbody");
			var lastConcepto;
			var lastFecha;
			var conceptos;
			var fechas;
			var appendSectionConceptos;
			var appendSectionFechasFactura;
			var numConceptos, numeroFechasFactura;
			var appendText;

			table.find("*").remove();

			for(k = 0, n = response.length; k < n; k++){
				table.append("<tr class='main-row'></tr>");
				lastRow = table.find("tr.main-row:last-child");

				lastRow.append("<td id='id'>"+jEntityDecode(response[k]['cotizacion'].id)+"</td>");

				appendText = '<td>'
				appendText += 	'<div class="input-group">';
				appendText += 		'<textarea rows="2" style="width: 95%" id="nota" class="form-control nota">';
				appendText += 			jEntityDecode(response[k]['cotizacion'].nota);
				appendText += 		'</textarea>';
				appendText += 		'<span class="input-group-btn">'
				appendText += 			'<button class="btn btn-default btn-save-note" id="btn-save-note"';
				appendText +=					'data-id='+response[k]['cotizacion'].id+' type="button">';
				appendText +=				'<span class="glyphicon glyphicon-floppy-disk"></span>';
				appendText += 			'</button>';
				appendText += 		'</span>';
				appendText += 	'</div>';
				appendText += '</td>';	

				lastRow.append(appendText);
				lastRow.append("<td id='creacion'>"+jEntityDecode(response[k]['cotizacion'].creacion)+"</td>");
				lastRow.append("<td id='conceptos'><table id='concepto-panel' class='table table-bordered'><tbody style='background-color: #eef;'></tbody></table></td>");

				appendSectionConceptos = lastRow.find("#conceptos #concepto-panel tbody");
				conceptos = response[k]['conceptos'];

				for(x = 0, numConceptos = conceptos.length; x<numConceptos; x++){
					appendText = "<tr id='row-concepto'>";
					appendText += 	"<td style='width: 200px;'>";
					appendText += 		"<div id='concepto-element'>";
					appendText += 			"<label>";
					appendText += 				conceptos[x]["concepto"].descripcion;
					appendText += 			"</label>";
					appendText += 			"<button class='form-control btn-cancelar-concepto btn btn-danger' data-id='"+conceptos[x]["concepto"].id+"'>";
					appendText += 				"Cancelar";
					appendText += 			"</button>";
					appendText += 		"</div>";
					appendText += 	"</td>";
					appendText +=	"<td>";
					
					//Ciclo de generación de fechas de factura
					for(m=0, numeroFechasFactura = conceptos[x]["fechas_factura"].length; m<numeroFechasFactura; m++){
						if(conceptos[x]["fechas_factura"][m].fecha_final == "00/00/0000"){
							conceptos[x]["fechas_factura"][m].fecha_final = conceptos[x]["fechas_factura"][m].fecha;
						}

						appendText += "<table style='width: 100%;' class='table table-bordered fecha-factura' fecha-factura-id='"+conceptos[x]["fechas_factura"][m].id+"'><tbody>";
						appendText += 	"<tr>";
						appendText += 		"<td>";
						appendText +=			"<div class='row'>";
						appendText +=				"<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>";
						appendText +=					"<input type='text' id='fecha-factura' class='datepicker form-control' value='"+conceptos[x]["fechas_factura"][m].fecha_final+"'>";
						appendText +=					"<input type='text' id='fecha-factura-alt' class='form-control'>";
						appendText +=				"</div>";
						appendText +=				"<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>";
						appendText +=					"<button class='btn btn-success form-control btn-guardar-fecha'>Guardar</button>";
						appendText +=				"</div>";
						appendText +=				"<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
						appendText +=					"<div class='form-group'>";
						appendText +=						"<label>Nota</label>";
						appendText +=						"<textarea class='form-control' id='nota-fecha-factura'>"+jEntityDecode(conceptos[x]["fechas_factura"][m].nota)+"</textarea>";
						appendText +=					"</div>";
						appendText +=					"<div class='form-group'>";
						appendText +=						"<label>Referencia</label>";
						appendText +=						"<textarea class='form-control' id='referencia-fecha-factura'>"+jEntityDecode(conceptos[x]["fechas_factura"][m].referencia)+"</textarea>";
						appendText +=					"</div>";
						appendText +=				"</div>";
						appendText +=			"</div>";
						appendText += 			"<button class='form-control btn-cancelar-fecha btn btn-danger' data-id='"+conceptos[x]["fechas_factura"][m].id+"'>";
						appendText += 				"Cancelar";
						appendText += 			"</button>";
						appendText += 		"</td>";
						appendText += 	"</tr>";
						appendText += "</tbody></table>";
					}

					appendText +=	"</td>";
					appendText += "</tr>";

					appendSectionConceptos.append(appendText);

				}
			}

			clickCancelarConcepto();
			clickSaveNota();
			changeNota();

			clickCancelarFechaFactura();
			clickGuardarFechaFactura();
			
			$(".datepicker").each(function(){
				var partes = $(this).val().split("/");
				var dia = parseInt(partes[0]);
				var mes = parseInt(partes[1])-1;
				var anio = parseInt(partes[2]);

				//alert(dia+"-"+mes+"-"+anio);
				jInitDatepicker($(this), $(this).siblings("#fecha-factura-alt"), 'dd/mm/yy', 'yy-mm-dd', new Date(anio, mes, dia, 0, 0, 0, 0));				
			});

			//Vuelve a habilitar botón de consulta
			$("#btn-submit-consulta").show();
		},
		error: function(){
			alert("Ha ocurrido un error. Por favor, intente de nuevo.");

			//Vuelve a habilitar botón de consulta
			$("#btn-submit-consulta").show();
		}
	});
}

// ***************************
// ******** Triggers *********
// ***************************

function clickCancelaConcepto(){
	$(".btn-cancelar-concepto").click(function(e){
		e.preventDefault();

		if(confirm("¿Está seguro que desea cancelar el concepto con sus fechas asociadas?"))
			cancelarConcepto($(this));
	});
}

function clickCancelarFechaFactura(){
	$(".btn-cancelar-fecha").click(function(e){
		e.preventDefault();

		if(confirm("¿Está seguro que desea cancelar esta fecha de facturación?"))
			cancelarFechaFactura($(this));
	});	
}

function clickGuardarFechaFactura(){
	$(".btn-guardar-fecha").click(function(e){
		e.preventDefault();

		guardarFechaFactura($(this));
	});
}

function clickCancelarConcepto(){
	$(".btn-cancelar-concepto").click(function(e){
		e.preventDefault();
	
		if(confirm("¿Está seguro que desea cancelar el concepto con sus fechas asociadas?"))
			cancelarConcepto($(this));
	});
}

function changeNota(){
	$(".nota").change(function(){
		$(this).parent().find("#btn-save-note").removeClass("btn-default").removeClass("btn-success").addClass("btn-danger");
	});
}

function clickSaveNota(){
	$(".btn-save-note").click(function(event){
		saveNote($(this), $(this).attr('data-id'), $(this).closest('tr').find('#nota').val());
		event.preventDefault();
	});	
}

// ***************************
// ******** Handlers *********
// ***************************

function saveNote(sender, id, note){
	$.ajax({
		url: 'Control_cotizacion_ctrl/saveNote_AJAX',
		method: 'post',
		data: {'id': id, 'nota': note},
		success: function(){
			sender.removeClass("btn-danger").removeClass("btn-default").addClass("btn-success");
		},
		error: function(){
			alert("Ha ocurrido un error al intentar guardar. Intente de nuevo, por favor.");
		}
	});
}

// Cancela las fechas de facturación con estado "por facturar" 
// asociadas al concepto de cotización en cuestión.

function cancelarConcepto(sender){
	var idConcepto = sender.attr("data-id");

	$.ajax({
		url: 'Control_cotizacion_ctrl/cancelarConcepto',
		data: {"idConcepto":idConcepto},
		method: 'post',
		success: function(response){
			//Elimina de vista las fechas de facturación asociadas
			var cRow = sender.closest("#row-concepto");
			var fechasFactura = cRow.find(".fecha-factura");

			fechasFactura.remove();			
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function guardarFechaFactura(sender){
	var parent = sender.closest("table");
	var idFechaFactura = parent.attr("fecha-factura-id");
	var nuevaFecha = parent.find("#fecha-factura-alt").val();
	var nota = parent.find("#nota-fecha-factura").val();
	var referencia = parent.find("#referencia-fecha-factura").val();

	$.ajax({
		url: 'Control_cotizacion_ctrl/guardarFechaFactura',
		data: {"idFechaFactura":idFechaFactura, 'nuevaFecha':nuevaFecha, 'nota':nota, 'referencia': referencia},
		method: 'post',
		success: function(response){
			alert("Fecha actualizada");
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function cancelarFechaFactura(sender){
	var parent = sender.closest("table");
	var idFechaFactura = parent.attr("fecha-factura-id");

	$.ajax({
		url: 'Control_cotizacion_ctrl/cancelarFechaFactura',
		data: {"idFechaFactura":idFechaFactura},
		method: 'post',
		success: function(response){
			//Borra de vista la fecha de factura en cuestión
			parent.remove();
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function traerCotizaciones(){
	var cCliente = $("#currentCliente").val();

	$.ajax({
		url: 'Control_cotizacion_ctrl/traerCotizaciones_AJAX',
		data: {'idCliente': cCliente},
		method: 'post',
		dataType: 'json',
		success: function(response){
			var k, n;
			var appendSection = $("#currentCotizacion");

			appendSection.find("*").remove();
			appendSection.append('<option value="-1">Mostrar todas</option>');

			for(k=0, n=response.length; k<n; k++){
				appendSection.append('<option value="'+response[k].id+'">'+response[k].folio+' -> '+response[k].titulo+'</option>');
			}
		}
	});
}

$(function(){
	$("#form-query").submit(function(event){
		retrieveQuotations();
		event.preventDefault();
	});

	$("#currentCliente").change(function(){
		traerCotizaciones();
	});
});