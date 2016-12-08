<!-- Form agenda -->
<section id="main-agenda" style="display: none;">

<div class="row dotted-bottom">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<h3 class="sectionTitle">Agenda</h3>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<button class="form-control btn btn-primary" id="btn-agrega-contacto">
			<span class="glyphicon glyphicon-plus-sign"></span>
		</button>
	</div>

	<div id="append-section-contacto">
	</div>				

	<div id="existent-section-contacto">
	</div>

	<section id="sc-agenda" class="form-agenda-section" style="padding-top: 20px; clear: both; display: none;">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<form
				class="form_agenda"
			>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label>Nombre</label>
							<input type="text" name="nombre" id="nombre" class="form-control">
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label>Apellido</label>
							<input type="text" name="apellido" id="apellido" class="form-control">
						</div>
						<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
							<label>Correo</label>
							<input type="text" name="correo" id="correo" class="form-control">
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<label>Nota</label>
							<input type="text" name="nota" id="nota" class="form-control">
						</div>
						<div class="col-xs-1 col-sm-1 col-md-2 col-lg-2">
							<label>Tipo</label>
							<select name="idTipoContacto" id="idTipoContacto" class="form-control">
							<?php foreach($tiposContacto as $tipo){ ?>
							<option value="<?php echo $tipo->id; ?>"><?php echo $tipo->descripcion; ?></option>
							<?php } ?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
							<section id="sc-actions-contacto">
								<button id="btn-ver-detalle-contacto" class="btn btn-warning" style="margin-top: 25px; width: 50%;">Ver</button>
								<button id="btn-eliminar-contacto" class="btn btn-danger" style="margin-top: 25px;">Eliminar</button>
							</section>
						</div>						
					</div>
				</div>

				<section id = "sc-data-detail">

				<div class="form-group">
					<div class="row">
						<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
							<label>Lada</label>
							<input type="text" name="lada" id="lada" class="form-control">
						</div>
						<div class="col-xs-8 col-sm-8 col-md-10 col-lg-10">
							<label>Teléfono</label>
							<input type="text" name="telefono" id="telefono" class="form-control">
						</div>
						<div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
							<label>Extensión</label>
							<input type="text" name="extension" id="extension" class="form-control">
						</div>
					</div>
				</div>

				<div class="form-group">
					<input class="form-control btn btn-warning" type="submit" value="Guardar">
				</div>

				</section>
			</form>
		</div>

	</section>

</div>

</section>
<!-- Form perfiles -->

<section id="main-perfiles" style="display: none;">

<div class="row dotted-bottom">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<h3 class="sectionTitle">Perfiles</h3>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<button class="form-control btn btn-primary" id="btn-agrega-perfil">
			<span class="glyphicon glyphicon-plus-sign"></span>
		</button>
	</div>

	<div id="append-section-perfil">
	</div>				

	<div id="existent-section-perfil">
	</div>

	<section id="sc-perfil" class="form-perfil-section" style="padding-top: 20px; clear: both; display: none;">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<form
				class="form_perfil"
			>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
							<label>Tipo</label>
							<select name="idPerfil" id="idPerfil" class="form-control">
							<?php foreach($perfiles as $perfil){ ?>
								<option value="<?php echo $perfil->id; ?>"><?php echo $perfil->descripcion; ?></option>
							<?php } ?>
							</select>
						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
							<div class="form-group">
								<button id="btn-eliminar-perfil" class="form-control btn btn-danger" style="margin-top: 25px;">Eliminar</button>
							</div>
						</div>	
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
							<div class="form-group">
								<input type="submit" class="form-control btn btn-warning" value="Guardar" style="margin-top: 25px;">
							</div>
						</div>
					</div>
				</div>

			</form>
		</div>

	</section>

</div>

</section>

<!-- Form Servicios -->

<section id="main-servicios" style="display: none;">

<div class="row dotted-bottom">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<h3 class="sectionTitle">Servicios</h3>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<button class="form-control btn btn-primary" id="btn-agrega-servicio">
			<span class="glyphicon glyphicon-plus-sign"></span>
		</button>
	</div>

	<div id="append-section-servicio">
	</div>				

	<div id="existent-section-servicio">
	</div>

	<section id="sc-servicio" class="form-servicio-section" style="padding-top: 20px; clear: both; display: none;">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<form
				class="form_servicio"
			>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
							<label>Tipo</label>
							<select name="idServicio" id="idServicio" class="form-control">
							<?php foreach($servicios as $servicio){ ?>
								<option value="<?php echo $servicio->id; ?>"><?php echo $servicio->descripcion; ?></option>
							<?php } ?>
							</select>
						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
							<div class="form-group">
								<button id="btn-eliminar-servicio" class="form-control btn btn-danger" style="margin-top: 25px;">Eliminar</button>
							</div>
						</div>	
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
							<div class="form-group">
								<input type="submit" class="form-control btn btn-warning" value="Guardar" style="margin-top: 25px;">
							</div>
						</div>
					</div>
				</div>

			</form>
		</div>

	</section>

</div>

</section>
