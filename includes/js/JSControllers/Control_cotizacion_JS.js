function retrieveQuotations(){
	var idCliente = $("#currentCliente").val();
	var idRazon = $("#currentRazon").val();

	$.ajax({
		url: 'Control_cotizacion_ctrl/retrieveQuotations_AJAX',
		method: 'post',
		dataType: 'json',
		data: {'idCliente': idCliente, 'idRazonSocial': idRazon},
		success: function(response){
			var k, n, lastRow;
			var table = $("#tbl-razon-social tbody");
			table.find("*").remove();

			for(k = 0, n = response.data.length; k < n; k++){
				table.append("<tr></tr>");
				lastRow = table.find("tr:last-child");

				lastRow.append("<td id='id'>"+jEntityDecode(response.data[k].id)+"</td>");
				lastRow.append("<td id='razonSocial'>"+jEntityDecode(response.data[k].razonSocial)+"</td>");
				lastRow.append('<td><div class="input-group"><textarea rows="2" style="width: 95%" id="nota" class="form-control">'+response.data[k].nota+'</textarea><span class="input-group-btn"><button class="btn btn-default" id="btn-save-note" data-id='+response.data[k].id+' type="button"><span class="glyphicon glyphicon-floppy-disk"></span></button></span></div></td>');
				lastRow.append("<td id='creacion'>"+jEntityDecode(response.data[k].creacion)+"</td>");
				lastRow.append("<td><button id='btn-consult' class='btn btn-primary'>Consultar</td>");

				lastRow.find("#btn-save-note").click(function(event){
					saveNote($(this), $(this).attr('data-id'), $(this).closest('tr').find('#nota').val());
					event.preventDefault();
				});

				lastRow.find('#btn-consult').click(function(event){
					window.location.replace('Listado_facturas_ctrl/loadWindow/'+$(this).closest("tr").find("td#id").html());
					event.preventDefault();
				});

				lastRow.find("#nota").change(function(){
					$(this).parent().find("#btn-save-note").removeClass("btn-default").removeClass("btn-success").addClass("btn-danger");
				});
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Por favor, intente de nuevo.");
		}
	});
}

function retrieveSocialReasons(){
	var idCliente = $("#currentCliente").val();

	$.ajax({
		url: 'Control_cotizacion_ctrl/retrieveSocialReasons_AJAX',
		method: 'post',
		data: {'idCliente':idCliente},
		dataType: 'json',
		success: function(response){
			var k, n;
			var select = $("#currentRazon");
			select.find("*").remove();

			select.append("<option value='-1'>Todas</option>");
			for(k = 0, n = response.data.length; k < n; k++){
				select.append("<option value='"+response.data[k].id+"'>"+jEntityDecode(response.data[k].razonSocial)+"</option>");
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Por favor, intente de nuevo.");
		}
	});
}

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

$(function(){
	$("#currentCliente").change(function(){
		retrieveSocialReasons();
	});

	$("#form-query").submit(function(event){
		retrieveQuotations();
		event.preventDefault();
	});

	retrieveSocialReasons();
});