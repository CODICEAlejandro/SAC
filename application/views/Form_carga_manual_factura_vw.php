<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html>
<head>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>

	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>

	<script type="text/javascript">
		var totalFechasFactura = new Array();
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Form_carga_manual_factura.js"></script>
	<title>JOBS</title>

	<style type="text/css">
		.row-impuesto, .clone-match-col, .row-with-custom-border {
			border-radius: 0px;
			background-color: #eee;
			margin-bottom: 6px;
			padding: 6px;
			padding-top: 10px;
			border: #F3E2A9 15px solid;		
		}
		
		#conceptos-tbl tbody input {
			width: 150px;
		}

		.row-impuesto #titulo {
			margin-left: 8px;

			-webkit-transition: margin-left 0.2s; /* Safari */
			transition: margin-left 0.2s;				
		}

		.row-impuesto:hover #titulo{
			margin-left: 10%;
		}

		.impuesto-input {
			width: 100% !important;
		}

		.eliminar-impuesto {
			-webkit-transition: all 0.5s;
			transition: all 0.5s;
		}

		.eliminar-impuesto:hover {
			transform: rotate(360deg);
		}

		#contraer-item {
			cursor: pointer;
		}

		#delete-item {
			cursor: pointer;
		}

		.AErrorMessage {
			color: red;
		}
	</style>
