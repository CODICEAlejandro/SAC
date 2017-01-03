// ***************************
// ******** Triggers *********
// ***************************

function guardarFormulario(sender){
	var mainForm = sender;

	var cliente = mainForm.find("#cliente").val();
	var account = mainForm.find("#account").val();

	//Generar relaciones concepto-monto-fecha => [[concepto, monto, fecha], ...]
	var seccionesConcepto = $("#append-concepto-section .concepto-section");
	var conceptos = new Array();
	var concepto;
	var monto;
	var fecha;

	seccionesConcepto.each(function(index){
		concepto = $(this).find("#concepto").val();
		monto = $(this).find("#monto").val();
		fecha = $(this).find("input[name='fecha-alt']").val();

		conceptos.push(new Array(concepto, monto, fecha));
	});

	$.ajax({
		url: baseURL+'index.php/Facturacion/Captura_facturacion_ctrl/guardarConceptos',
		method: 'post',
		data: {'cliente': cliente, 'account': account, 'conceptos': conceptos},
		beforeSend: function(){
			$("#submit-main-form").hide();
		},
		success: function(response){
			alert("Operación realizada correctamente.");
			window.location.replace(baseURL+'index.php/Panel_control_ctrl');
		},
		error: function(){
			alert("Ha ocurrido un error. Intente de nuevo más tarde, por favor.");
			$("#submit-main-form").show();
		}
	});
}

// ***************************
// ******** Handlers *********
// ***************************

function submitMainForm(){
	$("#main-form").submit(function(event){
		event.preventDefault();

		guardarFormulario($(this));
	});
}


function clickCloneConceptoSection(){
	$("#btn-add-concepto").click(function(event){
		event.preventDefault();

		var clon = jCloneSection($("#clone-concepto-section"), $("#append-concepto-section"), true);
		var calendar = clon.find("#fecha");
		var calendarAlt = clon.find("#fecha-alt");

		jInitDatepicker(calendar, calendarAlt, "dd/mm/yy", "yy-mm-dd");
	});
}


$(function(){
	clickCloneConceptoSection();
	submitMainForm();
});