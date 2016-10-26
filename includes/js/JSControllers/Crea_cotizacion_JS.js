function reenumerarConceptos(){
		var appendSection = $("#containerConceptos");
		var conceptos = appendSection.find(".rowConcepto");

		conceptos.each(function(index){
			$(this).find("#numeroConcepto").html("Concepto "+(index+1));
		});	
}



$(function(){

	initDatepicker("input[name='inicioProyecto']", "input[name='inicioProyectoAlt']", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("input[name='finProyecto']", "input[name='finProyectoAlt']", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("input[name='fechaJuntaArranque']", "input[name='fechaJuntaArranqueAlt']", "dd/mm/yy", "yy-mm-dd");
	initDatepicker("input[name='fechaVenta']", "input[name='fechaVentaAlt']", "dd/mm/yy", "yy-mm-dd");

	$("#btn-agrega-concepto").click(function(event){
		var cloneSection = $("#rowConcepto").clone(true);
		var appendSection = $("#containerConceptos");
		var numberChildren = appendSection.find(".rowConcepto").length;

		cloneSection.find("#numeroConcepto").html("Concepto "+(numberChildren+1));
		cloneSection.attr("id", "rowConcepto"+numberChildren);
		cloneSection.appendTo(appendSection);
		cloneSection.find("#periodo").hide();
		cloneSection.show();

		cloneSection.find("#btn-borrar-concepto").click(function(event){
			cloneSection.remove();
			reenumerarConceptos();
			event.preventDefault();
		});

		cloneSection.find("#recYes").click(function(){
			var periodSection = cloneSection.find("#periodo");

			if(!$(this).is(":checked")){
				cloneSection.find("#recNo").prop("checked", true);
				periodSection.hide();
			}else{
				cloneSection.find("#recNo").prop("checked", false);
				periodSection.show();
			}
		});

		event.preventDefault();
	});

	$("select#cliente").change(function(){
		var appendSection = $("select[name='idRazonSocial']");
		var currentCliente = $(this).val();

		appendSection.find("*").remove();

		$.ajax({
			url: baseURL+"index.php/Crea_cotizacion_ctrl/traerDireccionesFiscales/"+currentCliente,
			method: 'post',
			dataType: 'json',
			success: function(response){
				var k, n;

				n = response.length;
				appendSection.append("<option value = '-1'>Seleccione una razón social</option>");
				for(k = 0; k < n; k++){
					appendSection.append("<option value = '"+response[k].id+"'>"+response[k].razonSocial+"</option>");
				}
			},
			error: function(){
				alert("Error al consultar base de datos. Intente de nuevo, por favor.");
			}
		});
	});

});