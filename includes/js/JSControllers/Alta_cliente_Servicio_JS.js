function appendServicio(appendSection, data = null){
	var section = $("#sc-servicio").clone(true);
	var form = section.find("form.form_servicio");
	form.unbind("submit");

	form.find("#btn-eliminar-servicio").click(function(event){
		event.preventDefault();
		removeServicio(form, section);
	});

	if(data == null){
		appendSection.find("*").remove();
		
		form.attr("id", "form-new-servicio");
		form.find("#btn-eliminar-servicio").hide();
		form.submit(function(event){
			event.preventDefault();
			newServicio($(this), section);
		});

		section.attr("id", "sc-new-servicio");
	}else{
		form.attr("id", "form-edit-servicio");
		form.find("#idServicio").val(data.idServicio);
		form.submit(function(event){
			event.preventDefault();
			editServicio($(this), section);
		});

		section.attr("id-to-edit", data.id);
		section.attr("id", "sc-edit-servicio-"+appendSection.find("section").length);
	}

	section.show();
	section.appendTo(appendSection);
}

function newServicio(form, section){
	var idPadre = $("#cCliente").val();
	var idServicio = form.find("#idServicio").val();

	$.ajax({
		url: pageController+'/nuevoServicio_AJAX',
		method: 'post',
		data: {'idPadre':idPadre, 'idServicio':idServicio},
		dataType: 'json',
		success: function(response){
			alert("Servicio agregado.");

			form.unbind('submit');
			form.submit(function(event){
				event.preventDefault();
				editServicio(form, section);
			});
			form.find("#btn-eliminar-servicio").show().click(function(event){
				event.preventDefault();
				removeServicio(form, section);
			});

			section.attr("id-to-edit", response.data);
			section.appendTo($("#existent-section-servicio"));
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function editServicio(form, section){
	var id = section.attr('id-to-edit');
	var idServicio = form.find("#idServicio").val();

	$.ajax({
		url: pageController+'/editarServicio_AJAX/'+id,
		method: 'post',
		data: {'idServicio':idServicio},
		dataType: 'json',
		success: function(response){
			alert("Servicio actualizado.");
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function removeServicio(form, section){
	var id = section.attr('id-to-edit');

	$.ajax({
		url: pageController+'/eliminarServicio_AJAX/'+id,
		method: 'post',
		dataType: 'json',
		success: function(response){
			section.remove();
			alert("Servicio eliminado.");
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function putServicios(){
	var current = $("#cCliente").val();
	var appendSection = $("#existent-section-servicio");
	appendSection.find("*").remove();

	$.ajax({
		url: pageController+'/traerServicios_AJAX/'+current,
		dataType: 'json',
		method: 'post',
		success: function(response){
			for(var k=0, n=response.data.length; k<n; k++)
				appendServicio(appendSection, response.data[k]);
		},
		error: function(){
			alert("Error al intentar consultar servicios asocidados. Intente recargando la página, por favor.");
		}
	});
}

$(function(){

	$("#btn-agrega-servicio").click(function(event){
		event.preventDefault();
		appendServicio($("#append-section-servicio"));
	});

});