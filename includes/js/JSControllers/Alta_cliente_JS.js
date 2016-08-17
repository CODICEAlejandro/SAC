function putMainInformation(){
	var cCliente = $("#cCliente").val();

	if(cCliente == -1){
		$("#rowNuevoCliente").show();
		$("#rowEdicionCliente").hide();
	}else{

		$.ajax({
			url: pageController+'/traerInformacionPrincipal_AJAX/'+cCliente,
			method: 'post',
			dataType: 'json',
			success: function(response){
				$("#form-edita #nombre:visible").val(response.cliente.nombre);
				$("#form-edita input[name='estadoActivo']").val(response.cliente.estadoActivo);
				$("#form-edita input[name='id']").val(cCliente);

				checkEstadoActivo();
			},
			error: function(){
				alert("Ha ocurrido un error al intentar consultar el cliente seleccionado. Intente de nuevo, por favor.")
			}
		});

		$("#rowNuevoCliente").hide();
		$("#rowEdicionCliente").show();				
	}

}

//************************** Acciones de botón de activo e inactivo

// Revisa si el cliente seleccionado es -1 o diferente, para saber acción que se está realizando
// -1 : Ninguna acción (Solo mostrar u ocultar renglón correspondiente)
// != -1 : Traer datos de cliente con ajax y vaciar en formulario. Mostrar u ocultar renglón correspondiente
function checkCCliente(){
	var cCliente = $("#cCliente").val();

	$.ajax({
		url: pageController+'/consultarCliente_AJAX/'+cCliente,
		method: 'post',
		dataType: 'json',
		success: function(response){
			//Direcciones fiscales
			appendDireccionesFiscales(response.direccionesFiscales);
		},
		error: function(){
			alert("Ha ocurrido un error al intentar consultar el cliente seleccionado. Intente de nuevo, por favor.")
		}
	});
}

//Cambio apariencia de botón de activo e inactivo comparando el valor del input de estado activo del formulario de edición
function checkEstadoActivo(){
	var estadoActivo = $("#form-edita input[name='estadoActivo']").val();

	if(estadoActivo == 1){ 
		$("#form-edita #btn-estado").removeClass("btn-success").addClass("btn-danger").html("Inactivar");
		$("#form-edita #labelEstado").html("Estado: Activo");
	}else if(estadoActivo == 0){ 
		$("#form-edita #btn-estado").removeClass("btn-danger").addClass("btn-success").html("Activar");
		$("#form-edita #labelEstado").html("Estado: Inactivo");
	}

}

$(function(){
	//Evento de click en botón de activo e inactivo de cliente
	$("#btn-estado").click(function(event){
		event.preventDefault();
		var inpEstadoActivo = $("#form-edita input[name='estadoActivo']");

		if(inpEstadoActivo.val() == 1) inpEstadoActivo.val(0); 
		else if(inpEstadoActivo.val() == 0) inpEstadoActivo.val(1);

		checkEstadoActivo(); 
	});

	//********************** Fin de acciones de botón de activo e inactivo 

	//********************** Eventos submit de formularios

	$("#form_alta").submit(function(event){
		event.preventDefault();
		var nombreComercial = $("#form_alta input[name='nombre']:visible").val();

		$.ajax({
			url: pageController+'/nuevoCliente_AJAX',
			method: 'post',
			dataType: 'json',
			data: {'nombre' : nombreComercial},
			success: function(response){
				if(response.status == "OK") {
					$("#cCliente *").remove();
					$("#form_alta input[name='nombre']:visible").val("");
					var clientes = $("#cCliente");

					clientes.append("<option value='-1'>Ninguno</option>");
					for(var k = 0; k < response.data.length; k++){
						clientes.append("<option value='"+response.data[k].id+"'>"+response.data[k].nombre+"</option>");
					}

					alert("Operación realizada con éxito.");
				}else alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			},
			error: function(){
				alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			}
		});
	});

	$("#form-edita").submit(function(event){
		event.preventDefault();
		var nombreComercial = $("#form-edita input[name='nombre']:visible").val();
		var estadoActivo = $("#form-edita input[name='estadoActivo']").val();
		var id = $("#form-edita input[name='id']").val();

		$.ajax({
			url: pageController+'/editarCliente_AJAX',
			method: 'post',
			dataType: 'json',
			data: {'nombre' : nombreComercial, 'estadoActivo' : estadoActivo, 'id' : id},
			success: function(response){
				if(response.status == "OK") {
					$("#cCliente option:selected").html(nombreComercial);

					alert("Operación realizada con éxito.");
				}else alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			},
			error: function(){
				alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			}					
		});
	});


	//************************* Fin de eventos de submit de formularios


	//************************* Acciones iniciales esenciales
	checkEstadoActivo();
	putMainInformation();
	$("#main-info-financiera").show();
});
