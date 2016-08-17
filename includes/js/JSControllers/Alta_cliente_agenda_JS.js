function appendContactos(appendSection, data = null){
	var form = $("#sc-agenda").clone(true);
	form.find("form").unbind("submit");

	if(data == null){
		form.attr("id", "sc_new_contacto");
		form.find("#sc-actions-contacto").hide();
		form.find("form").submit(function(event){
			event.preventDefault();
			submitNewContact($(this));
		});
	}else{
		form.attr("id", "sc_edit_contacto_"+appendSection.find("form").length);
		form.attr("id-padre", data.id);

		form.find("#nombre").val(data.nombre);
		form.find("#apellido").val(data.apellido);
		form.find("#lada").val(data.lada);
		form.find("#telefono").val(data.telefono);
		form.find("#extension").val(data.extension);
		form.find("#idTipoContacto").val(data.idTipoContacto);
		form.find("#sc-data-detail").hide();

		form.find("form").submit(function(event){
			event.preventDefault();
			submitEditContact($(this));
		});

		form.find("#btn-ver-detalle-contacto").click(function(event){
			form.find("#sc-data-detail").toggle();
			event.preventDefault();
		});

		form.find("#btn-eliminar-contacto").click(function(event){
			deleteContact($(this));
			event.preventDefault();
		});
	}
	
	form.show();
	form.appendTo(appendSection);

}

function submitNewContact(element){
	var form = element;
	var section = form.closest("section.form-agenda-section");
	var nombre = form.find("#nombre").val();
	var apellido = form.find("#apellido").val();
	var lada = form.find("#lada").val();
	var telefono = form.find("#telefono").val();
	var extension = form.find("#extension").val();
	var idTipoContacto = form.find("#idTipoContacto").val();
	var idPadre = $("#cCliente").val();

	$.ajax({
		url: pageController+'/nuevoContacto_AJAX',
		method: 'post',
		data: {'nombre':nombre, 'apellido':apellido, 'lada':lada, 'telefono':telefono, 'extension':extension, 'idPadre':idPadre, 'idTipoContacto':idTipoContacto},
		dataType: 'json',
		success: function(response){
			alert("Se ha realizado correctamente la operación.");
			form.find("#sc-actions-contacto").show();
			form.find("#sc-data-detail").hide();

			form.find("#btn-ver-detalle-contacto").click(function(event){
				form.find("#sc-data-detail").toggle();
				event.preventDefault();
			});

			form.find("#btn-eliminar-contacto").click(function(event){
				deleteContact($(this));
				event.preventDefault();
			});

			form.unbind('submit');
			form.submit(function(event){
				event.preventDefault();
				submitEditContact($(this));
			});

			section.attr("id-padre", response.data);
			section.appendTo($("#existent-section-contacto"));
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function submitEditContact(element){
	var form = element;
	var section = form.parent().parent();
	var nombre = form.find("#nombre").val();
	var apellido = form.find("#apellido").val();
	var lada = form.find("#lada").val();
	var telefono = form.find("#telefono").val();
	var extension = form.find("#extension").val();
	var idTipoContacto = form.find("#idTipoContacto").val();
	var idPadre = section.attr("id-padre");

	$.ajax({
		url: pageController+'/editarContacto_AJAX/'+idPadre,
		method: 'post',
		data: {'nombre':nombre, 'apellido':apellido, 'lada':lada, 'telefono':telefono, 'extension':extension, 'idTipoContacto':idTipoContacto},
		dataType: 'json',
		success: function(response){
			alert("Contacto actualizado.");
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function deleteContact(element){
	var form = element;
	var section = form.closest('section.form-agenda-section');
	var id = section.attr('id-padre');

	if(confirm("¿Está seguro que desea eliminar este contacto de su lista?")){
		$.ajax({
			url: pageController+'/eliminarContacto_AJAX/'+id,
			dataType: 'json',
			success: function(response){
				section.remove();
			},
			error: function(){
				alert("Ha sucedido un error. Intente de nuevo, por favor.");
			}
		});
	}
}

function putContacts(){
	var idPadre = $("#cCliente").val();
	$("#existent-section-contacto").find("*").remove();

	$.ajax({
		url: pageController+'/traerContactos_AJAX/'+idPadre,
		method: 'post',
		dataType: 'json',
		success: function(response){
			for(var k = 0, n = response.data.length; k<n; k++){
				appendContactos($("#existent-section-contacto"), response.data[k]);
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

$(function(){
	$("#btn-agrega-contacto").click(function(event){
		event.preventDefault();
		var appendSection = $("#append-section-contacto");
		appendSection.find("*").remove();

		appendContactos(appendSection);
	});
});