function parseDate(){
	var date = new Date();
	var day = date.toString().split(" ")[2];
	var month = date.getMonth();
	var year = date.getFullYear();

	month = parseMonth(month);

	return day+" de "+month+" del "+year;
}

function parseMonth(month){
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

	return month;
}

function parseMonthFromString(month){
	switch(month){
		case "01":
			month = "Enero";
			break;
		case "02":
			month = "Febrero";
			break;
		case "03":
			month = "Marzo";
			break;
		case "04":
			month = "Abril";
			break;
		case "05":
			month = "Mayo";
			break;
		case "06":
			month = "Junio";
			break;
		case "07":
			month = "Julio";
			break;
		case "08":
			month = "Agosto";
			break;
		case "09":
			month = "Septiembre";
			break;
		case "10":
			month = "Octubre";
			break;
		case "11":
			month = "Noviembre";
			break;
		case "12":
			month = "Diciembre";
			break;
	}

	return month;	
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

function jEntityDecode(str) {
    return $("<div/>").html(str).text();
}

//StringTime: hh:mm:ss
function transformTimeToDecimal(stringTime){
	var parts = stringTime.split(":");
	var seconds, minutes, hours;
	var result = stringTime;

	if(parts.length == 3){
		seconds = parseInt(parts[2]);
		minutes = parseInt(parts[1]);
		hours = parseInt(parts[0]);

		seconds = seconds / 3600;
		minutes = minutes / 60;
		result = hours + seconds + minutes;
	}

	return result;
}

//Inicializar datepicker
function initDatepicker(visualContainer, altContainer, dateFormat, altFormat){
	$(visualContainer).datepicker({
		dateFormat: dateFormat,
		altFormat: altFormat,
		altField: altContainer
	}).datepicker('setDate', new Date());

	$(altContainer).hide();
	$(visualContainer).attr("readonly", true);
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
