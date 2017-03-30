
function buscaRegistros() {
	
	$("#btn-search").click(function (event) {
		event.preventDefault();

		var parametro=$("#search").val();

		if (parametro=="") {
			alert("Debes ingresar los parámetros de búsqueda.");
		}else{

			$.ajax({
				url: baseURL+"index.php/Cotizacion/Listado_cotizacion_ctrl/busquedaRegistros",
				method: "post",
				dataType:"json",
				data:{ "parametro":parametro},
				success: function (response) {
					$("#cuerpoTabla").find("#contenidoTabla").remove();

					var clon;

					for (var i = 0, n=response.length; i < n; i++) {
						clon = $("#cloneSectionTabla").clone();
						a = response[i];

						clon.find("#folio").html(a.folio);
						clon.find("#nombreCliente").html(a.nombre_cli);
						clon.find("#titulo").html(a.titulo);
						clon.find("#contacto").html(a.nombre_acc+" "+a.apellido_acc+"<br>"+a.correo);
						clon.find("#importe_total").html(a.importe_total);
						clon.find("#fecha_alta").html(a.fecha_alta);
						clon.find("#fecha_inicio").html(a.fecha_inicio);
						clon.find("#fecha_fin").html(a.fecha_fin);
						clon.find("#nombreAcc").html(a.nombre_acc);
						clon.find("#status").html(a.clave_status);

						$("#cuerpoTabla").append(clon);
						clon.show();
						clon.find(".btn-info").show();
						clon.find(".btn-warning").show();
						clon.find(".btn-success").show();
						clon.find(".btn-danger").show();
						clon.find(".btn-primary").show();
					}
					
				},
				error: function(response){
					alert("Hubo un error al realizar la búsqueda");
				}
			});
		}
	});
	
}

window.onload=function() {
	buscaRegistros();
}