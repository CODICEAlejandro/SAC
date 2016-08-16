<div class="row dotted-bottom">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<h3 class="sectionTitle">Banco</h3>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<button class="form-control btn btn-primary" id="btn-agrega-banco">
			<span class="glyphicon glyphicon-plus-sign"></span>
		</button>
	</div>

	<div id="append-section-banco">
	</div>				

	<div id="existent-section-banco">
	</div>

	<section id="sc-banco" class="form-banco" style="padding-top: 20px; clear: both; display: none; ">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
			<form
				class="form-banco"
			>
				
				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<label>Nombre</label>
							<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Identificador">
						</div>
						<div class="col-xs-12 col-sm-6 col-md-5 col-lg-5">
							<label>Banco</label>
							<select class="form-control" id="idBanco" name="idBanco">
								<option value="-1">Seleccione una opción</option>
								<?php foreach($bancos as $banco){ ?>
								<option value="<?php echo $banco->id; ?>"><?php echo $banco->nombre; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
							<label>Estado</label>
							<select class="form-control" id="estadoActivo" name="estadoActivo">
								<option value="1">Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-1 col-lg-1">
							<button style="margin-top: 25px; float: right;" class="btn btn-warning" id="btn-ver-detalle-banco">Ver</button>
						</div>
					</div>
				</div>

				<section id="sc-banco-detail">
				<div class="form-group">
					<label>Sucursal</label>
					<input type="text" name="sucursal" id="sucursal" class="form-control">
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label>CLABE</label>
							<input type="text" name="clabe" id="clabe" class="form-control">					
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label>Cuenta</label>					
							<input type="text" name="cuenta" id="cuenta" class="form-control">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label>Dirección fiscal asociada</label>
					<select class="form-control idDireccionFiscal" id="idDireccionFiscal" name="idDireccionFiscal">
						<option value="-1">Seleccione una opción</option>
					</select>
				</div>

				<div class="form-group">
					<input type="submit" class="btn btn-warning form-control" value="Guardar">
				</div>

			</form>

		</div>

	</section>

</div>
