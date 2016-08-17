function appendBancosAsociados(data){
	$("#existent-section-banco").find("*").remove();

	for(var k=0, n=data.length; k<n; k++)
		appendFormBanco($("#existent-section-banco"), data[k]);
}

function appendFormBanco(appendSection, data = null){
	var form = $("#sc-banco").clone(true);

	form.attr("id", "sc-banco-appended");
	form.unbind('submit');

	if(data != null){
		// Se trata de un formulario para edición
		form.submit(function(event){
			submitEditBanco(event, $(this));
		});

		form.attr('id-banco', data.id);
		form.find("#nombre").val(data.nombre);
		form.find("#idBanco").val(data.idBanco);
		form.find("#estadoActivo").val(data.estadoActivo);
		form.find("#sucursal").val(data.sucursal);
		form.find("#clabe").val(data.clabe);
		form.find("#cuenta").val(data.cuenta);
		form.find("#sc-banco-detail").hide();
		traerDireccionesFiscalesBanco(form, data.idDireccionFiscal);

	}else{
		// Se trata de un formulario para creación
		form.submit(function(event){
			submitNewBanco(event, $(this));
		});

		traerDireccionesFiscalesBanco(form);
		form.find("#btn-ver-detalle-banco").hide();
		form.find("#sc-banco-detail").show();		
	
	}

	form.find("#btn-ver-detalle-banco").click(function(event){
		event.preventDefault();
		$(this).closest("form").find("#sc-banco-detail").toggle();
	});

	form.css("display", "inherit");
	form.appendTo(appendSection);
}

function traerDireccionesFiscalesBanco(currentForm, currentSelection = null){
	var idPadre = $("#cCliente").val();
	var form = currentForm;
	var select = form.find("#idDireccionFiscal");

	$.ajax({
		url: pageController+'/traerDireccionesFiscales_AJAX/'+idPadre,
		method: 'post',
		dataType: 'json',
		success: function(response){
			select.find("*").remove();
			select.append("<option value='-1'>Seleccione una opción</option>");
			for(var k=0, n=response.length; k<n; k++){
				select.append("<option value="+response[k].id+">"+response[k].razonSocial+"</option>");
			}

			//Recuperar valor
			if(currentSelection != null) select.val(currentSelection);
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function putBancos(){
	var current = $("#cCliente").val();

	$.ajax({
		url: pageController+'/traerBancos_AJAX/'+current,
		method: 'post',
		dataType: 'json',
		success: function(response){
			appendBancosAsociados(response.data);
		},
		error: function(){
			alert("Error.");
		}
	});
}

function submitNewBanco(event, element){
	event.preventDefault();

	var form = element;
	var nombre = form.find("#nombre").val();
	var idBanco = form.find("#idBanco").val();
	var estadoActivo = form.find("#estadoActivo").val();
	var sucursal = form.find("#sucursal").val();
	var clabe = form.find("#clabe").val();
	var cuenta = form.find("#cuenta").val();
	var idDireccionFiscal = form.find("#idDireccionFiscal").val();

	$.ajax({
		url: pageController+"/nuevoBanco_AJAX",
		method: 'post',
		dataType: 'json',
		data: { 'nombre':nombre, 'idBanco':idBanco, 'estadoActivo':estadoActivo, 'sucursal':sucursal, 'clabe':clabe, 'cuenta':cuenta, 'idDireccionFiscal':idDireccionFiscal },
		success: function(response){
			if( response.status == "OK" ){
				alert("Operación realizada con éxito.");
				
				form.unbind('submit');
				form.submit(function(event){
					submitEditBanco(event, $(this));
				});

				form.attr('id-banco', response.data);
				form.find("#btn-ver-detalle-banco").show();
				form.find("#sc-banco-detail").hide();
				form.appendTo("#existent-section-banco");
			}else
				alert("Ha ocurrido un error. Por favor, intente de nuevo.");
		},
		error: function(){
			alert("Ha ocurrido un error. Por favor, intente de nuevo.");
		}
	});
}

function submitEditBanco(event, element){
	event.preventDefault();

	var form = element;
	var id = element.attr('id-banco');
	var nombre = form.find("#nombre").val();
	var idBanco = form.find("#idBanco").val();
	var estadoActivo = form.find("#estadoActivo").val();
	var sucursal = form.find("#sucursal").val();
	var clabe = form.find("#clabe").val();
	var cuenta = form.find("#cuenta").val();
	var idDireccionFiscal = form.find("#idDireccionFiscal").val();

	$.ajax({
		url: pageController+"/editarBanco_AJAX/"+id,
		method: 'post',
		dataType: 'json',
		data: { 'nombre':nombre, 'idBanco':idBanco, 'estadoActivo':estadoActivo, 'sucursal':sucursal, 'clabe':clabe, 'cuenta':cuenta, 'idDireccionFiscal':idDireccionFiscal },
		success: function(response){
			if( response.status == "OK" ){
				alert("Operación realizada con éxito.");
			}else
				alert("Ha ocurrido un error. Por favor, intente de nuevo.");
		},
		error: function(){
			alert("Ha ocurrido un error. Por favor, intente de nuevo.");
		}
	});
}

$(function(){
	$("#btn-agrega-banco").click(function(event){
		event.preventDefault();

		var appendSection = $("#append-section-banco");
		appendSection.find("*").remove();

		appendFormBanco(appendSection);
	});

	$(".idDireccionFiscal").click(function(){
		var form = $(this).closest("form");
		traerDireccionesFiscalesBanco(form, $(this).val());
	});
});