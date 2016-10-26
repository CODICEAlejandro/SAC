var cPage = 0;
var cCount = 10;
var filterByDate = false;

function saveNote(idFactura, nuevaNota){
	$.ajax({
		url: baseURL+'index.php/Listado_facturas_ctrl/updateNote_AJAX',
		method: 'post',
		data: {'id':idFactura, 'nota':nuevaNota},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function retrievePageItems(filtered = true){
	var idCotizacion = $("#currentCotizacion").val();
	var dateFrom = $("#dateDesdeAlt").val();
	var dateTo = $("#dateHastaAlt").val();
	var url = "retrieveBills_AJAX";

	if(!filtered) url = "retrieveAll_AJAX";

	$.ajax({
		url: baseURL+'index.php/Listado_facturas_ctrl/'+url,
		dataType: 'json',
		method: 'post',
		data: {'idCotizacion':idCotizacion, 'cPage':cPage, 'cCount':cCount, 'dateFrom':dateFrom, 'dateTo':dateTo},
		success: function(response){
			var table = $("#tbl-factura tbody");
			var k, n, lastRow;
			table.find("*").remove();

			if(!response.data.hasNextPage) $("#btn-nextPage").prop("disabled", true);
			else $("#btn-nextPage").prop("disabled", false);

			if(response.data.items.length > cCount) n = response.data.items.length - 1;
			else n = response.data.items.length;

			for(k=0; k < n; k++){
				table.append('<tr data-id='+response.data.items[k].id+'></tr>');

				lastRow = table.find("tr:last-child");
				lastRow.append("<td>"+response.data.items[k].id+"</td>");
				lastRow.append("<td>"+response.data.items[k].folio+"</td>");
				lastRow.append("<td>"+response.data.items[k].ordenCompra+"</td>");
				lastRow.append("<td>"+response.data.items[k].fechaPago+"</td>");
				lastRow.append('<td><div class="input-group"><textarea id="nota" rows="2" style="width: 95%" class="form-control">'+response.data.items[k].nota+'</textarea><span class="input-group-btn"><button class="btn btn-default" id="btn-save-note" data-id='+response.data.items[k].id+' type="button"><span class="glyphicon glyphicon-floppy-disk"></span></button></span></div></td>');
				lastRow.append("<td>"+response.data.items[k].estadoFactura+"</td>");
				lastRow.append("<td><button id='btn-consultar-factura' style='margin-top: 8px;' class='form-control btn btn-default'><span class='glyphicon glyphicon-list-alt'></span></button></td>");

				lastRow.find("#btn-save-note").click(function(event){
					event.preventDefault();

					saveNote($(this).attr("data-id"), $(this).closest("td").find("#nota").val());
					$(this).parent().find("#btn-save-note").removeClass().addClass("btn btn-success");
				});

				lastRow.find("#nota").change(function(){
					$(this).closest("tr").find("#btn-save-note").removeClass().addClass("btn btn-danger");	
				});

				lastRow.find("#btn-consultar-factura").click(function(event){
					var idFactura = $(this).closest("tr").attr("data-id");

					window.location.replace(baseURL+'index.php/Detalle_factura_ctrl/detallarFactura/'+idFactura);

					event.preventDefault();
				});
			}
		},
		error: function(){
			alert("Error al intentar consultar facturas. Intente de nuevo, por favor.");
		}
	});
}

function checkForPrevPage(){
	if(cPage == 0) $("#btn-prevPage").prop("disabled", true);
	else $("#btn-prevPage").prop("disabled", false);
}

$(function(){
	$("#dateDesde").datepicker({
		dateFormat: 'dd/mm/yy',
		altFormat: 'yy/mm/dd',
		altField: '#dateDesdeAlt'
	}).datepicker('setDate', new Date());

	$("#dateHasta").datepicker({
		dateFormat: 'dd/mm/yy',
		altFormat: 'yy/mm/dd',
		altField: '#dateHastaAlt'
	}).datepicker('setDate', new Date());

	$("#query-form").submit(function(event){
		event.preventDefault();
		cPage = 0;
		filterByDate = true;

		checkForPrevPage(filterByDate);
		retrievePageItems();
	});

	$("#btn-nextPage").click(function(event){
		event.preventDefault();
		cPage++;

		checkForPrevPage();
		retrievePageItems(filterByDate);
	});

	$("#btn-prevPage").click(function(event){
		event.preventDefault();
		cPage--;

		checkForPrevPage();
		retrievePageItems(filterByDate);
	});

	checkForPrevPage();
	retrievePageItems(filterByDate);
});