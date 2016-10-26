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
		var baseURL = "<?php echo base_url(); ?>";
		<?php if (isset($factura)){ ?>

		var factura = jQuery.parseJSON('<?php echo json_encode($factura); ?>'.replace(/\s+/g,""));

		<?php } ?>
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Lectura_factura_ctrl.js"></script>
	<title>JOBS</title>

	<style type="text/css">
		.row-impuesto {
		    border-radius: 7px;
		    background-color: rgb(187, 221, 255);
		    margin-bottom: 6px;
		}

		.data-group {
		    border-bottom: 1px solid #eee;
		    padding-right: 10px;
		    padding-left: 10px;
		    padding-top: 5px;
		    padding-bottom: 5px;
		}

		.data-group:hover {
		    background-color: white !important;
		}

		#sc-data-factura {
			margin-bottom: 15px;
		}

		#sc-data-factura div span {
		    float: right;
		}

		#sc-data-factura div {
		    border-bottom: 1px solid #ddd;
		}

		#sc-data-factura div:hover {
		    background-color: black !important;
		    color: white !important;
		}
	</style>
</head>
<body>
	<?=$menu; ?>
	<?php //print_r($factura); 
	?>

	<div class="container" id="processBar" style="display: none;">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				 <div class="progress">
				  	<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
				  </div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<form
					class = "form"
					id = "form-submit-factura"
					action = "<?php echo base_url() ?>index.php/Lectura_factura_ctrl/procesarXML"
					method = "post"
					enctype="multipart/form-data"
				>
					<div class="form-group">
						<label>Seleccione el archivo XML que contiene la información de la factura</label>
						<input type="file" name="fileXML" class="form-control">
					</div>

					<div class="form-group">
						<input type="submit" class="form-control btn btn-default" value="Procesar">
					</div>
				</form>
			</div>
		</div>

		<?php if(isset($factura)){ ?>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Cliente</label>
					<select class="form-control" id="clienteAsociado">
					<option value="-1">Ninguno</option>
					<?php foreach($clientes as $cliente){ ?>
					<option value="<?php echo $cliente->id; ?>"><?php echo $cliente->nombre; ?></option>
					<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>Razón social</label>
					<select class="form-control" id="razonSocialAsociada">
						<option value="-1">Ninguna</option>
					</select>
				</div>
				<div class="form-group">
					<label>Cotización</label>
					<select class="form-control" id="cotizacionAsociada">
						<option value="-1">Ninguna</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow: scroll; width: 100%" >
				<table class="table table-bordered table-hover" id="conceptos-tbl" style="width: 6000px;">
					<thead>
						<th>#</th>
						<th>Match</th>
						<th>Tipo</th>
						<th>Cantidad</th>
						<th>Unidad de medida</th>
						<th>Descripción</th>
						<th>Valor unitario</th>
						<th>Importe</th>
						<th>Precio de lista</th>
						<th>Importe de lista</th>
						<th>Importe total</th>
						<th>Monto</th>
						<th>Importe efectivo</th>
						<th>Textos de posicion</th>
						<th>Impuestos</th>
						<th>Notas</th>
					</thead>
					<tbody>
					<?php 
					$k = -1;
					foreach($factura->conceptos as $c){ 
					$k++;
					?>
					<tr>
						<td id="id"><?php echo $k; ?></td>
						<td id="matchCol">
							<select class="form-control idMatched" name="idMatched" class="idMatched" id="idMatched" style="width: 300px;">
							<option value="-1">Seleccione una opción</option>
							</select>
						</td>
						<td id="tipoCol">
							<select class="form-control tipoConcepto" id="tipoConcepto" name="tipoConcepto" style="width: 200px;">
							<option value="-1">Seleccione una opción</option>
							<?php foreach($tiposConcepto as $tipo){ ?>
							<option value="<?php echo $tipo->id; ?>">
								<?php echo $tipo->descripcion; ?>
							</option>
							<?php } ?>
							</select>
						</td>
						<td id="cantidadCol"><?php echo $c->cantidad; ?></td>
						<td id="unidadDeMedidaCol"><?php echo $c->unidadDeMedida; ?></td>
						<td id="descripcionCol"><?php echo $c->descripcion; ?></td>
						<td id="valorUnitarioCol"><?php echo $c->valorUnitario; ?></td>
						<td id="importeCol"><?php echo $c->importe; ?></td>
						<td id="precioListaCol"><?php echo $c->precioLista; ?></td>
						<td id="importeListaCol"><?php echo $c->importeLista; ?></td>
						<td id="importeCol"><?php echo $c->importe; ?></td>
						<td id="montoCol"><?php echo $c->monto; ?></td>
						<td id="montoEfectivoCol">
							<input type="text" class="form-control montoEfectivo" style="width: 100px; text-align: right;" value="<?php echo $c->monto; ?>">
						</td>
						<td id="textosDePosicionCol"><?php echo $c->textosDePosicion; ?></td>
						<td id="impuestosCol">
							<?php foreach($c->impuestos as $i){ ?>
							<div class="row-impuesto"  style="width: 350px;">
								<div class="data-group">
									<label>Contexto: </label>
									<span><?php echo $i->contexto; ?></span>
								</div>
								<div class="data-group">
									<label>Operación: </label>
									<span><?php echo $i->operacion; ?></span>
								</div>
								<div class="data-group">
									<label>Código: </label>
									<span><?php echo $i->codigo; ?></span>
								</div>
								<div class="data-group">
									<label>Base: </label>
									<span><?php echo $i->base; ?></span>
								</div>
								<div class="data-group">
									<label>Tasa: </label>
									<span><?php echo $i->tasa; ?></span>
								</div>
								<div class="data-group">
									<label>Monto: </label>
									<span><?php echo $i->monto; ?></span>
								</div>
							</div>
							<?php } ?>
						</td>
						<td>
							<textarea class="notasTextarea" id="notasTextarea" class="form-control" rows="10" style="width: 200px;"></textarea>
						</td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="sc-data-factura">
				<div>
					<label>Fecha de expedición: </label>
					<span><?php echo $factura->fechaFactura; ?></span>
				</div>
				<div>
					<label>Moneda: </label>
					<span><?php echo $factura->moneda; ?></span>
				</div>
				<div>
					<label>Tipo de cambio venta: </label>
					<span><?php echo $factura->tipoDeCambioVenta; ?></span>
				</div>
				<div>
					<label>Moneda: </label>
					<span><?php echo $factura->subtotal; ?></span>
				</div>
				<div>
					<label>Total: </label>
					<span><?php echo $factura->total; ?></span>
				</div>
				<div>
					<label>Total en letra: </label>
					<span><?php echo $factura->totalEnLetra; ?></span>
				</div>
				<div>
					<label>Forma de pago: </label>
					<span><?php echo $factura->formaDePago; ?></span>
				</div>
				<div>
					<label>Total de traslados federales: </label>
					<span><?php echo $factura->totalTrasladosFederales; ?></span>
				</div>
				<div>
					<label>Total de IVA trasladado: </label>
					<span><?php echo $factura->totalIVATrasladado; ?></span>
				</div>
				<div>
					<label>Total de IEPS Trasladado: </label>
					<span><?php echo $factura->totalIEPSTrasladado; ?></span>
				</div>
				<div>
					<label>Total de retenciones federales: </label>
					<span><?php echo $factura->totalRetencionesFederales; ?></span>
				</div>
				<div>
					<label>Total de ISR retenido: </label>
					<span><?php echo $factura->totalISRRetenido; ?></span>
				</div>
				<div>
					<label>Total de IVA retenido: </label>
					<span><?php echo $factura->totalIVARetenido; ?></span>
				</div>
				<div>
					<label>Total de traslados locales: </label>
					<span><?php echo $factura->totalTrasladosLocales; ?></span>
				</div>
				<div>
					<label>Total de retenciones locales: </label>
					<span><?php echo $factura->totalRetencionesLocales; ?></span>
				</div>
				<div>
					<label>Subtotal bruto: </label>
					<span><?php echo $factura->subtotalBruto; ?></span>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label for="folioFactura">Folio</label>
				<input type="text" id="folioFactura" class="form-control" style="margin-bottom: 15px;">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label for="estadoFactura">Estado</label>
				<select id="estadoFactura" class="form-control" style="margin-bottom: 15px;">
					<option value="-1">Seleccione una opción</option>
				<?php foreach($estadosFactura as $e){ ?>
					<option value="<?php echo $e->id; ?>"><?php echo $e->descripcion; ?></option>
				<?php } ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label for="fechaPago">Fecha de pago</label>
				<input type="text" id="fechaPago" class="form-control datepicker" style="margin-bottom: 15px;" readonly="true">
				<input type="text" id="fechaPagoAlt" class="form-control datepicker" style="margin-bottom: 15px; display: none;" readonly="true">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label for="fechaCancelacion">Fecha de cancelación</label>
				<input type="text" id="fechaCancelacion" class="form-control datepicker" style="margin-bottom: 15px;" readonly="true">
				<input type="text" id="fechaCancelacionAlt" class="form-control datepicker" style="margin-bottom: 15px; display: none;" readonly="true">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label for="ordenCompra">Orden de compra</label>
				<input type="text" id="ordenCompra" class="form-control" style="margin-bottom: 15px;">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label for="ivaFactura">IVA</label>
				<input type="text" id="ivaFactura" class="form-control" style="margin-bottom: 15px;">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label for="importeFactura">Importe</label>
				<input type="text" id="importeFactura" class="form-control" style="margin-bottom: 15px;">
			</div>
		</div>

		<div class="row">
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
		<?php } ?>
	</div>
</body>