</head>
<body>
	<?=$menu; ?>
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<form id="form-carga-manual-cliente">
			<div class="form-group">
				<label>Cliente</label>
				<div id="main-select-cliente">
					<select name="cliente" id="cliente" class="form-control obligatorio slc-clienteAsignado">
						<option value="-1">Seleccione una opción</option>
						<?php foreach($clientes as $c){ ?>
						<option value="<?php echo $c->id; ?>">
						<?php echo $c->nombre; ?>
						</option>
						<?php } ?>
					</select>
				</div>
				<div id="append-section-cliente"></div>
			</div>
			<div class="form-group">
				<button class="btn btn-info" id="btn-agregar-cliente">Agregar cliente</button>
			</div>
			<div class="form-group">
				<label>Razón social</label>
				<select name="razonSocial" id="razonSocial" class="form-control obligatorio">
					<option value="-1">Seleccione una opción</option>
				</select>
			</div>
			<div class="form-group">
				<label>Fecha desde donde se muestran las fechas de facturación</label>
				<input type="text" id="fecha_desde" class="form-control datepicker">
				<input type="text" id="fecha_desde_alt" class="form-control datepicker">
			</div>
			<div class="form-group">
				<label>Fecha hasta donde se muestran las fechas de facturación</label>
				<input type="text" id="fecha_hasta" class="form-control datepicker">
				<input type="text" id="fecha_hasta_alt" class="form-control datepicker">
			</div>
		</form>

		</div>
	</div>

	<div class="row row-with-custom-border" style="overflow: scroll; background-color: white;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<table class="table table-bordered table-hover" id="conceptos-tbl" style="width: 6000px;">
				<thead>
					<th>#</th>
					<th>Match</th>
					<th>Cantidad</th>
					<th>Unidad de medida</th>
					<th>Descripción</th>
					<th>Valor unitario</th>
					<th>Importe</th>
					<th>Precio de lista</th>
					<th>Importe de lista</th>
					<th>Importe total</th>
					<th>Monto</th>
					<th>Textos de posicion</th>
					<th>Impuestos</th>
					<th>Notas</th>
				</thead>
				<tbody id="append-section-concepto-factura">
					<tr id="clone-section-concepto-factura" class="clone-section-concepto-factura" style="display: none;">
						<td id="id"></td>
						<td id="matchCol">
							<span id="append-section-matchCol" class="append-section-matchCol">
							</span>

							<button class="btn btn-primary btn-add-matched-select" id="btn-add-matched-select" style="width: 100%; margin-top: 10px;">
								Agregar
							</button>
						</td>
						<td id="cantidadCol">
							<input type="text" id="cantidad" class="form-control">
						</td>
						<td id="unidadDeMedidaCol">
							<input type="text" id="unidadDeMedida" class="form-control">
						</td>
						<td id="descripcionCol">
							<textarea type="text" id="descripcion" class="form-control" rows="10" style="width: 200px;"></textarea>
						</td>
						<td id="valorUnitarioCol">
							<input type="text" id="valorUnitario" class="form-control">
						</td>
						<td id="importeCol">
							<input type="text" id="importe" class="form-control">
						</td>
						<td id="precioListaCol">
							<input type="text" id="precioLista" class="form-control">
						</td>
						<td id="importeListaCol">
							<input type="text" id="importeLista" class="form-control">
						</td>
						<td id="importeTotalCol">
							<input type="text" id="importeTotal" class="form-control">
						</td>
						<td id="montoCol">
							<input type="text" id="monto" class="form-control">
						</td>
						<td id="textosDePosicionCol">
							<textarea class="form-control notasTextarea" id="textosDePosicion" class="form-control" rows="10" style="width: 200px;"></textarea>
						</td>
						<td id="impuestosCol">
							<span id="append-section-impuestoCol" class="append-section-impuestoCol">
							</span>

							<button class="btn btn-primary btn-add-impuestoCol" id="btn-add-impuestoCol" style="width: 100%; margin-top: 10px;">
								Agregar
							</button>
						</td>
						<td id="notasCol">
							<textarea class="form-control notasTextarea" id="notas" class="form-control" rows="10" style="width: 200px;"></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<button class="btn btn-info form-control" id="btn-add-concepto-factura">Agregar concepto</button>
		</div>
	</div>

	<!-- ####### GENERAL DATA ####### -->
	<div class="row row-with-custom-border" style="margin-top: 15px;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<form class="form">
				<div class="form-group">
					<label>Fecha de expedición: </label>
					<input type="text" class="form-control" id="fechaDeExpedicion">
					<input type="hidden" id="fechaDeExpedicion-alt">
				</div>
				<div class="form-group">
					<label>Moneda: </label>
					<select id="moneda" class="form-control obligatorio">
						<option value="-1">Seleccione una opción</option>
						<?php foreach($monedas as $m){ ?>
						<option value="<?php echo $m->moneda ?>"><?php echo $m->moneda; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>Tipo de cambio venta: </label>
					<input type="text" class="form-control" id="tipoDeCambioVenta" value="1">
				</div>
				<div class="form-group">
					<label>Subtotal: </label>
					<input type="text" class="form-control" id="subtotal" value="0">
				</div>
				<div class="form-group">
					<label>Total: </label>
					<input type="text" class="form-control" id="total" value="0">
				</div>
				<div class="form-group">
					<label>Total en letra: </label>
					<input type="text" class="form-control" id="totalEnLetra">
				</div>
				<div class="form-group">
					<label>Forma de pago: </label>
					<input type="text" class="form-control" id="formaDePago">
				</div>
				<div class="form-group">
					<label>Total de traslados federales: </label>
					<input type="text" class="form-control" id="totalTrasladosFederales" value="0">
				</div>
				<div class="form-group">
					<label>Total de IVA trasladado: </label>
					<input type="text" class="form-control" id="totalIVATrasladado" value="0">
				</div>
				<div class="form-group">
					<label>Total de IEPS Trasladado: </label>
					<input type="text" class="form-control" id="totalIEPS" value="0">
				</div>
				<div class="form-group">
					<label>Total de retenciones federales: </label>
					<input type="text" class="form-control" id="totalRetencionesFederales" value="0">
				</div>
				<div class="form-group">
					<label>Total de ISR retenido: </label>
					<input type="text" class="form-control" id="totalISRRetenido" value="0">
				</div>
				<div class="form-group">
					<label>Total de IVA retenido: </label>
					<input type="text" class="form-control" id="totalIVARetenido" value="0">
				</div>
				<div class="form-group">
					<label>Total de traslados locales: </label>
					<input type="text" class="form-control" id="totalTrasladosLocales" value="0">
				</div>
				<div class="form-group">
					<label>Total de retenciones locales: </label>
					<input type="text" class="form-control" id="totalRetencionesLocales" value="0">
				</div>
				<div class="form-group">
					<label>Subtotal bruto: </label>
					<input type="text" class="form-control" id="subtotalBruto" value="0">
				</div>
			</form>
		</div>
	</div>

	<div class="row row-with-custom-border">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label for="folioFactura">Folio</label>
			<input type="text" id="folioFactura" class="form-control" style="margin-bottom: 15px;">
		</div>
	</div>
	<div class="row row-with-custom-border">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label for="estadoFactura">Estado</label>
			<select id="estadoFactura" class="form-control" style="margin-bottom: 15px;">
			<?php foreach($estadosFactura as $e){ ?>
				<option value="<?php echo $e->id; ?>"><?php echo $e->descripcion; ?></option>
			<?php } ?>
			</select>
		</div>
	</div>
	<div class="row row-with-custom-border">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label for="fechaPago">Fecha de pago</label>
			<input type="text" id="fechaPago" class="form-control datepicker" style="margin-bottom: 15px;" readonly="true">
			<input type="text" id="fechaPagoAlt" class="form-control datepicker" style="margin-bottom: 15px; display: none;" readonly="true">
		</div>
		<!--
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label for="fechaCancelacion">Fecha de cancelación</label>
			<input type="text" id="fechaCancelacion" class="form-control datepicker" style="margin-bottom: 15px;" readonly="true">
			<input type="text" id="fechaCancelacionAlt" class="form-control datepicker" style="margin-bottom: 15px; display: none;" readonly="true">
		</div>
		-->
	</div>
	<div class="row row-with-custom-border">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label for="ordenCompra">Orden de compra</label>
			<input type="text" id="ordenCompra" class="form-control" style="margin-bottom: 15px;">
		</div>
	</div>
	<div class="row row-with-custom-border">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label for="ivaFactura">IVA (%)</label>
			<input type="text" id="ivaFactura" class="form-control" style="margin-bottom: 15px;" placeholder="0-100">
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label for="importeFactura">Importe</label>
			<input type="text" id="importeFactura" class="form-control" style="margin-bottom: 15px;">
		</div>
	</div>

	<div class="row row-with-custom-border">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<label for="notasFactura">Notas de la factura actual</label>
			<textarea id="notasFactura" class="form-control" rows="6" style="margin-bottom: 15px;"></textarea>
		</div>
	</div>
	<div class="row" style="margin-bottom: 15px;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<button class="btn btn-primary form-control" id="btn-guardar-factura">Confirmar</button>
		</div>
	</div>


	<!-- ###### CLONE SECTIONS ###### -->

	<!-- TABLE ROW -->

	<!-- MATCH COL -->
	<div id="clone-match-col" class="clone-match-col" style="display: none;">
		<span style="border-bottom: 15px solid black; color: #aaa;">
			<h4 style="color: #aaa;">
				<span id="titulo">Concepto de cotización</span>
				<span style="float: right;"> 
					<span class="glyphicon glyphicon-minus contraer-impuesto" id="contraer-item" style="margin-right: 6px;"></span>
					<span class="glyphicon glyphicon-remove eliminar-impuesto" id="delete-item" style="color: #FACC2E;"></span>
				</span>
			</h4>
			<div class="form-group">
				<span id="append-matchCol">
					<select class="form-control idMatched matchObligatorio" name="idMatched[]" id="idMatched" style="width: 300px;">
						<option value="-1">Seleccione una opción</option>
					</select>
				</span>
			</div>

			<span id="contraer-section">
				<div class="form-group">
					<span>Importe: </span>
					<span id="importeFechaFactura"></span>
				</div>
				<div class="form-group">
					<span>Nota: </span> 
					<span id="notaFechaFactura"></span>
				</div>
				<div class="form-group">
					<span>Servicio: </span>
					<span id="servicioConcepto"></span>
				</div>
			</span>
		</span>
	</div>

	<!-- IMPUESTO COL -->
	<form class="row-impuesto" id="clone-section-impuesto" style="width: 350px; display:none;">
		<div class="form-group">
			<h4 style="color: #aaa;">
				<span id="titulo">Impuesto</span>
				<span style="float: right;"> 
					<span class="glyphicon glyphicon-minus contraer-impuesto" id="contraer-item" style="margin-right: 6px;"></span>
					<span class="glyphicon glyphicon-remove eliminar-impuesto" id="delete-item" style="color: #FACC2E;"></span>
				</span>
			</h4>
		</div>
		<div class="data-group form-group">
			<input type="text" class="form-control impuesto-input" id="contexto" placeholder="Contexto">
		</div>

		<span id="contraer-section">
			<div class="data-group form-group">
				<input type="text" class="form-control impuesto-input" id="operacion" placeholder="Operación">
			</div>
			<div class="data-group form-group">
				<input type="text" class="form-control impuesto-input" id="codigo" placeholder="Código">
			</div>
			<div class="data-group form-group">
				<input type="text" class="form-control impuesto-input" id="base" placeholder="Base">
			</div>
			<div class="data-group form-group">
				<input type="text" class="form-control impuesto-input" id="tasa" placeholder="Tasa">
			</div>
			<div class="data-group form-group">
				<input type="text" class="form-control impuesto-input" id="monto" placeholder="Monto">
			</div>
		</span>
	</form>

	</body>
</div>
