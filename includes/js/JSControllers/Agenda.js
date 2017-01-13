$(function(){
	$("#buscador").keyup(function(){
		var sender = $(this);
		var busqueda = sender.val();
		var regExp = new RegExp('^.*'+busqueda+'.*$', 'i');

		var renglones = $(".row-contacto #col-nombre");
		var resultado, r;

		renglones.each(function(index){
			r = $(this).html();
			resultado = regExp.test(r);

			if(resultado) $(this).closest("tr").show();
			else $(this).closest("tr").hide();
		});
	});
});