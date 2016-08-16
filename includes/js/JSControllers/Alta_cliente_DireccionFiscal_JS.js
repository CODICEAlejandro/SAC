function submitEdit_DireccionFiscal(event, element){
	event.preventDefault();

	var form = element;
	var id = form.attr("id-direccion-fiscal");
	var razonSocial = form.find("#razonSocial").val();
	var estadoActivo = form.find("#estadoActivo").val();
	var calle = form.find("#calle").val();
	var numero = form.find("#numero").val();
	var colonia = form.find("#colonia").val();
	var ciudad = form.find("#idCiudad").val();
	var estado = form.find("#idEstado").val();
	var pais = form.find("#idPais").val();
	var cp = form.find("#cp").val();
	var rfc = form.find("#rfc").val();

	$.ajax({
		url: 'Alta_cliente_ctrl/editarDireccionFiscal_AJAX/'+id,
		data: { 'razonSocial' : razonSocial,
				'estadoActivo' : estadoActivo,
				'calle' : calle,
				'numero' : numero,
				'colonia' : colonia,
				'idCiudad' : ciudad,
				'idEstado' : estado,
				'idPais' : pais,
				'cp' : cp,
				'rfc' : rfc
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

};


function submitNew_DireccionFiscal(event, element){
		event.preventDefault();

		var cCliente = $("#cCliente").val();

		var form = element;
		var razonSocial = form.find("#razonSocial").val();
		var estadoActivo = form.find("#estadoActivo").val();
		var calle = form.find("#calle").val();
		var numero = form.find("#numero").val();
		var colonia = form.find("#colonia").val();
		var ciudad = form.find("#idCiudad").val();
		var estado = form.find("#idEstado").val();
		var pais = form.find("#idPais").val();
		var cp = form.find("#cp").val();
		var rfc = form.find("#rfc").val();

		$.ajax({
			url: 'Alta_cliente_ctrl/nuevaDireccionFiscal_AJAX',
			data: { 'razonSocial' : razonSocial,
					'estadoActivo' : estadoActivo,
					'calle' : calle,
					'numero' : numero,
					'colonia' : colonia,
					'idCiudad' : ciudad,
					'idEstado' : estado,
					'idPais' : pais,
					'cp' : cp,
					'rfc' : rfc,
					'idPadre' : cCliente
				},
			method: 'post',
			dataType: 'json',
			success: function(response){
				if(response.status == "OK"){
					form.parent().appendTo("#existent-section-direccion-fiscal");
					form.attr("id-direccion-fiscal", response.data.id);
					form.find("#btn-ver-direccion-fiscal").show();

					form.unbind("submit");
					form.bind('submit', function(event){
						submitEdit_DireccionFiscal(event, $(this));
					});
					form.find("#sc-description-direccion-fiscal").hide();

					alert("Dirección guardada.");
				}else alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			},
			error: function(){
				alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			}
		});

}

$(function(){
	//********************** Acciones de formulario de dirección fiscal

	$("#btn-agrega-direccion-fiscal").click(function(event){
		event.preventDefault();

		var formulario = $("#sc-informacion-fiscal").clone(true);
		var appendSection = $("#append-section-direccion-fiscal");
		var currentElement = appendSection.find("section.form-informacion-fiscal").length + 1;

		appendSection.find("*").remove();

		//Editar atributos de particularidad del formulario
		formulario.attr("id", "sc-informacion-fiscal-"+currentElement );
		formulario.css("display", "inherit");
		formulario.find("#btn-ver-direccion-fiscal").hide();
		formulario.find(".form_direccion_fiscal").submit(function(event){
			submitNew_DireccionFiscal(event, $(this));
		});

		formulario.appendTo(appendSection);
	});

	$("#btn-ver-direccion-fiscal").click(function(event){
		verDetalleDireccionFiscal(event, $(this));
	});

	function verDetalleDireccionFiscal(event, element){
		event.preventDefault();

		var form = element.closest("form");
		var section = form.find("#sc-description-direccion-fiscal");

		if(section.is(":visible")) section.hide();
		else section.show();
	}

	//********************** Fin de acciones de formulario de dirección fiscal
});