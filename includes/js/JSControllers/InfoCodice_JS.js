function retrieveInfoCodice() {
	$.ajax({
		url: baseURL+"index.php/Control_info_codice_ctrl/retrieveAll",
		method: 'post',
		dataType: 'json',
		success: function(response){
			var k, n;
			var appendSection = $("#appendSection");
			var clonedSection = null;

			appendSection.find("*").remove();

			for(k = 0, n = response.length; k < n; k++){
				clonedSection = $("#toCloneSection").clone();

				clonedSection.attr("id", "clonedSection_"+k);
				clonedSection.find("form").attr("data-id", response[k].id).submit(function(event){
					event.preventDefault();
					updateInfoCodice($(this));
				});

				clonedSection.find("#razonSocial").val(response[k].razonSocial);
				clonedSection.find("#rfc").val(response[k].rfc);
				clonedSection.find("#domicilioFiscal").val(response[k].domicilioFiscal);
				clonedSection.find("#regimenFiscal").val(response[k].regimenFiscal);

				clonedSection.show();
				clonedSection.appendTo(appendSection);
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function updateInfoCodice(sender){
	var id = sender.attr("data-id");
	var razonSocial = sender.find("#razonSocial").val();
	var rfc = sender.find("#rfc").val();
	var domicilioFiscal = sender.find("#domicilioFiscal").val();
	var regimenFiscal = sender.find("#regimenFiscal").val();

	$.ajax({
		url: baseURL+"index.php/Control_info_codice_ctrl/update/"+id,
		method: 'post',
		data: { 'razonSocial': razonSocial, 'rfc': rfc, 'domicilioFiscal': domicilioFiscal, 'regimenFiscal': regimenFiscal },
		success: function(response){
			alert("Información atualizada.");
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function createInfoCodice(sender){
	var razonSocial = sender.find("#razonSocial").val();
	var rfc = sender.find("#rfc").val();
	var domicilioFiscal = sender.find("#domicilioFiscal").val();
	var regimenFiscal = sender.find("#regimenFiscal").val();

	$.ajax({
		url: baseURL+"index.php/Control_info_codice_ctrl/create",
		method: 'post',
		data: { 'razonSocial': razonSocial, 'rfc': rfc, 'domicilioFiscal': domicilioFiscal, 'regimenFiscal': regimenFiscal },
		success: function(response){
			alert("Información nueva asociada exitosamente.");
			sender.find("input").val("");
			sender.find("input[type='submit']").val("Crear");

			retrieveInfoCodice();
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

$(function(){
	retrieveInfoCodice();

	$("#create-form").submit(function(event){
		event.preventDefault();

		createInfoCodice($(this));
	});
});