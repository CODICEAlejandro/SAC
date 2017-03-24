function cargarContactos(idCliente){
	$.ajax({
		method: 'post',
		data: {'idCliente': idCliente},
		dataType: 'json',
		success: function(response){
			var select = $("#id-contacto");
			var k, n = response.length;

			for(k=0; k<n; k++){
				select.append("<option value='"+response[k].id+"'>"+response[k].+"</option>");
			}
		}
	});
}

$(function(){
	$("#id-cliente").change(function(){
		var idCliente = $(this).val();

		cargarContactos(idCliente);
	});
});