function parseDate(){
	var date = new Date();
	var day = date.getDay();
	var month = date.getMonth();
	var year = date.getFullYear();

	switch(month){
		case 0:
			month = "Enero";
			break;
		case 1:
			month = "Febrero";
			break;
		case 2:
			month = "Marzo";
			break;
		case 3:
			month = "Abril";
			break;
		case 4:
			month = "Mayo";
			break;
		case 5:
			month = "Junio";
			break;
		case 6:
			month = "Julio";
			break;
		case 7:
			month = "Agosto";
			break;
		case 8:
			month = "Septiembre";
			break;
		case 9:
			month = "Octubre";
			break;
		case 10:
			month = "Noviembre";
			break;
		case 11:
			month = "Diciembre";
			break;
	}

	return day+" de "+month+" del "+year;
}


function parseTime(){
	var date = new Date();
	var hours = date.getHours();
	var minutes = date.getMinutes();

	if(hours>12) hours = hours-12;

	if(hours<10) hours = "0" + hours;
	if(minutes<10) minutes = "0" + minutes;

	return hours+":"+minutes;
}

/*
function parseDate(datetime){
	var date = datetime;
	var day = date.getDay();
	var month = date.getMonth();
	var year = date.getFullYear();

	switch(month){
		case 0:
			month = "Enero";
			break;
		case 1:
			month = "Febrero";
			break;
		case 2:
			month = "Marzo";
			break;
		case 3:
			month = "Abril";
			break;
		case 4:
			month = "Mayo";
			break;
		case 5:
			month = "Junio";
			break;
		case 6:
			month = "Julio";
			break;
		case 7:
			month = "Agosto";
			break;
		case 8:
			month = "Septiembre";
			break;
		case 9:
			month = "Octubre";
			break;
		case 10:
			month = "Noviembre";
			break;
		case 11:
			month = "Diciembre";
			break;
	}

	return day+" de "+month+" del "+year;
}

function parseTime(datetime){
	var date = datetime;
	var hours = date.getHours();
	var minutes = date.getMinutes();

	if(hours>12) hours = hours-12;

	if(hours<10) hours = "0" + hours;
	if(minutes<10) minutes = "0" + minutes;

	return hours+":"+minutes;
}*/