$(function(){
	$(".concepto-row .btn-es-nuevo").click(function(){
		var sender = $(this);
		var current_row = sender.closest("tr");
		var id_concepto = sender.attr("data-id");

		$.ajax({
			url: baseURL+"index.php/Facturacion/Categorizacion_facturacion_ctrl/categorizar",
			data: {"cat":1, "id_concepto":id_concepto},
			dataType: "text",
			method:"post",
			success: function(response){
				current_row.remove();
			},
			error: function(){
				alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			}
		});
	});

	$(".concepto-row .btn-ya-considerado").click(function(){
		var sender = $(this);
		var current_row = sender.closest("tr");
		var id_concepto = sender.attr("data-id");

		$.ajax({
			url: baseURL+"index.php/Facturacion/Categorizacion_facturacion_ctrl/categorizar",
			data: {"cat":0, "id_concepto":id_concepto},
			dataType: "text",
			method:"post",
			success: function(response){
				current_row.remove();
			},
			error: function(){
				alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			}
		});
	});
});