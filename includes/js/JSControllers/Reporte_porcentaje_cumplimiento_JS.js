$(function(){
	var cDate = new Date();
	var cDay = (cDate.getUTCDate().toString().length < 2)? "0"+cDate.getUTCDate()-1: cDate.getUTCDate()-1;
	var cMonth = (cDate.getUTCMonth().toString().length < 2)? "0"+(cDate.getUTCMonth()+1): cDate.getUTCMonth()+1;
	var cYear = cDate.getUTCFullYear();
	var ultimoDia = new Date(cDate.getFullYear(), cDate.getMonth(), 0); //Último día del mes pasado
	var uDay = (ultimoDia.getUTCDate().toString().length < 2)? "0"+ultimoDia.getUTCDate(): ultimoDia.getUTCDate();
	var uMonth = (ultimoDia.getUTCMonth().toString().length < 2)? "0"+(ultimoDia.getUTCMonth()+1): ultimoDia.getUTCMonth()+1;
	var uYear = ultimoDia.getUTCFullYear();

	if(cDate.getDay()==0){
		$("#dateDesde").val((cDay-2)+'/'+cMonth+'/'+cYear);
		$("#dateHasta").val((cDay+1)+'/'+cMonth+'/'+cYear);
	}else if(cDate.getDay()==6){
		$("#dateDesde").val((cDay-1)+'/'+cMonth+'/'+cYear);
		$("#dateHasta").val((cDay+2)+'/'+cMonth+'/'+cYear);
	}else if(cDate.getDay()==1){
		
		if(cDate.getUTCDate()-3 == 0){//Si el lunes es dia 3
			$("#dateDesde").val((uDay)+'/'+(uMonth)+'/'+uYear);
			$("#dateHasta").val((cDay+1)+'/'+cMonth+'/'+cYear);
		}else if(cDate.getUTCDate()-3 == -1){ //Si el lunes es día 2
			$("#dateDesde").val((uDay-1)+'/'+(uMonth)+'/'+uYear);
			$("#dateHasta").val((cDay+1)+'/'+cMonth+'/'+cYear);
		}else if(cDate.getUTCDate()-3 == -2){//Si el lunes es día 1
			$("#dateDesde").val((uDay-2)+'/'+(uMonth)+'/'+uYear);
			$("#dateHasta").val((cDay+1)+'/'+cMonth+'/'+cYear);
		}else{//Si cae en otro día
			$("#dateDesde").val((cDay-3)+'/'+cMonth+'/'+cYear);
			$("#dateHasta").val((cDay+1)+'/'+cMonth+'/'+cYear);
		}
	}else{
		$("#dateDesde").val(cDay+'/'+cMonth+'/'+cYear);
		$("#dateHasta").val((cDay+1)+'/'+cMonth+'/'+cYear);
	}


	$("#dateDesde, #dateHasta").change(function(){
		var dateDesde = $("#dateDesde").val().split('/');
		var dateHasta = $("#dateHasta").val().split('/');
		var objDateDesde = null;
		var objDateHasta = null;

		if( (dateDesde.length == 3) && (dateHasta.length == 3) ){
			objDateDesde = new Date(dateDesde[2], dateDesde[1], dateDesde[0]);
			objDateHasta = new Date(dateHasta[2], dateHasta[1], dateHasta[0]);

			if(objDateDesde > objDateHasta) $("#dateHasta").val($("#dateDesde").val());
		}
	});
	
	if(cDate.getDay()==0){ //Si es día domingo
		$("#dateDesde").datepicker({ dateFormat: 'dd/mm/yy', defaultDate: -2 });
		$("#dateHasta").datepicker({ dateFormat: 'dd/mm/yy', defaultDate: +1 });
	}else if(cDate.getDay()==6){ //Si es día sábado
		$("#dateDesde").datepicker({ dateFormat: 'dd/mm/yy', defaultDate: -1 });
		$("#dateHasta").datepicker({ dateFormat: 'dd/mm/yy', defaultDate: +2 });
	}else if(cDate.getDay() == 1){ //Si es día lunes
		$("#dateDesde").datepicker({ dateFormat: 'dd/mm/yy', defaultDate: -3 });
		$("#dateHasta").datepicker({ dateFormat: 'dd/mm/yy'});
	}else{
		$("#dateDesde").datepicker({ dateFormat: 'dd/mm/yy', defaultDate: -1 });
		$("#dateHasta").datepicker({ dateFormat: 'dd/mm/yy'});
	}
});

