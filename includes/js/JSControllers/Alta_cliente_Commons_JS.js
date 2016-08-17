$(function(){

	$("#btn-direcciones-fiscales").click(function(event){
		event.preventDefault();
		$("#main-direccion-fiscal").show();
		$("#main-direccion-operativa").hide();
		$("#main-banco").hide();
		$("#main-agenda").hide();
		$("#main-perfiles").hide();
		$("#main-servicios").hide();

		$("#main-menu-cliente li").removeClass("active");
		$(this).parent().addClass("active");
		checkCCliente();
	});

	$("#btn-direcciones-operativas").click(function(event){
		event.preventDefault();
		$("#main-direccion-fiscal").hide();
		$("#main-direccion-operativa").show();
		$("#main-banco").hide();
		$("#main-agenda").hide();
		$("#main-perfiles").hide();
		$("#main-servicios").hide();

		$("#main-menu-cliente li").removeClass("active");
		$(this).parent().addClass("active");
		putDireccionesOperativas();
	});

	$("#btn-bancos").click(function(event){
		event.preventDefault();
		$("#main-direccion-fiscal").hide();
		$("#main-direccion-operativa").hide();
		$("#main-banco").show();
		$("#main-agenda").hide();
		$("#main-perfiles").hide();
		$("#main-servicios").hide();

		$("#main-menu-cliente li").removeClass("active");
		$(this).parent().addClass("active");
		putBancos();
	});

	$("#btn-agenda").click(function(event){
		event.preventDefault();
		$("#main-direccion-fiscal").hide();
		$("#main-direccion-operativa").hide();
		$("#main-banco").hide();
		$("#main-agenda").show();
		$("#main-perfiles").hide();
		$("#main-servicios").hide();

		$("#main-menu-cliente li").removeClass("active");
		$(this).parent().addClass("active");
		putContacts();
	});

	$("#btn-perfiles").click(function(event){
		event.preventDefault();
		$("#main-direccion-fiscal").hide();
		$("#main-direccion-operativa").hide();
		$("#main-banco").hide();
		$("#main-agenda").hide();
		$("#main-perfiles").show();
		$("#main-servicios").hide();

		$("#main-menu-cliente li").removeClass("active");
		$(this).parent().addClass("active");
		putProfiles();
	});

	$("#btn-servicios").click(function(event){
		event.preventDefault();
		$("#main-direccion-fiscal").hide();
		$("#main-direccion-operativa").hide();
		$("#main-banco").hide();
		$("#main-agenda").hide();
		$("#main-perfiles").hide();
		$("#main-servicios").show();

		$("#main-menu-cliente li").removeClass("active");
		$(this).parent().addClass("active");
		putServicios();
	});

	//Evento de cambio en combo de clientes
	$("#cCliente").change(function(){
		putMainInformation();

		if( $("#btn-direcciones-fiscales").parent().hasClass("active") ) checkCCliente();
		else if( $("#btn-bancos").parent().hasClass("active") ) putBancos();
		else if( $("#btn-direcciones-operativas").parent().hasClass("active") ) putDireccionesOperativas();
		else if( $("#btn-agenda").parent().hasClass("active") ) putContacts();
		else if( $("#btn-perfiles").parent().hasClass("active") ) putProfiles();
		else if( $("#btn-servicios").parent().hasClass("active") ) putServicios();
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