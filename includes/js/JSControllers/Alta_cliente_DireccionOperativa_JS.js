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
			url: 'Alta_cliente_ctrl/nuevaDireccionOperativa_AJAX',
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

	};

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
			url: 'Alta_cliente_ctrl/editarDireccionOperativa_AJAX/'+id,
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

	};


	//************************* Fin de eventos de submit de formularios
});
