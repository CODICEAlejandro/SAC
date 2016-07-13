<?php
defined('BASEPATH') OR exit('No direct access script allowed');

class Reporte_tiempos_tareas_area_ctrl extends CI_Controller {
	public function index($idArea){
		checkSession();

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$data['idArea'] = $idArea;
		$this->load->view('Reporte_tiempos_tareas_area_vw', $data);
	}

	public function doResults($idArea,$condition_tblTareas,$condition_tblErrores){
		$this->load->model("Estadistica");

		$idArea = htmlentities($idArea,ENT_QUOTES,'UTF-8');
		$condition_area = " AND cu.idArea = ".$idArea;

		$this->db->where('id ='.$idArea);
		$result['nombreArea'] = $this->db->get('catarea')->row()->nombre;

		$result['totalTareas'] = ($this->Estadistica->count_where('cattarea AS ct, catusuario AS cu',
																	'1=1
																	AND cu.activo="S" 
																	AND cu.id = ct.idResponsable'
																	.$condition_tblTareas
																	.$condition_area)) 
								+ ($this->Estadistica->count_where('caterror AS ce, catusuario AS cu, cattarea AS ct',
																	'1=1
																	AND cu.activo="S"  
																	AND ct.idResponsable=cu.id 
																	ANd ct.id = ce.idTareaOrigen'
																	.$condition_tblErrores
																	.$condition_area));
		
		$result['totalPendientes'] = ($this->Estadistica->count_where('cattarea AS ct, catusuario AS cu',
																	'ct.idEstado = 1 
																	AND cu.id = ct.idResponsable
																	AND cu.activo="S" '
																	.$condition_tblTareas
																	.$condition_area))
								+ ($this->Estadistica->count_where('caterror AS ce, catusuario AS cu, cattarea AS ct',
																	'ce.idEstado = 1 
																	AND ct.idResponsable=cu.id
																	AND ct.id=ce.idTareaOrigen 
																	AND cu.activo="S" '
																	.$condition_tblErrores
																	.$condition_area));

		$result['totalTerminadas'] = ($this->Estadistica->count_where('cattarea AS ct, catusuario AS cu',
																	'ct.idEstado = 2 
																	AND cu.id = ct.idResponsable
																	AND cu.activo="S" '
																	.$condition_tblTareas
																	.$condition_area)) 
								+ ($this->Estadistica->count_where('caterror AS ce, cattarea AS ct, catusuario AS cu',
																	'ce.idEstado = 2 
																	AND ct.idResponsable = cu.id
																	AND ce.idTareaOrigen = ct.id
																	AND cu.activo="S" '
																	.$condition_tblErrores
																	.$condition_area));

		$result['totalCalificadas'] = ($this->Estadistica->count_where('cattarea AS ct, catusuario AS cu',
																	'ct.idEstado = 3 
																	AND ct.idResponsable = cu.id
																	AND cu.activo="S" '
																	.$condition_tblTareas
																	.$condition_area)) 
								+ ($this->Estadistica->count_where('caterror AS ce, catusuario AS cu, cattarea AS ct',
																	'ce.idEstado = 3 
																	AND ct.idResponsable = cu.id
																	AND ce.idTareaOrigen = ct.id
																	AND cu.activo="S" '
																	.$condition_tblErrores
																	.$condition_area));

		$this->db->order_by('nombre ASC');
		$this->db->where('1=1 AND cu.activo="S" '.$condition_area);
		$resultConsultores = $this->db->get('catusuario AS cu')->result();

		foreach($resultConsultores as $consultor){
			$consultor->totalTareas = $this->Estadistica->count_where('cattarea AS ct, catusuario AS cu',
																'cu.id = ct.idResponsable
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id)
																.$condition_area
																.$condition_tblTareas);
			$consultor->totalTerminadas = $this->Estadistica->count_where('cattarea AS ct, catusuario AS cu', 
																'ct.idResponsable = cu.id
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id)
																.' AND ct.idEstado = 2'
																.$condition_area
																.$condition_tblTareas);
			$consultor->totalCalificadas = $this->Estadistica->count_where('cattarea AS ct, catusuario AS cu', 
																'ct.idResponsable = cu.id
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id) 
																.' AND ct.idEstado = 3'
																.$condition_area
																.$condition_tblTareas);
			$consultor->totalPendientes = $this->Estadistica->count_where('cattarea AS ct, catusuario AS cu', 
																'ct.idResponsable = cu.id
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id) 
																.' AND ct.idEstado = 1'
																.$condition_area
																.$condition_tblTareas);
			$consultor->totalErrores = $this->Estadistica->count_where('cattarea AS ct, caterror AS ce, catusuario AS cu', 
																'ct.idResponsable = cu.id
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id)  
																.' AND ce.idTareaOrigen = ct.id'
																.$condition_area
																.$condition_tblErrores);
			$consultor->totalErroresPendientes = $this->Estadistica->count_where('cattarea AS ct, caterror AS ce, catusuario AS cu', 
																'ct.idResponsable = cu.id
																AND ct.idResponsable = '.($consultor->id) 
																.' AND ce.idTareaOrigen = ct.id
																AND cu.activo="S" 
																AND ce.idEstado = 1'
																.$condition_area
																.$condition_tblErrores);
			//Tiempo total estimado considerando tiempo de errores y de tareas sin regeneración
			$tiempoTotalEstimadoTareas = $this->Estadistica->count_time_field('cattarea AS ct, catusuario AS cu',
																'ct.tiempoEstimado',
																'ct.idResponsable = cu.id
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id)
																.$condition_area
																.$condition_tblTareas);
			$tiempoTotalEstimadoErrores = $this->Estadistica->count_time_field('cattarea AS ct, caterror AS ce, catusuario AS cu',
																'ce.tiempoEstimado',
																'ct.idResponsable = cu.id
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id)
																.' AND ce.idTareaOrigen = ct.id'
																.$condition_area
																.$condition_tblErrores);
			$consultor->tiempoTotalEstimado = $this->Estadistica->addTimes($tiempoTotalEstimadoTareas,$tiempoTotalEstimadoErrores);
			
			//Tiempo total real considerando tiempo de errores y de tareas sin regeneración
			$tiempoTotalRealTareas = $this->Estadistica->count_time_field('cattarea AS ct, catusuario As cu',
																'ct.tiempoRealGerente',
																'ct.idResponsable = cu.id
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id)
																.$condition_area
																.$condition_tblTareas);
			$tiempoTotalRealErrores = $this->Estadistica->count_time_field('cattarea AS ct, caterror AS ce, catusuario AS cu',
																'ce.tiempoRealGerente',
																'ct.idResponsable = cu.id
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id)
																.' AND ce.idTareaOrigen = ct.id'
																.$condition_area
																.$condition_tblErrores);
			$consultor->tiempoTotalReal = $this->Estadistica->addTimes($tiempoTotalRealTareas, $tiempoTotalRealErrores);

			//Tiempo total de tareas y errores pendientes
			$tiempoTotalTareasPendientes = $this->Estadistica->count_time_field('cattarea AS ct, catusuario AS cu',
																'ct.tiempoEstimado',
																'ct.idResponsable = cu.id
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id)
																.' AND ct.idEstado = 1'
																.$condition_area
																.$condition_tblTareas);
			$tiempoTotalErroresPendientes = $this->Estadistica->count_time_field('cattarea AS ct, catusuario AS cu, caterror AS ce',
																'ce.tiempoEstimado',
																'ct.idResponsable = cu.id
																AND cu.activo="S" 
																AND ct.idResponsable = '.($consultor->id)
																.' AND ce.idTareaOrigen = ct.id
																AND ce.idEstado = 1'
																.$condition_area
																.$condition_tblErrores);

			$consultor->tiempoTotalPendientes = $this->Estadistica->addTimes($tiempoTotalTareasPendientes, $tiempoTotalErroresPendientes);

			//Tiempo total disponible

			$consultor->tiempoTotalDisponible = 0;
		}

		$result['areas'] = $resultConsultores;
		$result['totalErrores'] = $this->Estadistica->count_where('caterror AS ce, catusuario AS cu, cattarea AS ct',
																	'1=1 
																	AND cu.activo="S" 
																	AND ct.idResponsable = cu.id
																	AND ce.idTareaOrigen = ct.id
																	'.$condition_tblErrores.$condition_area);

		
		$tiempoTotalEstimadoTareas =  $this->Estadistica->count_time_field('cattarea AS ct, catusuario AS cu',
																	'ct.tiempoEstimado', 
																	'1=1 
																	AND cu.activo="S" 
																	AND ct.idResponsable = cu.id'
																	.$condition_tblTareas.$condition_area);
		$tiempoTotalEstimadoErrores = $this->Estadistica->count_time_field('caterror AS ce, cattarea AS ct, catusuario AS cu',
																	'ce.tiempoEstimado', 
																	'1=1 
																	AND cu.activo="S" 
																	AND ct.idResponsable = cu.id
																	AND ce.idTareaOrigen = ct.id'
																	.$condition_tblErrores.$condition_area);
		$result['tiempoTotalEstimado'] = $this->Estadistica->addTimes($tiempoTotalEstimadoTareas,$tiempoTotalEstimadoErrores);

		$tiempoTotalRealTareas = $this->Estadistica->count_time_field('cattarea AS ct, catusuario AS cu',
																	'ct.tiempoRealGerente', 
																	'1=1 
																	AND cu.activo="S" 
																	AND ct.idResponsable = cu.id'
																	.$condition_tblTareas.$condition_area);
		$tiempoTotalRealErrores = $this->Estadistica->count_time_field('caterror AS ce, catusuario AS cu, cattarea AS ct',
																	'ce.tiempoRealGerente', 
																	'1=1 
																	AND ct.idResponsable = cu.id
																	AND ce.idTareaOrigen = ct.id
																	AND cu.activo="S" '
																	.$condition_tblErrores.$condition_area);
		$result['tiempoTotalReal'] = $this->Estadistica->addTimes($tiempoTotalRealTareas,$tiempoTotalRealErrores);

		return $result;		
	}

	public function formatString($data){
		$areas = $data['areas'];
		$totalPendientes = $data['totalPendientes'];
		$totalTerminadas = $data['totalTerminadas'];
		$totalCalificadas = $data['totalCalificadas'];
		$totalTareas = $data['totalTareas'];
		$totalErrores = $data['totalErrores'];
		$tiempoTotalEstimado = $data['tiempoTotalEstimado'];
		$tiempoTotalReal = $data['tiempoTotalReal'];
		?>
		<div class="row">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<h4>Pendientes: <?php echo $totalPendientes; ?></h4>
				<h4>Terminados: <?php echo $totalTerminadas; ?></h4>
				<h4>Calificados: <?php echo $totalCalificadas; ?></h4>
				<h4>Total de tareas: <?php echo $totalTareas; ?></h4>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<h4>Errores: <?php echo $totalErrores; ?></h4>
				<br>
				<h4>Tiempo total real: <?php echo $tiempoTotalReal; ?></h4>
				<h4>Tiempo total estimado: <?php echo $tiempoTotalEstimado; ?></h4>
			</div>
		</div>
		<div class="row">
		<?php foreach($areas as $area){ ?>
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			<div class="APanel" id-area="<?php echo $area->id; ?>">
				<div class="APanelTitle">
					<h2><?php echo $area->nombre; ?></h2>
				</div>
				<div class="APanelBody">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 APanelLeft">
						<label>Número de tareas: </label>
						<span id="numeroTareas"><?php echo $area->totalTareas; ?></span><br>
						<label>Número de tareas terminadas: </label>
						<span id="numeroTerminadas"><?php echo $area->totalTerminadas; ?></span><br>
						<label>Número de tareas calificadas: </label>
						<span id="numeroCalificadas"><?php echo $area->totalCalificadas; ?></span><br>
						<label>Número de tareas pendientes: </label>
						<span id="numeroPendientes"><?php echo $area->totalPendientes; ?></span><br>
						<label>Número de errores: </label>
						<span id="numeroErrores"><?php echo $area->totalErrores; ?></span><br>
						<label>Número de errores pendientes: </label>
						<span id="numeroErroresPendientes"><?php echo $area->totalErroresPendientes; ?></span>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 APanelRight">
						<label>Tiempo total estimado: </label>
						<span id="tiempoTotalEstimado"><?php echo $area->tiempoTotalEstimado; ?></span><br>
						<label>Tiempo total real: </label>
						<span id="tiempoTotalReal"><?php echo $area->tiempoTotalReal; ?></span><br>
						<label>Tiempo total de tareas pendientes: </label>
						<span id="tiempoTotalPendientes"><?php echo $area->tiempoTotalPendientes; ?></span><br>
						<label>Tiempo total disponible: </label>
						<span id="tiempoTotalDisponible"><?php echo $area->tiempoTotalDisponible; ?></span>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		</div>
		<?php
	}

	public function refreshData($idArea=-1){
		$post = $this->input->post();

		$dateDesde = (isset($post['dateDesde']) && !empty($post['dateDesde']))? explode('/', $post['dateDesde']): array('00','00','0000');
		$dateHasta = (isset($post['dateHasta']) && !empty($post['dateHasta']))? explode('/', $post['dateHasta']): array('00','00','0000');

		if(count($dateDesde) != 3) $dateDesde = array('00','00','0000');
		if(count($dateHasta) != 3) $dateHasta = array('00','00','0000');

		$dayDesde = htmlentities($dateDesde[0], ENT_QUOTES, 'UTF-8');
		$monthDesde = htmlentities($dateDesde[1], ENT_QUOTES, 'UTF-8');
		$yearDesde = htmlentities($dateDesde[2], ENT_QUOTES, 'UTF-8');

		$dayHasta = htmlentities($dateHasta[0], ENT_QUOTES, 'UTF-8');
		$monthHasta = htmlentities($dateHasta[1], ENT_QUOTES, 'UTF-8');
		$yearHasta = htmlentities($dateHasta[2], ENT_QUOTES, 'UTF-8');

		$dateDesde = $yearDesde.'-'.$monthDesde.'-'.$dayDesde;
		$dateHasta = $yearHasta.'-'.$monthHasta.'-'.$dayHasta;

		$condition_tblTareas = " AND ct.creacion BETWEEN '".$dateDesde."' AND DATE_ADD('".$dateHasta."', INTERVAL 1 DAY)";
		$condition_tblErrores = " AND ce.creacion BETWEEN '".$dateDesde."' AND DATE_ADD('".$dateHasta."', INTERVAL 1 DAY)";

		$this->formatString($this->doResults($idArea,$condition_tblTareas,$condition_tblErrores));
	}
}
?>