function recalcular() {
	$("#btn-recalcular").click(function(event){
		event.preventDefault();

		var fecha = new Date();
		
		//alert(dias[fecha.getDay()]);
		var fechaInicio = $("#dateDesde").val();
		var fechaFin = $("#dateHasta").val();

		var fechaInicioFormato=fechaInicio.split("/");
		var fechaFinFormato =fechaFin.split("/");

		var diaInicio=obtenDiaDeLaSemana(fechaInicioFormato[0],fechaInicioFormato[1],fechaInicioFormato[2]);
		var diaFin=obtenDiaDeLaSemana(fechaFinFormato[0],fechaFinFormato[1],fechaFinFormato[2]);

		var diasDif = obtenDiasDeDiferencia(fechaInicioFormato[2]+"-"+fechaInicioFormato[1]+"-"+fechaInicioFormato[0],fechaFinFormato[2]+"-"+fechaFinFormato[1]+"-"+fechaFinFormato[0]);

		var diasIntervalo = obtenDiasIntervalo(diaInicio,diasDif);
		//alert(diasIntervalo);
		fechaInicio = fechaInicioFormato[2]+"-"+fechaInicioFormato[1]+"-"+fechaInicioFormato[0];
		fechaFin = fechaFinFormato[2]+"-"+fechaFinFormato[1]+"-"+fechaFinFormato[0];

		$.ajax({
			url: baseURL+"index.php/Reporte_porcentaje_cumplimiento_ctrl/traerHorasConsultor",
			method: 'POST',
			dataType: 'json',
			data: {"fechaInicio":fechaInicio, "fechaFin":fechaFin, "diasIntervalo": diasIntervalo},
			success: function(r){
				actualizaTabla(r);
			},
			error: function(r){
				alert("Ocurrió un error al relizar la consulta");
			}
		});

		$.ajax({
			url: baseURL+"index.php/Reporte_porcentaje_cumplimiento_ctrl/traerHorasCodice",
			method: 'POST',
			dataType: 'json',
			data: {"fechaInicio":fechaInicio, "fechaFin":fechaFin, "diasIntervalo": diasIntervalo},
			success: function(r){
				actualizaTablaCodice(r);
			},
			error: function(r){
				alert("Ocurrió un error al relizar la consulta");
			}
		});

		$.ajax({
			url: baseURL+"index.php/Reporte_porcentaje_cumplimiento_ctrl/traerHorasClientes",
			method: 'POST',
			dataType: 'json',
			data: {"fechaInicio":fechaInicio, "fechaFin":fechaFin, "diasIntervalo": diasIntervalo},
			success: function(r){
				actualizaTablaClientes(r);
			},
			error: function(r){
				alert("Ocurrió un error al relizar la consulta");
			}
		});
	});
}

function obtenActividadesConsultor(){
	$("#cuerpoTabla").delegate(".detallePorcentaje","click",function(event){
		event.preventDefault();

		var a = $(this);

		if(a.html()!="0%"){
			var idConsultor = a.attr("usr-id");
			var fechaInicio = $("#dateDesde").val();
			var fechaFin = $("#dateHasta").val(); 
			var fechaInicioFormato=fechaInicio.split("/");
			var fechaFinFormato =fechaFin.split("/");

			fechaInicio = fechaInicioFormato[2]+"-"+fechaInicioFormato[1]+"-"+fechaInicioFormato[0];
			fechaFin = fechaFinFormato[2]+"-"+fechaFinFormato[1]+"-"+fechaFinFormato[0];
			
			$.ajax({
				url: baseURL+"index.php/Reporte_porcentaje_cumplimiento_ctrl/traeActividadesConsultor",
				method: 'POST',
				dataType: 'json',
				data: {"fechaInicio":fechaInicio, "fechaFin":fechaFin, "idConsultor": idConsultor},
				success: function(r){
					tablaActividadesConsultor(r);
				},
				error: function(r){
					alert("Ocurrió un error al relizar la consulta");
				}
			});
			
			$.fancybox.open({
				'src': "#tablaConsultor",
				'autoSize': false,
				'width': 600,
				'height':500

			});
		}else{
			alert("No hay datos sobre este consultor.");
		}
		
	});
}

