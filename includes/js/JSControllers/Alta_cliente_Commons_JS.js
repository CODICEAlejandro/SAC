//************************* Appends

function appendDireccionesFiscales(data, removeActual = true){
	var formulario = null;
	var appendSection = $("#existent-section-direccion-fiscal");
	var currentElement = 0;

	if(removeActual){
		appendSection.find("*").remove();
	}

	for(var k=0; k<data.length; k++){
		currentElement = k + 1;
		formulario = $("#sc-informacion-fiscal").clone(true);

		//Editar atributos de particularidad del formulario
		formulario.attr("id", "sc-informacion-fiscal-"+currentElement );
		
		formulario.find(".form_direccion_fiscal").attr("id-direccion-fiscal", data[k].id);
		formulario.find(".form_direccion_fiscal").find("#sc-description-direccion-fiscal").hide();
		formulario.find(".form_direccion_fiscal").submit(function(event){
			submitEdit_DireccionFiscal(event, $(this));
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

function appendBancosAsociados(data){
	$("#existent-section-banco").find("*").remove();

	for(var k=0, n=data.length; k<n; k++)
		appendFormBanco($("#existent-section-banco"), data[k]);
}

$(function(){

	$("#btn-bancos-direcciones").click(function(event){
		event.preventDefault();
		$("#main-info-financiera").show();
		$("#main-agenda").hide();

		$("#main-menu-cliente li").removeClass("active");
		$(this).parent().addClass("active");
		checkCCliente();
	});

	$("#btn-agenda").click(function(event){
		event.preventDefault();
		$("#main-info-financiera").hide();
		$("#main-agenda").show();

		$("#main-menu-cliente li").removeClass("active");
		$(this).parent().addClass("active");
		checkCCliente();
	});


	$(".idPais").change(function(){
		var currentCountry = $(this).val();
		var stateSelect = $(this).closest("form").find("#idEstado");
		var citySelect = $(this).closest("form").find("#idCiudad");
		citySelect.find("*").remove();
		stateSelect.find("*").remove();

		$.ajax({
			url: 'Alta_cliente_ctrl/traerEstados_AJAX/'+currentCountry,
			method: 'post',
			dataType: 'json',
			async: false,
			success: function(response){
				var data = response.data;

				stateSelect.append("<option value='-1'>Ninguno</option>");
				citySelect.append("<option value='-1'>Ninguna</option>");
				for(var k=0, n=data.length; k<n; k++){
					stateSelect.append("<option value="+data[k].id+">"+data[k].nombre+"</option>");
				}
			},
			error: function(){
				alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			}
		});
	});

	$(".idEstado").change(function(){
		var currentState = $(this).val();
		var citySelect = $(this).closest("form").find("#idCiudad");
		citySelect.find("*").remove();

		$.ajax({
			url: 'Alta_cliente_ctrl/traerCiudades_AJAX/'+currentState,
			method: 'post',
			dataType: 'json',
			async: false,
			success: function(response){
				var data = response.data;

				citySelect.append("<option value='-1'>Ninguna</option>");
				for(var k=0, n=data.length; k<n; k++)
					citySelect.append("<option value="+data[k].id+">"+data[k].nombre+"</option>");
			},
			error: function(){
				alert("Ha ocurrido un error. Intente de nuevo, por favor.");
			}
		});
	});

});