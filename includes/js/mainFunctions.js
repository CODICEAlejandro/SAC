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

//Recibe los objetos jquery
function jInitDatepicker(visualContainer, altContainer, dateFormat, altFormat){
	visualContainer.datepicker( "destroy" );
	visualContainer.removeClass("hasDatepicker").removeAttr('id');

	altContainer.datepicker( "destroy" );
	altContainer.removeClass("hasDatepicker").removeAttr('id');

	visualContainer.datepicker({
		dateFormat: dateFormat,
		altFormat: altFormat,
		altField: altContainer
	}).datepicker('setDate', new Date());

	altContainer.hide();
	visualContainer.attr("readonly", true);
}

//Quita saltos de línea de cadena
function removeBlanks(cad){
	return cad.replace(/<br>|\n|\r/, ". ");
}


//Clona una sección indicada y la pega en otra, reasignando un id serializado
function jCloneSection(cloneSection, appendSection, inherited){
	var clon;
	var newID;

	if(inherited === undefined) inherited = true;

	clon = cloneSection.clone(inherited);
	newID = cloneSection.attr("id")+"-clon-"+(appendSection.children().size())

	clon.attr("id", newID);
	clon.show();

	appendSection.append(clon);

	return appendSection.find("#"+newID);
}

//Append after text
function insertAfterElement(arrElements, message, classT){
	var k, n;

	for(k=0, n=arrElements.length; k<n; k++)
		arrElements[k].after("<div class='"+classT+"'>"+message+"</div>");
}

//Validación de selects adjuntos en selector. Si X.val() == nullValue => !filled
function areSelected(selector, nullValue, showErrors, errorMessage){
	var flag = 0;
	var failAt = new Array();
	var message = "Seleccione una opción.";
	var k, n;

	selector.each(function(index){
		if($(this).val() === nullValue){
			flag++;
			failAt.push(selector.eq(index));
		}
	});

	if(typeof showErrors !== 'undefined'){
		if(showErrors){
			if(typeof errorMessage !== 'undefined'){
				message = errorMessage;
			}

			insertAfterElement(failAt, message, 'AErrorMessage');
		}
	}

	return {'status': flag, 'failAt': failAt};
}

//Validación de campos que deben ser numéricos
function areNumeric(selector, showErrors, errorMessage){
	var flag = 0;
	var failAt = new Array();
	var message = "El campo debe ser numérico.";
	var k, n;

	selector.each(function(index){
		if( !($.isNumeric($(this).val())) ){
			flag++;
			failAt.push(selector.eq(index));
		}
	});

	if(typeof showErrors !== 'undefined'){
		if(showErrors){
			if(typeof errorMessage !== 'undefined'){
				message = errorMessage;
			}
			
			insertAfterElement(failAt, message, 'AErrorMessage');
		}
	}

	return {'status': flag, 'failAt': failAt};	
}

//Validación de campos que no deben estar vacíos
function areFilled(selector){
	var flag = true;
	var failAt = null;

	selector.each(function(index){
		if(flag){
			if( $(this).val().trim() == '' || $(this).val().length == 0 ){
				flag = false;
				failAt = selector.eq(index);
				return;
			}
		}
	});

	return {'status': flag, 'failAt': failAt};		
}

//Validación de longitud mínima
function minLength(selector, length){
	var flag = true;
	var failAt = null;

	selector.each(function(index){
		if(flag){
			if( $(this).val().length < length ){
				flag = false;
				failAt = selector.eq(index);
				return;
			}
		}
	});

	return {'status': flag, 'failAt': failAt};	
}

//Validación de longitud máxima
function minLength(selector, length){
	var flag = true;
	var failAt = null;

	selector.each(function(index){
		if(flag){
			if( $(this).val().length > length ){
				flag = false;
				failAt = selector.eq(index);
				return;
			}
		}
	});

	return {'status': flag, 'failAt': failAt};	
}

// Format number
// round = {"up" => .5 sube, "down" => .5 baja, "none" => trunca la cantidad}
// function formatFloat(number, separator_miles, separator_millones, n_decimals, round){
// 	var str_number = number.toString();
// 	var arr = str_number.split(".");
// 	var k, n;
// 	var str_int, str_float;
// 	var res_int, res_float;

// 	if(arr.length == 2){
// 		str_int = arr[1];
// 		str_float = arr[0];

// 		//Enteros
// 		for(k=0, n=str_int.length; k<n; k++){
// 			if( (k+1)%6 == 0 ){
// 				//Millon
// 				res_int += str_int[k]+separator_millones;
// 			}else if( (k+1)%3 == 0 ){
// 				//Mil
// 				res_int += str_int[k]+separator_miles;
// 			}else{
// 				res_int += str_int[k];
// 			}
// 		}

// 		//Decimales
// 		for(k=0; k<n_decimals; k++){
// 			if(k == (n_decimals-1)){
// 				//Ultimo dígito que debe o no redondearse
// 				if(round == "up"){
// 					if(str_float[k])
// 				}else if(round == "down"){

// 				}else if(round == "none"){

// 				}
// 			}else{

// 			}
// 		}
// 	}else if(arr.length == 1){
// 		//Sin decimales
// 		res_float = "";
// 		for(k=0; k<n_decimals; k++)
// 			res_float += "0";
// 	}else throw "Formato incorrecto";
// }

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