function obtenActividadesCodice(){
	$("#cuerpoTablaCodice").delegate(".detallePorcentajeCodice","click",function(event){
		event.preventDefault();

		var a = $(this);

		if(a.html()!="0%"){
			var fechaInicio = $("#dateDesde").val();
			var fechaFin = $("#dateHasta").val(); 
			var fechaInicioFormato=fechaInicio.split("/");
			var fechaFinFormato =fechaFin.split("/");

			fechaInicio = fechaInicioFormato[2]+"-"+fechaInicioFormato[1]+"-"+fechaInicioFormato[0];
			fechaFin = fechaFinFormato[2]+"-"+fechaFinFormato[1]+"-"+fechaFinFormato[0];
			
			$.ajax({
				url: baseURL+"index.php/Reporte_porcentaje_cumplimiento_ctrl/traeActividadesCodice",
				method: 'POST',
				dataType: 'json',
				data: {"fechaInicio":fechaInicio, "fechaFin":fechaFin},
				success: function(r){
					tablaActividadesCodice(r);
				},
				error: function(r){
					alert("Ocurrió un error al relizar la consulta");
				}
			});
			
			$.fancybox.open({
				'src': "#tablaCodiceClon",
				'autoSize': false,
				'width': 600,
				'height':500

			});
		}else{
			alert("No hay datos sobre Códice.");
		}
		
	});
}

function obtenActividadesCliente(){
	$("#cuerpoTablaClientes").delegate(".detallePorcentajeCliente","click",function(event){
		event.preventDefault();

		var a = $(this);

		if(a.html()!="0%"){
			var idCliente = a.attr("cliente-id");
			var fechaInicio = $("#dateDesde").val();
			var fechaFin = $("#dateHasta").val(); 
			var fechaInicioFormato=fechaInicio.split("/");
			var fechaFinFormato =fechaFin.split("/");

			fechaInicio = fechaInicioFormato[2]+"-"+fechaInicioFormato[1]+"-"+fechaInicioFormato[0];
			fechaFin = fechaFinFormato[2]+"-"+fechaFinFormato[1]+"-"+fechaFinFormato[0];
			
			$.ajax({
				url: baseURL+"index.php/Reporte_porcentaje_cumplimiento_ctrl/traeActividadesCliente",
				method: 'POST',
				dataType: 'json',
				data: {"fechaInicio":fechaInicio, "fechaFin":fechaFin, "idCliente": idCliente},
				success: function(r){
					tablaActividadesCliente(r);
				},
				error: function(r){
					alert("Ocurrió un error al relizar la consulta");
				}
			});
			
			$.fancybox.open({
				'src': "#tablaCliente",
				'autoSize': false,
				'width': 600,
				'height':500

			});
		}else{
			alert("No hay datos sobre este cliente.");
		}
		
	});
}

//Obtiene el número de día de la semana que es con base en una fecha específica
function obtenDiaDeLaSemana(dia,mes,anio){ 
	//var dias = new Array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
	var f = new Date();

	f.setFullYear(anio,mes-1,dia);

	//return dias[f.getDay()];
	return f.getDay();
}

//Obtiene el número de días de diferencia que hay entre 2 fechas
function obtenDiasDeDiferencia(fecha1,fecha2){
	var fechaInicio = new Date(fecha1).getTime();
	var fechaFin = new Date(fecha2).getTime();

	var diferencia =fechaFin - fechaInicio;

	var diasDif = diferencia/(1000*60*60*24);

	return diasDif;
}

//Obtiene el número de días que hay por cada dia de la semana en un intervalo de tiempo marcada por una diferencia de días
function obtenDiasIntervalo(diaInicio,diferencia){
	var dom=0,lun=0,mar=0,mie=0,jue=0,vie=0,sab=0,i;

	for (i=0; i < diferencia; i++) {
	
		switch(diaInicio){
			case 0:
				dom++;
				break;
			case 1:
				lun++;
				break;
			case 2:
				mar++;
				break;
			case 3:
				mie++;
				break;
			case 4:
				jue++;
				break;
			case 5:
				vie++;
				break;
			case 6:
				sab++;
				break;
		}

		diaInicio++;
		if (diaInicio==7) {diaInicio=0;}
	}

	var numDias = new Array(dom,lun,mar,mie,jue,vie,sab);
	return numDias;
}

