<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>JOBS</title>
	<?php includeJQuery(); ?>
	<?php includeBootstrap(); ?>
	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/mainFunctions.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/JSControllers/Cotizacion/Editar_cotizacion_JS.js"></script>
	
</head>
<body>
	<?=$menu ?>
	<input type="hidden" id="cotizacion_id" value="<?php echo $cotizacion->id; ?>">
	<input type="hidden" id="tipo_cotizacion_id" value="<?php echo $cotizacion->tipo_cotizacion_id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				
				<!-- Sección de información general de la cortización -->

				<div class="form-group">
					<label>Cliente (*)</label>
					<select class="form-control" id="id-cliente">
						<option value="-1">Selecciona una opción</option>
						
						<?php foreach($clientes as $c){ ?>
							<option value="<?php echo $c->id; ?>" <?php if($c->id==$cotizacion->id_cliente){echo "selected";}?>>
								<?php echo $c->nombre; ?>
							</option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>Contacto (*)</label>
					<select class="form-control" id="id-contacto">
						<option value="-1">Selecciona una opción</option>
						<?php foreach ($contactos as $c) { ?>
							<option value="<?php echo $c->id;?>" <?php if ($c->id == $cotizacion->id_contacto) {
								echo "selected";
							} ?>>
								<?php echo $c->nombre." ".$c->apellido." (".$c->tipo_contacto.")"; ?>
							</option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>Título (*)</label>
					<input type="text" id="titulo-cotizacion" class="form-control" value="<?php echo $cotizacion->titulo;?>">
				</div>
				<div class="form-group">
					<label>Forma de pago (*)</label>
					<select class="form-control" id="id-forma-de-pago">
						<option value="-1">Selecciona una opción</option>
						<?php foreach($forma_pago as $f){ ?>
						<option value="<?php echo $f->id; ?>" <?php if($f->id == $cotizacion->tipo_cotizacion_id){echo "selected";} ?>><?php echo $f->clave; ?></option>
						<?php } ?>
					</select>
				</div>

				<form enctype="multipart/form-data" id="form-archivo-adjunto">
					<div class="form-group">
						<label for="archivo-adjunto">Archivo adjunto</label>
						<input type="file" name="archivo-adjunto" id="archivo-adjunto" class="form-control" value="<?php echo $cotizacion->nombre_archivo;?>">
						<label for="archivo-adjunto">
							<?php if (!empty($cotizacion->nombre_archivo)){echo "Archivo: ".$cotizacion->nombre_archivo;} ?>
						</label>
					</div>
				</form>

				<div class="form-group">
					<label for="objetivo-cotizacion">Objetivo (*)</label>
					<textarea class="form-control" id="objetivo-cotizacion" rows="5"><?php echo $cotizacion->objetivo;?></textarea>
				</div>
				<div class="form-group">
					<label for="introduccion-cotizacion">Introducción (*)</label>
					<textarea class="form-control" id="introduccion-cotizacion" rows="5"><?php echo $cotizacion->introduccion; ?></textarea>
				</div>
				<div class="form-group">
					<label for="requerimientos-cotizacion">Requerimientos (*)</label>
					<textarea class="form-control" id="requerimientos-cotizacion" rows="5"><?php echo $cotizacion->requerimientos; ?></textarea>
				</div>
				<div class="form-group">
					<label for="nota-cotizacion">Notas</label>
					<textarea class="form-control" id="nota-cotizacion" rows="5"><?php echo $cotizacion->nota; ?></textarea>
				</div>

				<!-- Sección de alcances -->

				<div class="form-group">
					<button class="form-control btn btn-info" id="btn-agregar-alcance">Agregar alcance</button>
				</div>

					
				<div id="append-section-alcance">
					
					<!--Se cargan alcances por cotizacion-->
					<?php if(!empty($alcances)){ 
						for($i=0,$n=count($alcances);$i<$n;$i++){
							$a = $alcances[$i];
							//print_r($a);
					?>
					<div  class="clone-section-alcance">
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<div class="form-group">
									<label>Título (*)</label>
									<input type="text" id="titulo-alcance" class="form-control"
									value="<?php echo $a->titulo; ?>">
								</div>
							</div>
							<div class="col-xs-2 col-sm-1 col-md-1 col-lg-1">
								<div class="form-group">
									<label>Orden</label>
									<input type="text" id="orden-alcance" class="form-control" value="<?php echo $a->orden; ?>" disabled>
								</div>
							</div>
							<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="margin-top: 25px;">
								<button class="btn btn-primary" id="btn-up-alcance">
									<span class="glyphicon glyphicon-chevron-up"></span>
								</button>
								<button class="btn btn-info" id="btn-down-alcance">
									<span class="glyphicon glyphicon-chevron-down"></span>
								</button>
							</div>
							<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2" style="margin-top: 25px;">
								<div class="form-group">
									<button class="form-control btn btn-primary" id="btn-minus-alcance">-</button>
								</div>
							</div>
							<div class="col-xs-3 col-sm-1 col-md-1 col-lg-1" style="margin-top: 25px;">
								<div class="form-group">
									<button class="form-control btn btn-danger" id="btn-delete-alcance">x</button>
								</div>
							</div>

							<div id="minus-section" class="minus-section-carga" data-visible=1>

								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<div class="form-group">
										<label>Servicio (*)</label>
										<select class="form-control id-servicio-alcance" id="id-servicio-alcance">
											<option value="-1">Seleccione una opción</option>
											<?php foreach($servicio_alcance as $s){ ?>
											<option value="<?php echo $s->id; ?>" <?php if($s->id == $a->tipo_concepto_sel){echo "selected";} ?>>
												<?php echo $s->descripcion; ?>		
											</option>
											<?php } //Fin for servicio alcance ?>
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
									<div class="form-group">
										<label>Clasificación (*)</label>
										<select class="form-control" id="id-clasificacion-alcance">
											<option value="-1">Seleccione una opción</option>
											<?php 
											$clas_sel = $a->id_clasificacion_servicio;
											for($l=0,$n_clas=count($a->clasificaciones);$l<$n_clas;$l++){ 
												$clas = $a->clasificaciones[$l];
											?>
											<option value="<?php echo $clas->id; ?>"  
												<?php if($clas->id == $clas_sel){
													echo "selected";
												} ?>
											>
												<?php echo $clas->clave; ?>
											</option>
											<?php } //Fin for clasificaciones ?>
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<button class="btn btn-warning form-control" id="btn-agregar-descripcion">Agregar descripción (*)</button>	
									</div>
									<!-- Sección de descriciones del alcance -->
									<div id="append-section-descripcion">
										<!--Se cargan descripciones de alcance-->
										<?php if(!empty($a->descripciones)){ 
											for ($j=0,$n_desc=count($a->descripciones); $j < $n_desc; $j++) { 
												$d = $a->descripciones[$j];
										?>
										<div id="clone-section-descripcion" class="clone-section-descripcion">
											<div class="row">
												<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
													<div class="form-group">
														<label>Título (*)</label>
														<input type="text" id="titulo-descripcion" class="form-control"
														value="<?php echo $d->titulo; ?>">
													</div>
												</div>
												<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="margin-top: 25px;">
													<div class="form-group">
														<button class="form-control btn-primary" id="btn-minus-descripcion">-</button>
													</div>
												</div>

												<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="margin-top: 25px;">
													<div class="form-group">
														<button class="form-control btn-danger" id="btn-delete-descripcion">x</button>
													</div>
												</div>

												<div id="minus-section" data-visible=1>

												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<div class="form-group">
														<label>Descripción (*)</label>
														<textarea id="contenido-descripcion" class="form-control" rows="5"><?php echo $d->descripcion; ?></textarea>
													</div>
												</div>

												</div>
											</div>
										</div>
										<?php  
											} //Fin for descripciones
										} //Fin if descripciones
										?>

									</div>	
									
									
								</div>
								<!--
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<label>Requerimientos (*)</label>
										<textarea class="form-control" rows="5" id="requerimientos-alcance"><?php echo $a->requerimientos; ?></textarea>
									</div>
								</div>
								-->
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<label>Entregables (*)</label>
										<textarea class="form-control" rows="5" id="entregables-alcance"><?php echo $a->entregables; ?></textarea>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<label>Fecha de inicio del servicio (*)</label>
										<input type="text" id="fecha-inicio-servicio" class="form-control fecha-inicio-servicio fecha-inicio-servicio-carga" >
										<input type="text" id="fecha-inicio-servicio-alt" class="form-control fecha-inicio-servicio-alt fecha-inicio-servicio-alt-carga" value="<?php echo $a->fecha_inicio_servicio; ?>">
									</div>
								</div>
							
					
								<?php if ($cotizacion->tipo_cotizacion_id == 1) { //Pagos recurrentes  ?>
									<?php if(!empty($a->pago_recurrente)){ 
										$pr = $a->pago_recurrente;
									?>
									<!--Se cargan pagos recurrentes del alcance-->
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bloque-pagos-recurrentes pagos-recurrentes-carga" id="bloque-pagos-recurrentes"
									style="width: 100%; border-bottom: 2px red dotted; margin-bottom: 15px;">
										<!-- Pagos recurrentes -->
										<div class="form-group">
											<label>Periodicidad</label>
											<select id="id-periodicidad" class="form-control">
												<option value="-1">Seleccione una opción</option>
												<?php foreach($periodicidad as $p){ ?>
												<option value="<?php echo $p->id; ?>" <?php if($p->id == $pr->id_periodicidad){echo "selected";} ?>>
													<?php echo $p->clave; ?>	
												</option>
												<?php } ?>
											</select>
										</div>
										<div class="form-group">
											<label>Número de parcialidades</label>
											<input type="number" id="numero-parcialidades" class="form-control" 
											value="<?php echo $pr->numero_parcialidades; ?>">
										</div>
										<div class="form-group">
											<label>Monto de la parcialidad</label>
											<input type="number" id="monto-parcialidad" class="form-control"
											value="<?php echo $pr->monto_parcialidad; ?>">
										</div>
									</div>
									<?php } //Fin if pagos recurrentes ?>

								<?php }else if ($cotizacion->tipo_cotizacion_id==2) { //Pagos fijos  ?>
									<?php if(!empty($a->parcialidades)){ 
										$p_anticipo = $a->parcialidades[0];
									?>
										<!--Se cargan pagos fijos del alcance-->
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bloque-pagos-fijos pagos-fijos-carga" id="bloque-pagos-fijos" style="width: 100%; border-bottom: 2px yellow dotted; margin-bottom: 15px;">
											<!-- Pagos fijos -->
											<div class="form-group">
												<label>Precio total</label>
												<input type="number" id="precio-total" class="form-control precio-total" 
												value="<?php echo $a->monto_total; ?>">
											</div>
											<div class="form-group">
												<label>Porcentaje de anticipo (De 0 a 100)</label>
												<input type="number" id="porcentaje-anticipo" class="form-control porcentaje-anticipo" 
												value="<?php echo $p_anticipo->porcentaje_monto; ?>">
											</div>
											<div class="form-group">
												<label>Monto de anticipo</label>
												<input type="number" id="monto-anticipo" class="form-control monto-anticipo" 
												value="<?php echo $p_anticipo->monto_parcialidad; ?>">
											</div>
											<div class="form-group">
												<button id="btn-agregar-parcialidad" class="btn btn-primary form-control btn-agregar-parcialidad">Agregar parcialidad</button>
											</div>
											

											<div id="append-section-parcialidad">
												<?php if(count($a->parcialidades)>1){ 
													for($k=1,$n_parc=count($a->parcialidades);$k<$n_parc;$k++){
														$p = $a->parcialidades[$k];
												?>
													<!--Se cargan parcialidades del alcance si es que tiene-->
													<!-- Clone section parcialidad -->
													<div  class="clone-section-parcialidad">
														<div class="row">
															<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin-top: 25px;">
																<div class="form-group">
																	<button class="form-control btn btn-danger btn-delete-parcialidad" id="btn-delete-parcialidad">x</button>
																</div>
															</div>
															<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
																<div class="form-group">
																	<label>Concepto</label>
																	<input type="text" id="concepto-parcialidad" class="form-control"
																	value="<?php echo $p->concepto; ?>">
																</div>
															</div>
															<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
																<div class="form-group">
																	<label>Fecha</label>
																	<input type="text" id="fecha-parcialidad" class="form-control fecha-parcialidad fecha-parcialidad-carga"">
																	<input type="text" id="fecha-parcialidad-alt" class="form-control fecha-parcialidad-alt fecha-parcialidad-alt-carga" value="<?php echo $p->fecha; ?>">
																</div>
															</div>
															<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
																<div class="form-group">
																	<label>Porcentaje</label>
																	<input type="number" id="porcentaje-parcialidad" class="form-control porcentaje-parcialidad" value="<?php echo $p->porcentaje_monto; ?>">
																</div>
															</div>
															<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
																<div class="form-group">
																	<label>Monto</label>
																	<input type="number" id="monto-parcialidad" class="form-control monto-parcialidad" value="<?php echo $p->monto_parcialidad; ?>">
																</div>
															</div>
														</div>
													</div>
													<!-- Fin clone section parcialidad -->
												<?php 
													} //Fin for pagos fijos parcialidades
												} 	//Fin if pagos fijos parcialidades
												?>
											</div>
										</div>
									<?php } //Fin if empty parcialidades 
									} //Fin else if pagos fijos
									?>
							
							</div>
						</div>
					</div>
					<?php 
						} //fin for alcances
					} //Fin if alcances
					?>

				</div>

				<div class="form-group">
					<input type="submit" id="btn-guardar-cotizacion" class="form-control btn btn-default" value="Guardar cotización">
				</div>
			</div>
		</div>
	</div>
	



	<!-- Clone sections -->
	<div id="clone-sections" style="display: none;">
		
	<!-- Clone section alcance -->
	<div id="clone-section-alcance" class="clone-section-alcance">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Título (*)</label>
					<input type="text" id="titulo-alcance" class="form-control">
				</div>
			</div>
			<div class="col-xs-2 col-sm-1 col-md-1 col-lg-1">
				<div class="form-group">
					<label>Orden</label>
					<input type="text" id="orden-alcance" class="form-control" disabled>
				</div>
			</div>
			<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2" style="margin-top: 25px;">
				<button class="btn btn-primary" id="btn-up-alcance">
					<span class="glyphicon glyphicon-chevron-up"></span>
				</button>
				<button class="btn btn-info" id="btn-down-alcance">
					<span class="glyphicon glyphicon-chevron-down"></span>
				</button>
			</div>
			<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2" style="margin-top: 25px;">
				<div class="form-group">
					<button class="form-control btn btn-primary" id="btn-minus-alcance">-</button>
				</div>
			</div>
			<div class="col-xs-3 col-sm-1 col-md-1 col-lg-1" style="margin-top: 25px;">
				<div class="form-group">
					<button class="form-control btn btn-danger" id="btn-delete-alcance">x</button>
				</div>
			</div>

			<div id="minus-section" data-visible=1>

			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Servicio (*)</label>
					<select class="form-control id-servicio-alcance" id="id-servicio-alcance">
						<option value="-1">Seleccione una opción</option>
						<?php foreach($servicio_alcance as $s){ ?>
						<option value="<?php echo $s->id; ?>"><?php echo $s->descripcion; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Clasificación (*)</label>
					<select class="form-control" id="id-clasificacion-alcance">
						<option value="-1">Seleccione una opción</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<button class="btn btn-warning form-control" id="btn-agregar-descripcion">Agregar descripción (*)</button>	
				</div>
				<!-- Sección de descriciones del alcance -->
				<div id="append-section-descripcion"></div>	
			</div>
			<!--
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Requerimientos (*)</label>
					<textarea class="form-control" rows="5" id="requerimientos-alcance"></textarea>
				</div>
			</div>
			-->
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Entregables (*)</label>
					<textarea class="form-control" rows="5" id="entregables-alcance"></textarea>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Fecha de inicio del servicio (*)</label>
					<input type="text" id="fecha-inicio-servicio" class="form-control fecha-inicio-servicio">
					<input type="text" id="fecha-inicio-servicio-alt" class="form-control fecha-inicio-servicio-alt">
				</div>
			</div>

			<!-- Opcionales: si se seleccionó pago recurrente o pago fijo -->
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bloque-pagos-recurrentes" id="bloque-pagos-recurrentes"
			style="width: 100%; border-bottom: 2px blue dotted; margin-bottom: 15px;">
				<!-- Pagos recurrentes -->
				<div class="form-group">
					<label>Periodicidad</label>
					<select id="id-periodicidad" class="form-control">
						<option value="-1">Seleccione una opción</option>
						<?php foreach($periodicidad as $p){ ?>
						<option value="<?php echo $p->id ?>"><?php echo $p->clave; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>Número de parcialidades</label>
					<input type="number" id="numero-parcialidades" class="form-control">
				</div>
				<div class="form-group">
					<label>Monto de la parcialidad</label>
					<input type="number" id="monto-parcialidad" class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bloque-pagos-fijos" id="bloque-pagos-fijos">
				<!-- Pagos fijos -->
				<div class="form-group">
					<label>Precio total</label>
					<input type="number" id="precio-total" class="form-control precio-total" value="0">
				</div>
				<div class="form-group">
					<label>Porcentaje de anticipo (De 0 a 100)</label>
					<input type="number" id="porcentaje-anticipo" class="form-control porcentaje-anticipo" value="0">
				</div>
				<div class="form-group">
					<label>Monto de anticipo</label>
					<input type="number" id="monto-anticipo" class="form-control monto-anticipo" value="0">
				</div>
				<div class="form-group">
					<button id="btn-agregar-parcialidad" class="btn btn-primary form-control btn-agregar-parcialidad">Agregar parcialidad</button>
				</div>
				<div id="append-section-parcialidad"></div>
			</div>

			</div>
		</div>
	</div>
	<!-- Fin de clone section alcance -->

	<!-- Clone section descripcion -->
	<div id="clone-section-descripcion" class="clone-section-descripcion">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Título (*)</label>
					<input type="text" id="titulo-descripcion" class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="margin-top: 25px;">
				<div class="form-group">
					<button class="form-control btn-primary" id="btn-minus-descripcion">-</button>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="margin-top: 25px;">
				<div class="form-group">
					<button class="form-control btn-danger" id="btn-delete-descripcion">x</button>
				</div>
			</div>

			<div id="minus-section" data-visible=1>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Descripción (*)</label>
					<textarea id="contenido-descripcion" class="form-control" rows="5"></textarea>
				</div>
			</div>

			</div>
		</div>
	</div>
	<!-- Fin de clone section descripcion -->

	<!-- Clone section parcialidad -->
	<div id="clone-section-parcialidad" class="clone-section-parcialidad">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin-top: 25px;">
				<div class="form-group">
					<button class="form-control btn btn-danger btn-delete-parcialidad" id="btn-delete-parcialidad">x</button>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Concepto</label>
					<input type="text" id="concepto-parcialidad" class="form-control">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label>Fecha</label>
					<input type="text" id="fecha-parcialidad" class="form-control fecha-parcialidad">
					<input type="text" id="fecha-parcialidad-alt" class="form-control fecha-parcialidad-alt">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Porcentaje</label>
					<input type="number" id="porcentaje-parcialidad" class="form-control porcentaje-parcialidad" value="0">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label>Monto</label>
					<input type="number" id="monto-parcialidad" class="form-control monto-parcialidad" value="0">
				</div>
			</div>
		</div>
	</div>
	<!-- Fin clone section parcialidad -->

	</div>
	<!-- Fin de clone sections -->
</body>
