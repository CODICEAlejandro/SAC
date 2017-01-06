class ConceptoCotizacion {
	constructor(){
		this.descripcion = "";
		this.referencia = "";
		this.nota = "";
		this.iva = "";
		this.importe = 0;
		this.total = 0;
		this.fechasFactura = new Array();
	}

	pushFechaFactura(fecha){
		this.fechasFactura.push(fecha);
	}
} 


class FechaFactura {
	constructor(){
		this.importe = 0;
		this.referencia = "";
		this.nota = "";
		this.fecha = "";
	}
}