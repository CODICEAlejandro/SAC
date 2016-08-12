<div class="row dotted-bottom">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<h3 class="sectionTitle">Dirección operativa</h3>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<button class="form-control btn btn-primary" id="btn-agrega-direccion-operativa">
			<span class="glyphicon glyphicon-plus-sign"></span>
		</button>
	</div>

	<div id="append-section-direccion-operativa">
	</div>				

	<div id="existent-section-direccion-operativa">
	</div>

	<section id="sc-informacion-operativa" class="form-informacion-operativa" style="padding-top: 20px; clear: both; display:none;">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<form
				class="form_direccion_operativa"
			>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-8 col-md-4 col-lg-4">
							<label>Calle</label>
							<input type="text" name="calle" id="calle" class="calle form-control">
						</div>
						<div class="col-xs-12 col-sm-4 col-md-1 col-lg-1">
							<label>Número</label>
							<input type="text" name="numero" id="numero" class="numero form-control">									
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<label>Colonia</label>
							<input type="text" name="colonia" id="colonia" class="colonia form-control">
						</div>

						<div class="col-xs-10 col-sm-4 col-md-2 col-lg-2">
							<label>Estado</label>
							<select name="estadoActivo" id="estadoActivo" class="estadoActivo form-control">
								<option value="1">Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
						<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
							<button class="btn btn-warning" id="btn-ver-direccion-operativa" style="margin-top: 25px; float: right;">Ver</button>
						</div>
					</div>
				</div>

				<section id="sc-description-direccion-operativa">

				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
							<label>País</label>
							<select name="idPais" id="idPais" class="idPais form-control">
								<?php foreach($paises as $pais){ ?>
									<option value="<?php echo $pais->id; ?>"><?php echo $pais->nombre; ?></option>
								<?php } ?>
							</select>									
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
							<label>C.P.</label>
							<input type="text" id="cp" name="cp" class="cp form-control" placeholder="00">
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<label>Estado</label>
							<select name="idEstado" id="idEstado" class="idEstado form-control">
								<option value="-1">Ninguno</option>
							</select>									
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<label>Ciudad</label>
							<select name="idCiudad" id="idCiudad" class="idCiudad form-control">
								<option value="-1">Ninguna</option>
							</select>									
						</div>
					</div>
				</div>

				<div class="form-group">
					<input type="submit" value="Guardar" class="form-control btn btn-warning">
				</div>

				</section>
			</form>

		</div>
	</section>
</div>