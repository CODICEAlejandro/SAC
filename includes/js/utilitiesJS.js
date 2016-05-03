
// Transforma un dato nativo SQL datetime a un formato legible de la siguiente forma
// Día Mes Año | horas:minutos
// String -> String
function parseDatetime(datetime){
	var date,time = datetime.split(" ");
	var anio,mes,dia = data.split("-");
	var horas,minutos,segundos = time.split(":");
	var format = "";

	switch(mes){
		case "01":
			mes = "Enero";
			break;
		case "02":
			mes = "Febrero";
			break;
		case "03":
			mes = "Marzo";
			break;
		case "04":
			mes = "Abril";
			break;
		case "05":
			mes = "Mayo";
			break;
		case "06":
			mes = "Junio";
			break;
		case "07":
			mes = "Julio";
			break;
		case "08":
			mes = "Agosto";
			break;
		case "09":
			mes = "Septiembre";
			break;
		case "10":
			mes = "Octubre";
			break;
		case "11":
			mes = "Noviembre";
			break;
		case "12":
			mes = "Diciembre";
			break;
	}

	format = dia+" "+mes+" "+anio+" | "+horas+":"+minutos;
}