function actualizaTabla(r){
	var tabla = $("#cuerpoTabla"),lastRow,a;
	tabla.find(".contenidoTabla").remove();


	for (var i = 0, n=r.length; i < n; i++) {
		a=r[i];

		
		tabla.append("<tr class='contenidoTabla'></tr>");

		lastRow = tabla.find("tr:last-child");

		lastRow.append("<td class='colNombre'>"+a.nombre+"</td>");
		lastRow.append("<td class='colTiempoReal'>"+a.tiempoReal+"</td>");
		lastRow.append("<td class='colPorcentajeCumplimiento'><a href='' class='detallePorcentaje' usr-id="+a.idConsultor+" >"+a.porcentaje+"%</a></td>");
	
	}
}

function actualizaTablaCodice(r){
	var tabla = $("#cuerpoTablaCodice"),lastRow,a;
	tabla.find(".contenidoTablaCodice").remove();


	//for (var i = 0, n=r.length; i < n; i++) {

	tabla.append("<tr class='contenidoTablaCodice'></tr>");

	lastRow = tabla.find("tr:last-child");

	lastRow.append("<td class='colTiempoTotal'>"+r.tiempoTotal+"</td>");
	lastRow.append("<td class='colTiempoReal'>"+r.tiempoReal+"</td>");
	lastRow.append("<td class='colPorcentajeCumplimiento'><a href='' class='detallePorcentajeCodice'>"+r.porcentaje+"%</a></td>");

		//obtenActividadesConsultor();
		
	//}
}

function actualizaTablaClientes(r){
	var tabla = $("#cuerpoTablaClientes"),lastRow,a;
	tabla.find(".contenidoTablaClientes").remove();


	for (var i = 0, n=r.length; i < n; i++) {
		a=r[i];

		
		tabla.append("<tr class='contenidoTablaClientes'></tr>");

		lastRow = tabla.find("tr:last-child");

		lastRow.append("<td class='colCliente'>"+a.cliente+"</td>");
		lastRow.append("<td class='colTiempoReal'>"+a.tiempoReal+"</td>");
		lastRow.append("<td class='colPorcentajeCumplimiento'><a href='' class='detallePorcentajeCliente' cliente-id="+a.idCliente+" >"+a.porcentaje+"%</a></td>");
		
	}
}

function tablaActividadesConsultor(r){

	$("#consultorResponsable").html("Responsable: "+r[0].nombre);
	var tabla = $("#cuerpoTablaConsultor");
	tabla.find(".contenidoTablaConsultor").remove();


	for (var i = 0, n=r.length; i < n; i++) {
		a=r[i];

		
		tabla.append("<tr class='contenidoTablaConsultor'></tr>");

		lastRow = tabla.find("tr:last-child");

		lastRow.append("<td class='cliente'>"+a.cliente+"</td>");
		lastRow.append("<td class='proyecto'>"+a.proyecto+"</td>");
		lastRow.append("<td class='titulo'>"+a.titulo+"</td>");
		lastRow.append("<td class='tiempoReal'>"+a.tiempoReal+"</td>");
		
	}
}

function tablaActividadesCliente(r){

	$("#nombreCliente").html("Cliente: "+r[0].cliente);
	var tabla = $("#cuerpoTablaCliente");
	tabla.find(".contenidoTablaCliente").remove();


	for (var i = 0, n=r.length; i < n; i++) {
		a=r[i];

		
		tabla.append("<tr class='contenidoTablaCliente'></tr>");

		lastRow = tabla.find("tr:last-child");

		lastRow.append("<td class='consultor'>"+a.consultor+"</td>");
		lastRow.append("<td class='proyecto'>"+a.proyecto+"</td>");
		lastRow.append("<td class='titulo'>"+a.titulo+"</td>");
		lastRow.append("<td class='tiempoReal'>"+a.tiempoReal+"</td>");
		
	}
}

function tablaActividadesCodice(r){

	var tabla = $("#cuerpoTablaCodiceClon");
	tabla.find(".contenidoTablaCodiceClon").remove();


	for (var i = 0, n=r.length; i < n; i++) {
		a=r[i];

		
		tabla.append("<tr class='contenidoTablaCodiceClon'></tr>");

		lastRow = tabla.find("tr:last-child");

		lastRow.append("<td class='consultor'>"+a.consultor+"</td>");
		lastRow.append("<td class='cliente'>"+a.cliente+"</td>");
		lastRow.append("<td class='proyecto'>"+a.proyecto+"</td>");
		lastRow.append("<td class='titulo'>"+a.titulo+"</td>");
		lastRow.append("<td class='tiempoReal'>"+a.tiempoReal+"</td>");
		
	}
}

window.onload = function(){
	recalcular();
	obtenActividadesConsultor();
	obtenActividadesCodice();
	obtenActividadesCliente();
}