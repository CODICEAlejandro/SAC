function appendProfile(appendSection, data = null){
	var section = $("#sc-perfil").clone(true);
	var form = section.find("form.form_perfil");
	form.unbind("submit");

	form.find("#btn-eliminar-perfil").click(function(event){
		event.preventDefault();
		removeProfile(form, section);
	});

	if(data == null){
		appendSection.find("*").remove();
		
		form.attr("id", "form-new-profile");
		form.find("#btn-eliminar-perfil").hide();
		form.submit(function(event){
			event.preventDefault();
			newProfile($(this), section);
		});

		section.attr("id", "sc-new-profile");
	}else{
		form.attr("id", "form-edit-profile");
		form.find("#idPerfil").val(data.idPerfil);
		form.submit(function(event){
			event.preventDefault();
			editProfile($(this), section);
		});

		section.attr("id-to-edit", data.id);
		section.attr("id", "sc-edit-profile-"+appendSection.find("section").length);
	}

	section.show();
	section.appendTo(appendSection);
}

function newProfile(form, section){
	var idPadre = $("#cCliente").val();
	var idPerfil = form.find("#idPerfil").val();

	$.ajax({
		url: pageController+'/nuevoPerfil_AJAX',
		method: 'post',
		data: {'idPadre':idPadre, 'idPerfil':idPerfil},
		dataType: 'json',
		success: function(response){
			alert("Perfil agregado.");

			form.unbind('submit');
			form.submit(function(event){
				event.preventDefault();
				editProfile(form, section);
			});
			form.find("#btn-eliminar-perfil").show().click(function(event){
				event.preventDefault();
				removeProfile(form, section);
			});

			section.attr("id-to-edit", response.data);
			section.appendTo($("#existent-section-perfil"));
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function editProfile(form, section){
	var id = section.attr('id-to-edit');
	var idPerfil = form.find("#idPerfil").val();

	$.ajax({
		url: pageController+'/editarPerfil_AJAX/'+id,
		method: 'post',
		data: {'idPerfil':idPerfil},
		dataType: 'json',
		success: function(response){
			alert("Perfil actualizado.");
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function removeProfile(form, section){
	var id = section.attr('id-to-edit');

	$.ajax({
		url: pageController+'/eliminarPerfil_AJAX/'+id,
		method: 'post',
		dataType: 'json',
		success: function(response){
			section.remove();
			alert("Perfil eliminado.");
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function putProfiles(){
	var current = $("#cCliente").val();
	var appendSection = $("#existent-section-perfil");
	appendSection.find("*").remove();

	$.ajax({
		url: pageController+'/traerPerfiles_AJAX/'+current,
		dataType: 'json',
		method: 'post',
		success: function(response){
			for(var k=0, n=response.data.length; k<n; k++)
				appendProfile(appendSection, response.data[k]);
		},
		error: function(){
			alert("Error al intentar consultar perfiles asocidados. Intente recargando la página, por favor.");
		}
	});
}

$(function(){

	$("#btn-agrega-perfil").click(function(event){
		event.preventDefault();
		appendProfile($("#append-section-perfil"));
	});

});