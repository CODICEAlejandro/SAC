function appendDireccionesOperativas(data, removeActual = true){
	var formulario = null;
	var appendSection = $("#existent-section-direccion-operativa");
	var currentElement = 0;

	if(removeActual){
		appendSection.find("*").remove();
	}

	for(var k=0; k<data.length; k++){
		currentElement = k + 1;
		formulario = $("#sc-informacion-operativa").clone(true);

		//Editar atributos de particularidad del formulario
		formulario.attr("id", "sc-informacion-operativa-"+currentElement );
		
		formulario.find(".form_direccion_operativa").attr("id-direccion-operativa", data[k].id);
		formulario.find(".form_direccion_operativa").find("#sc-description-direccion-operativa").hide();
		formulario.find(".form_direccion_operativa").submit(function(event){
			submitEdit_DireccionOperativa(event, $(this));
		});

		formulario.css("display", "inherit");
		
		formulario.find("#razonSocial").val(jEntityDecode(data[k].razonSocial));
		formulario.find("#estadoActivo").val(jEntityDecode(data[k].estadoActivo));
		formulario.find("#calle").val(jEntityDecode(data[k].calle));
		formulario.find("#numero").val(jEntityDecode(data[k].numero));
		formulario.find("#colonia").val(jEntityDecode(data[k].colonia));
		formulario.find("#idPais").val(data[k].pais).change();
		formulario.find("#idEstado").val(data[k].estado).change();
		formulario.find("#idCiudad").val(jEntityDecode(data[k].ciudad));
		formulario.find("#cp").val(jEntityDecode(data[k].cp));
		formulario.find("#rfc").val(jEntityDecode(data[k].rfc));

		formulario.appendTo(appendSection);
	}
}

function putDireccionesOperativas(){
	var current = $("#cCliente").val();

	$.ajax({
		url: pageController+'/traerDireccionesOperativas_AJAX/'+current,
		method: 'post',
		dataType: 'json',
		success: function(response){
			appendDireccionesOperativas(response.data);
		},
		error: function(){
			alert("Error.");
		}
	});
}

function submitEdit_DireccionOperativa(event, element){
	event.preventDefault();

	var form = element;
	var id = form.attr("id-direccion-operativa");
	var estadoActivo = form.find("#estadoActivo").val();
	var calle = form.find("#calle").val();
	var numero = form.find("#numero").val();
	var colonia = form.find("#colonia").val();
	var ciudad = form.find("#idCiudad").val();
	var estado = form.find("#idEstado").val();
	var pais = form.find("#idPais").val();
	var cp = form.find("#cp").val();

	$.ajax({
		url: pageController+'/editarDireccionOperativa_AJAX/'+id,
		data: { 'estadoActivo' : estadoActivo,
				'calle' : calle,
				'numero' : numero,
				'colonia' : colonia,
				'idCiudad' : ciudad,
				'idEstado' : estado,
				'idPais' : pais,
				'cp' : cp
			},
		method: 'post',
		dataType: 'json',
		success: function(response){
			if(response.status == "OK") alert("Dirección actualizada.");
			else alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});

}

function verDetalleDireccionOperativa(event, element){
	event.preventDefault();

	var form = element.closest("form");
	var section = form.find("#sc-description-direccion-operativa");

	if(section.is(":visible")) section.hide();
	else section.show();
}

//********************** Fin de acciones de formulario de dirección operativa

//********************** Eventos submit de formularios

function submitNew_DireccionOperativa(event, element){
	event.preventDefault();

	var cCliente = $("#cCliente").val();

	var form = element;
	var estadoActivo = form.find("#estadoActivo").val();
	var calle = form.find("#calle").val();
	var numero = form.find("#numero").val();
	var colonia = form.find("#colonia").val();
	var ciudad = form.find("#idCiudad").val();
	var estado = form.find("#idEstado").val();
	var pais = form.find("#idPais").val();
	var cp = form.find("#cp").val();

	$.ajax({
		url: pageController+'/nuevaDireccionOperativa_AJAX',
		data: { 'estadoActivo' : estadoActivo,
				'calle' : calle,
				'numero' : numero,
				'colonia' : colonia,
				'idCiudad' : ciudad,
				'idEstado' : estado,
				'idPais' : pais,
				'cp' : cp,
				'idPadre' : cCliente
			},
		method: 'post',
		dataType: 'json',
		success: function(response){
			if(response.status == "OK"){
				form.parent().appendTo("#existent-section-direccion-operativa");
				form.attr("id-direccion-operativa", response.data.id);
				form.find("#btn-ver-direccion-operativa").show();

				form.unbind("submit");
				form.bind('submit', function(event){
					submitEdit_DireccionOperativa(event, $(this));
				});
				form.find("#sc-description-direccion-operativa").hide();

				alert("Dirección guardada.");
			}else alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});

}

function submitEdit_DireccionOperativa(event, element){
	event.preventDefault();

	var form = element;
	var id = form.attr("id-direccion-operativa");
	var estadoActivo = form.find("#estadoActivo").val();
	var calle = form.find("#calle").val();
	var numero = form.find("#numero").val();
	var colonia = form.find("#colonia").val();
	var ciudad = form.find("#idCiudad").val();
	var estado = form.find("#idEstado").val();
	var pais = form.find("#idPais").val();
	var cp = form.find("#cp").val();

	$.ajax({
		url: pageController+'/editarDireccionOperativa_AJAX/'+id,
		data: { 'estadoActivo' : estadoActivo,
				'calle' : calle,
				'numero' : numero,
				'colonia' : colonia,
				'idCiudad' : ciudad,
				'idEstado' : estado,
				'idPais' : pais,
				'cp' : cp
			},
		method: 'post',
		dataType: 'json',
		success: function(response){
			if(response.status == "OK") alert("Dirección actualizada.");
			else alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});

}

$(function(){

	//********************** Acciones de formulario de dirección operativa

	$("#btn-agrega-direccion-operativa").click(function(event){
		event.preventDefault();

		var formulario = $("#sc-informacion-operativa").clone(true);
		var appendSection = $("#append-section-direccion-operativa");
		var currentElement = appendSection.find("section.form-informacion-operativa").length + 1;

		appendSection.find("*").remove();

		//Editar atributos de particularidad del formulario
		formulario.attr("id", "sc-informacion-operativa-"+currentElement );
		formulario.css("display", "inherit");
		formulario.find("#btn-ver-direccion-operativa").hide();
		formulario.find(".form_direccion_operativa").submit(function(event){
			submitNew_DireccionOperativa(event, $(this));
		});

		formulario.appendTo(appendSection);
	});

	$("#btn-ver-direccion-operativa").click(function(event){
		verDetalleDireccionOperativa(event, $(this));
	});

	//************************* Fin de eventos de submit de formularios
});
