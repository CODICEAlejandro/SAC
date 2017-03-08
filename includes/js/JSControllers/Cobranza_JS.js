$(function(){
	$(".btn-pagar").click(function(event){
		event.preventDefault();

		var sender = $(this);
		var idFechaFactura = sender.data("id");

		$.ajax({
			url: baseURL+'index.php/Cobranza_ctrl/pagar/'+idFechaFactura,
			dataType: 'text',
			method: 'post',
			success: function(r){
				sender.closest('tr').remove();
			},
			error: function(){
				alert('Ha ocurrido un error. Intente de nuevo, por favor.');
			}
		});
	});

	$(".btn-cancelar").click(function(event){
		event.preventDefault();

		var sender = $(this);
		var idFechaFactura = sender.data("id");

		if(!confirm("¿Está seguro que desea cancelar la fecha de facturación?"))
			return false;

		$.ajax({
			url: baseURL+'index.php/Cobranza_ctrl/cancelar_email/'+idFechaFactura,
			dataType: 'text',
			method: 'post',
			success: function(r){
				var row = sender.closest("tr");
				row.css("outline", "1px solid orange");
				row.css("background", "#aaa");

				alert("Se ha enviado un correo al administrador.");
			},
			error: function(){
				alert('Ha ocurrido un error. Intente de nuevo, por favor.');
			}
		});
	});

	$(".btn-refacturar").click(function(event){
		event.preventDefault();

		var sender = $(this);
		var idFechaFactura = sender.data("id");

		$.ajax({
			url: baseURL+'index.php/Cobranza_ctrl/refacturar/'+idFechaFactura,
			dataType: 'text',
			method: 'post',
			success: function(r){
				/*var clon = sender.closest("tr").clone(true);
				var btnRefacturar = clon.find(".btn-refacturar");
				var btnCancelar = clon.find(".btn-cancelar");
				var btnPagar = clon.find(".btn-pagar");
				var radios = clon.find(".radio-confirmada");
				var appendSection = sender.closest("tbody");

				btnRefacturar.data("id", r);
				btnCancelar.data("id", r);
				btnPagar.data("id", r);
				radios.data("id", r);
				radios.attr("name", "confirmada"+r);

				appendSection.append(clon);*/
				sender.closest("tr").remove();

				alert("Fecha refacturada.");
			},
			error: function(){
				alert('Ha ocurrido un error. Intente de nuevo, por favor.');
			}
		});
	});

	$(".btn-guardar-nota").click(function(event){
		event.preventDefault();

		var nuevaNota = $(this).html();

		$.ajax({
			url: baseURL+'index.php/Cobranza_ctrl/guardarNotaSeguimiento',
			dataType: 'text',
			method: 'post',
			data: {'nota': nuevaNota},
			async: false,
			success: function(r){},
			error: function(){
				alert('Ha ocurrido un error. Intente de nuevo, por favor.');
			}
		});		
	});

	$(".radio-confirmada").change(function(){
		var sender = $(this);
		var idFechaFactura = sender.data("id");
		var estado = sender.val();

		$.ajax({
			url: baseURL+'index.php/Cobranza_ctrl/actualizarConfirmacion/'+idFechaFactura+'/'+estado,
			dataType: 'text',
			method: 'post',
			success: function(r){},
			error: function(){
				alert('Ha ocurrido un error. Intente de nuevo, por favor.');
			}
		});
	});

	$(".datepicker").change(function(){
		var sender = $(this);
		var nuevaFecha = sender.parent().find(".datepicker-alt").first().val();
		var idFechaFactura = sender.data("id");

		$.ajax({
			url: baseURL+'index.php/Cobranza_ctrl/actualizarFecha/'+idFechaFactura,
			data: {"fecha": nuevaFecha},
			dataType: 'text',
			method: 'post',
			success: function(r){},
			error: function(){
				alert('Ha ocurrido un error. Intente de nuevo, por favor.');
			}
		});		
	});

	$(".datepicker").each(function(){
		var partes = $(this).val().split("/");
		var dia = partes[0];
		var mes = partes[1]-1;
		var anio = partes[2];

		jInitDatepicker($(this), $(this).parent().find("#datepicker-alt"), "dd/mm/yy", "yy-mm-dd", new Date(anio, mes, dia));
	});
});