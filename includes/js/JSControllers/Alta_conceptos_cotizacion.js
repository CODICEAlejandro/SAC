function retrieveRazonesSociales(idCliente){
	var k, n;
	var appendSection = $("#id-razon-social");
	appendSection.find("*").remove();

	$.ajax({
		url: baseURL+"index.php/Alta_conceptos_cotizacion_ctrl/getRazonesSociales/"+idCliente,
		method: 'post',
		dataType: 'json',
		success: function(response){
			appendSection.append("<option value='-1'>Ninguna</option>");
			for(k=0, n=response.length; k<n; k++){
				appendSection.append("<option value='"+response[k].id+"'>"+response[k].razonSocial+"</option>");
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});
}

function retrieveCotizaciones(idRazonSocial){
	var k, n;
	var appendSection = $("#id-cotizacion");
	appendSection.find("*").remove();

	$.ajax({
		url: baseURL+"index.php/Alta_conceptos_cotizacion_ctrl/getCotizaciones/"+idRazonSocial,
		method: 'post',
		dataType: 'json',
		success: function(response){
			appendSection.append("<option value='-1'>Ninguna</option>");
			for(k=0, n=response.length; k<n; k++){
				appendSection.append("<option value='"+response[k].id+"'>"+response[k].folio+"</option>");
			}
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo, por favor.");
		}
	});	
}

$(function(){
	$("#id-cliente").change(function(event){
		event.preventDefault();

		retrieveRazonesSociales($(this).val());
	});
	
	$("#id-razon-social").change(function(event){
		event.preventDefault();

		retrieveCotizaciones($(this).val());
	})

	$("#btn-add-concepto").click(function(event){
		event.preventDefault();

		var appendSection = $("#append-section-concepto");
		var cloneSection = $("#clone-section-concepto").clone(true);

		cloneSection.attr("id", "clone-section-concepto-"+appendSection.find(".clone-section-concepto").size());
		cloneSection.show();

		appendSection.append(cloneSection);
	});

	$("#id-cotizacion").change(function(){
		var cVal = $(this).val();

		if(cVal == -1){
			$("#folio-cotizacion").show();
		}else{
			$("#folio-cotizacion").hide();
		}
	});

	$("#btn-save-cotizacion").click(function(event){
		event.preventDefault();

		$("#main-form").submit();
	});

	initDatepicker("#fecha_junta_arranque", "#alt_fecha_junta_arranque", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fecha_venta", "#alt_fecha_venta", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fecha_inicio_proyecto", "#alt_fecha_inicio_proyecto", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("#fecha_fin_proyecto", "#alt_fecha_fin_proyecto", "dd/mm/yy", "yy-mm-dd");
});