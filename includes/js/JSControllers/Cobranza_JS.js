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

	$("#cliente").change(function(){
		var idCliente = $(this).val();
		var selectCotizacion = $("#cotizacion");

		$.ajax({
			url: baseURL+'index.php/Cobranza_ctrl/traerCotizaciones',
			dataType: 'json',
			method: 'post',
			data: {'idCliente': idCliente},
			success: function(response){
				var k,i;

				selectCotizacion.find("option").remove();
				selectCotizacion.append("<option value='-1'>Mostrar todas</option>");
				for(k=0, i=response.length; k<i; k++){
					selectCotizacion.append("<option value='"+response[k].idCotizacion+"'>"+response[k].nombreCotizacion+"</option>");
				}
			},
			error: function(){
				alert("No se han podido recuperar las cotizaciones del cliente seleccionado. Intente de nuevo, por favor.");
			}
		});
	});

	$("#filtrarBtn").click(function(event){
		event.preventDefault();

		var cliente = $("#cliente").val();
		var cotizacion = $("#cotizacion").val();

		$.ajax({
			url: baseURL+'index.php/Cobranza_ctrl/cargarTabla',
			dataType: 'json',
			method: 'post',
			data: {'idCliente': idCliente},
			success: function(response){
				var k,i;
				var tabla = $("#main-content-section");
				var fecha;

				tabla.find("*").remove();
				for(k=0, i=response.length; k<i; k++){
					fecha = response[k];

					if(fecha.idEstadoFactura == 25){
						appended = '<tr class="row-fecha" id="row-fecha" style="outline: 1px solid orange; background: #aaa;">';
					}else{
						appended = '<tr class="row-fecha" id="row-fecha">';
					}

					appended += '<td>';
					appended += '<input class="form-control datepicker"'; 
					appended +=	' data-id="'+fecha.id+'"';
					appended += ' value="'+fecha.fecha_final+'">';
					appended += '<input class="form-control datepicker-alt" id="datepicker-alt">';
					appended += '</td>';
					appended += '<td>';

					if(fecha.confirmada == 1){

						appended += '<input type="radio" class="radio-confirmada" value="1"';
						appended += ' data-id="'+fecha.id+'"';
						appended += ' name="confirmada'+fecha.id+'" checked>';
					
					}else{
						
						appended += '<input type="radio" class="radio-confirmada" value="1"';
						appended += ' data-id="'+fecha.id+'"';
						appended += ' name="confirmada'+fecha.id+'">';
					
					}
					
					appended += '</td>';
					appended += '<td>';
					
					if(fecha.confirmada == 1){
							
						appended += '<input type="radio" class="radio-confirmada" value="0"';
						appended += ' data-id="'+fecha.id+'"';
						appended += ' name="confirmada'+fecha.id+'">';
					
					}else{ 
					
						appended += '<input type="radio" class="radio-confirmada" value="0"';
						appended += ' data-id="'+fecha.id+'"';
						appended += ' name="confirmada'+fecha.id'" checked>';
					
					}
						
					appended += '</td>';
					appended += '<td>'+fecha.folioFactura+'</td>';
					appended += '<td>'+fecha.folioCotizacion+'</td>';
					appended += '<td>'+fecha.cliente+'</td>';
					appended += '<td><button data-id="'+fecha.id+'" id="btn-pagar" class="btn btn-success btn-pagar">';
					appended += '<span class="glyphicon glyphicon-credit-card"></span>';
					appended += '</button></td>';
					appended += '<td><button data-id="'+fecha.id+'" id="btn-cancelar" class="btn btn-danger btn-cancelar">';
					appended += '<span class="glyphicon glyphicon-ban-circle"></span>';
					appended += '</button></td>';
					appended += '<td><button data-id="'+fecha.id+'" id="btn-refacturar" class="btn btn-primary btn-refacturar" data-id="'+fecha.id+'">';
					appended += '<span class=" glyphicon glyphicon-object-align-vertical"></span>';
					appended += '</button></td>';
					appended += '<td>'+fecha.ref_fecha+'</td>';
					appended += '<td>'+fecha.desc_concepto_asociado+'</td>';

					appended += '</tr>';

					tabla.append(appended);
				}
			},
			error: function(){
				alert("No se han podido recuperar las cotizaciones del cliente seleccionado. Intente de nuevo, por favor.");
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