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
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Lectura_factura_proveedor.js"></script>
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
					action = "<?php echo base_url() ?>index.php/Lectura_factura_proveedor_ctrl/procesarXML"
					method = "post"
					enctype="multipart/form-data"
				>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
								<label>Seleccione el archivo XML que contiene la información de la factura</label>
								<input type="file" name="fileXML" class="form-control">
							</div>
							<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
								<p style="text-align: center; margin-top: 12px;"><b>ó</b></p>
							</div>
							<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
								<button id="btn-carga-manual" class="form-control btn btn-warning" style="height: 60px;">
									Carga manual
								</button>
							</div>
						</div>
					</div>

					<div class="form-group">
						<input type="submit" class="form-control btn btn-default" value="Procesar">
					</div>
				</form>
			</div>
		</div>

		<?php if(isset($factura)){ ?>
		<span id="carga-automatica-section">

		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Proveedor sugerido</label>
					<div style="text-align: right;"><?php echo $receptor["cliente_sugerido"]; ?></div>
				</div>
				<br>
				<div class="form-group">
					<label>Razón social sugerida</label>
					<div style="text-align: right;" id="razonSocialSugerida" ><?php echo $receptor["razonSocial"]; ?></div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Cliente</label>
					<select class="form-control" id="clienteAsociado">
						<option value="-1">Ninguno</option>
						<?php foreach($clientes as $cliente){ ?>
						<option value="<?php echo $cliente->id; ?>">
							<?php echo $cliente->nombre; ?>		
						</option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>Razón social</label>
					<select class="form-control" id="razonSocialAsociada">
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
					<tbody>
					<?php 
					$k = -1;
					foreach($factura->conceptos as $c){ 
					$k++;
					?>
					<tr>
						<td id="id"><?php echo $k; ?></td>
						<td id="matchCol">
							<span id="append-section-matchCol" class="append-section-matchCol">
								<div id="clone-match-col" class="clone-match-col">
									<div class="form-group">
										<span id="append-matchCol">
											<label>Fecha de facturación asociada</label>
											<select class="form-control idMatched" name="idMatched[]" id="idMatched" style="width: 300px;">
												<option value="-1">Seleccione una opción</option>
											</select>
										</span>
									</div>
									<div class="form-group">
										<label>Importe: 
											<span id="importeFechaFactura"></span>
										</label>
									</div>
									<div class="form-group">
										<label>Nota: 
											<span id="notaFechaFactura"></span>
										</label>
									</div>
									<div class="form-group">
										<label>Servicio: 
											<span id="servicioConcepto"></span>
										</label>
									</div>
									<div class="form-group">
										<div style="width: 100%; border-bottom: 5px solid black; margin-bottom: 8px; padding-bottom: 8px;"></div>
									</div>
								</div>
							</span>

							<button class="btn btn-primary btn-add-matched-select" style="width: 100%; margin-top: 10px;">
								Agregar
							</button>
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
					<label>Subtotal: </label>
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
				<input type="text" id="folioFactura" class="form-control" style="margin-bottom: 15px;" value="<?php echo $factura->folio; ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label for="estadoFactura">Estado</label>
				<select id="estadoFactura" class="form-control" style="margin-bottom: 15px;">
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
			</div>
			<div class="col-xs-2 col-sm-1 col-md-1 col-lg-1">
				<label style="margin-bottom: 0;">Cancelar</label>
				<input type="checkbox" id="estaCancelada" style="margin-top: 0;">
			</div>
			<div class="col-xs-10 col-sm-11 col-md-11 col-lg-11">
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
				<input type="text" id="ivaFactura" class="form-control" style="margin-bottom: 15px;" value="<?php echo $generalFactura['iva']; ?> %">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<label for="importeFactura">Importe</label>
				<input type="text" id="importeFactura" class="form-control" style="margin-bottom: 15px;" value="<?php echo $generalFactura['subtotal']; ?>">
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

		</span>
		<?php } ?>
	</div>
</body>