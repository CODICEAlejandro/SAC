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

	<section id="sc-agenda" class="form-agenda" style="padding-top: 20px; clear: both; display: none;">

		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<form
				class="form_agenda"
			>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-11 col-sm-11 col-md-5 col-lg-5">
							<label>Nombre</label>
							<input type="text" name="nombre" id="nombre" class="form-control">
						</div>
						<div class="col-xs-11 col-sm-11 col-md-6 col-lg-6">
							<label>Apellido</label>
							<input type="text" name="nombre" id="nombre" class="form-control">
						</div>
						<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
							<button id="ver-detalle-contacto" class="btn btn-warning" style="margin-top: 25px; width: 100%;">Ver</button>
						</div>						
					</div>
				</div>
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
			</form>
		</div>

	</section>

</div>
