$(function(){


	$(".btn-pagar").click(function(event){
		event.preventDefault();

		var sender = $(this);
		var idFechaFactura = sender.data("id");
		
		$.ajax({
			url: baseURL+'index.php/Cobranza_ctrl/traerBancosAsociados',
			dataType: "json",
			method: "post",
			success: function(r){
				var i;
				var l = r.length;

				var div = $("#pagado");
				div.find("#confirm-pago").attr("data-id",idFechaFactura);
				var sel = div.find("#bancos_asoc");
				sel.find("*").remove();
				sel.append('<option value="-1">Seleccione una opción</option>');

				for (i = 0; i < l; i++) {
					sel.append("<option value="+r[i].id+">"+r[i].nombre+"</option>");
				}

				$.fancybox.open({
					'src': "#pagado",
					'autoSize': false,
					'width': 600,
					'height':500

				});

				$(".confirm-pago").click(function(event) {
					event.preventDefault();

					var btn_confirm = $(this);

					var idFechaFactura = btn_confirm.attr("data-id");

					var idBanco = btn_confirm.closest("#pagado").find("#bancos_asoc").val();

					if (idBanco!=-1) {

						$.ajax({
							url: baseURL+"index.php/Cobranza_ctrl/pagar/"+idBanco+"/"+idFechaFactura,
							dataType: "text",
							method: "post",
							data: {"idBanco":idBanco, "idFechaFactura": idFechaFactura},
							success: function(r) {
								//alert("Se actualizó el banco");
								sender.closest('tr').remove();
								$.fancybox.close();
							},
							error: function () {
								alert("Ocurrió un error al actualizar banco");
							}
						});

					}else{
						alert("Debe seleccionar un banco para confirmar el pago");
					}
				});

			},
			error: function() {
				alert("Ocurrió un error al cargar los bancos");
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

		var nuevaNota = $(this).closest("td").find("#nota-seguimiento").val();
		var id = $(this).attr("data-id");

		$.ajax({
			url: baseURL+'index.php/Cobranza_ctrl/guardarNotaSeguimiento',
			dataType: 'text',
			method: 'post',
			data: {'nota': nuevaNota, "idFechaFactura": id},
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

