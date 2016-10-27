$(function(){
	$(".btn-note").click(function(event){
		event.preventDefault();

		var sender = $(this);
		var idConceptoFactura = sender.attr("data-id");
		var nuevaNota = sender.parent("#bloqueNota").find("#nota").val();

		$.ajax({
			url: baseURL+"index.php/Detalle_factura_ctrl/saveNote",
			data: {"idConcepto": idConceptoFactura, "nuevaNota": nuevaNota},
			method: "post",
			success: function(){
				sender.removeClass().addClass("btn btn-success btn-note");
			},
			error: function(){
				alert("Ha ocurrido un error. Intente de nuevo más tarde, por favor.");
			}
		});
	});
});