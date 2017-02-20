<section id="main-direccion-fiscal" style="display: none;">

<!-- Form dirección fiscal -->
<div class="row dotted-bottom">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<h3 class="sectionTitle">Dirección fiscal</h3>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<button class="form-control btn btn-primary" id="btn-agrega-direccion-fiscal">
			<span class="glyphicon glyphicon-plus-sign"></span>
		</button>
	</div>

	<div id="append-section-direccion-fiscal">
	</div>				

	<div id="existent-section-direccion-fiscal">
	</div>

	<section id="sc-informacion-fiscal" class="form-informacion-fiscal" style="padding-top: 20px; clear: both; display:none;">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<form
				class="form_direccion_fiscal"
			>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-7 col-sm-8 col-md-8 col-lg-8">
							<label>Razón social</label>
							<input type="text" name="razonSocial" id="razonSocial" class="razonSocial form-control">
						</div>
						<div class="col-xs-3 col-sm-2 col-md-2 col-lg-3">
							<label>Estado</label>
							<select name="estadoActivo" id="estadoActivo" class="estadoActivo form-control">
								<option value="1">Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-1">
							<button class="btn btn-warning" id="btn-ver-direccion-fiscal" style="margin-top: 25px; float: right;">Ver</button>
						</div>
					</div>
				</div>

				<section id="sc-description-direccion-fiscal">

				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-8 col-md-5 col-lg-5">
							<label>Calle</label>
							<input type="text" name="calle" id="calle" class="calle form-control">
						</div>
						<div class="col-xs-12 col-sm-4 col-md-2 col-lg-2">
							<label>Número</label>
							<input type="text" name="numero" id="numero" class="numero form-control">									
						</div>
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<label>Colonia</label>
							<input type="text" name="colonia" id="colonia" class="colonia form-control">
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
							<label>País</label>
							<select name="idPais" id="idPais" class="idPais form-control">
								<option value="NULL">Seleccione una opción</option>
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
					<label>RFC</label>
					<input type="text" name="rfc" id="rfc" class="rfc form-control">
				</div>

				<div class="form-group">
					<input type="submit" value="Guardar" class="form-control btn btn-warning">
				</div>

				</section>
			</form>

		</div>
	</section>
</div>

</section>
<section id="main-direccion-operativa" style="display: none;">

<!-- Form dirección operativa -->
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

</section>
<section id="main-banco" style="display: none;">

<!-- Form banco -->
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

</div>

</section>


</form>