

function filtro(){

	$("#filtro").change(function() {
		var sel = $("#filtro");
		var filtro = sel.val();

		$("#mesClon").remove();
		$("#anioFiltro").remove();
		$("#fechasClon").remove();

		if(filtro == 1){
			var clon_mes = $("#appendMesClon").clone(true);

			clon_mes.find("#mes").attr("id", "mesClon");
			$("#appendFiltros").append(clon_mes.html());
			//alert(clon_mes.html());
			clon_mes.show();
		
		}else if(filtro==2){
			var fecha = new Date();
			var anio = fecha.getUTCFullYear();

			$("#appendFiltros").append('<input id="anioFiltro" class="form-control" style="margin-top: 20px;" type="number" name="quantity" min="2016" max="'+anio+'" value="'+anio+'">')

		}else if(filtro==3){
			var clon_fechas = $("#appendFechasClon").clone(true);

			clon_fechas.find("#fechas_busqueda").attr("id", "fechasClon");
			var fecha_actual = new Date();
			var dia = fecha_actual.getUTCDate();
			var mes = fecha_actual.getUTCMonth();
			var anio = fecha_actual.getFullYear();
			

			if(dia<10){
				dia = "0"+dia;
			}
			if(mes<10){
				mes = "0"+(mes+1);
			}
			

			clon_fechas.find("#fecha_inicio").attr("class","form-control datepicker fecha_inicio");
			clon_fechas.find("#fecha_fin").attr("class","form-control datepicker fecha_fin");
			
			
			$("#appendFiltros").append(clon_fechas.html());
			$("#fechasClon").find("#fecha_inicio").val(dia+"/"+mes+"/"+anio);
			$("#fechasClon").find("#fecha_fin").val(dia+"/"+mes+"/"+anio);
			$(".datepicker").datepicker({ 
				dateFormat: 'dd/mm/yy',
				beforeShow: function (input, inst) {
			        setTimeout(function () {
			            inst.dpDiv.css({
			                top: 250,
			                left: 500
			            });
			        }, 0);
			    }
            });
			//$(".fecha_inicio").datepicker({ dateFormat: 'dd/mm/yy'});
			//$(".fecha_fin").datepicker({ dateFormat: 'dd/mm/yy'});
			clon_fechas.show();

		}
	});
}

function generaReporte(){

	$("#btn_genera_reporte").click(function(event){
		event.preventDefault();

		var filtro = $("#filtro").val();
		var periodo;

		$("#form-excel").empty();

		if(filtro == "-1"){
			alert("Debes seleccionar una opción.");
		}else if(filtro=="1"){
			var mes = $("#mesClon").val();

			if(mes=="-1"){
				alert("Debes seleccionar un mes.");
			}else{
				periodo=1;
				$("#form-excel").append('<input name="periodo" value="'+periodo+'"><input name="mes" value="'+mes+'">');
				$("#form-excel").submit();
			}
		}else if(filtro=="2"){
			var anio = $("#anioFiltro").val();
			periodo=2;
			$("#form-excel").append('<input name="periodo" value="'+periodo+'"><input name="anio" value="'+anio+'">');
			$("#form-excel").submit();

		}else if(filtro=="3"){
			var fechaInicio = $("#fechasClon").find("#fecha_inicio").val();
			
			var fechaFin = $("#fechasClon").find("#fecha_fin").val();

			periodo = 3; 

			$("#form-excel").append('<input name="periodo" value="'+periodo+'"><input name="fechaInicio" value="'+fechaInicio+'"><input name="fechaFin" value="'+fechaFin+'">');
			$("#form-excel").submit();
		}
		/*
		if(filtro == "-1"){
			alert("Debes seleccionar una opción");
		}else if(filtro == "1"){ //Si se quiere generar reporte por mes

			var mes = $("#mesClon").val();

			if(mes=="-1"){
				alert("Debes seleccionar un mes.");
			}else{
			
				periodo=1; //Reporte Mensual
				$.ajax({
					url: baseURL+'index.php/Reporte_ingresos_ctrl/generaReporte',
					method: 'post',
					dataType: 'text',
					data: { "mes":mes,"periodo":periodo},
					success: function(r) {
						//alert("Reporte generado con éxito")
					},
					error: function(r) {
						alert("Ha ocurrido un error al generar el reporte");
					}
				});
			}
		}else if(filtro == "2"){ //Si se quiere generar un reporte Anual
			var anio = $("#anioFiltro").val();
			periodo=2; //Reporte anual
			$.ajax({
				url: baseURL+'index.php/Reporte_ingresos_ctrl/generaReporte',
				method: 'post',
				dataType: 'text',
				data:{"anio":anio, "periodo":periodo},
				success: function(r) {
					alert("Reporte generado con éxito")
				},
				error: function(r) {
					alert("Ha ocurrido un error al generar el reporte");
				}
			});

		}else if(filtro == "3"){ //Si se quiere generar un reporte por fechas específicas


			var fechaInicio = $("#fechasClon").find("#fecha_inicio").val();
			
			var fechaFin = $("#fechasClon").find("#fecha_fin").val();

			periodo = 3; //Reporte fechas específicas
			
			$.ajax({
				url: baseURL+'index.php/Reporte_ingresos_ctrl/generaReporte',
				method: 'post',
				dataType: 'text',
				data:{ "fechaInicio": fechaInicio, "fechaFin": fechaFin, "periodo":periodo},
				success: function(r) {
					alert("Reporte generado con éxito")
				},
				error: function(r) {
					alert("Ha ocurrido un error al generar el reporte");
				}
			});
		}
		*/
	});
}

window.onload= function(){
	filtro();
	generaReporte();
}