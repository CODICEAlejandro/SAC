<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'includes/tcpdf/tcpdf.php';

ini_set("allow_url_fopen", 1);

class Listado_cotizacion_ctrl extends CI_Controller{
	public function index(){
		$data["clientes"] = $this->db->query("select * from catcliente where estadoActivo=1 and tipo=0")->result();
		$data["forma_pago"]= $this->db->query("select * from cat_tipo_cotizacion")->result();
		$data["servicio_alcance"] = $this->db->query("select * from cattipoconcepto where tipo = 0")->result();
		/*
		$data["cotizaciones"]= $this->db->query("SELECT cli.nombre nombre_cli, usu.nombre nombre_acc, usu.correo correo,cot.* 
												FROM cotizacion cot
												JOIN catcliente cli ON cot.idCliente = cli.id
												JOIN catusuario usu ON cot.accountManager = usu.id
												ORDER BY creacion DESC LIMIT 10")->result();

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		*/
		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$data["cotizaciones"]= $this->db->query("SELECT cli.nombre nombre_cli, usu.nombre nombre_acc,con.nombre nombre_con, con.apellido apellido_con, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
		FROM cotizacion_account cot
		JOIN catcliente cli ON cot.id_cliente = cli.id
		JOIN contacto con ON cot.id_contacto = con.id
		JOIN catusuario usu ON cot.id_usuario = usu.id
		JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
		ORDER BY cot.fecha_alta DESC LIMIT 30")->result();

		$this->load->view("Cotizacion/Listado_cotizacion_vw", $data);
	}

	public function busquedaRegistros()
	{
		if (isset($_POST) && isset($_POST["parametro"])) {
			$parametro = $_POST["parametro"];
			$parametro = htmlentities($parametro,ENT_QUOTES,'UTF-8');

			$cotizaciones= $this->db->query("
				SELECT cli.nombre nombre_cli, usu.nombre nombre_acc,con.nombre nombre_con, con.apellido apellido_con, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
				FROM cotizacion_account cot
				JOIN catcliente cli ON cot.id_cliente = cli.id
				JOIN contacto con ON cot.id_contacto = con.id
				JOIN catusuario usu ON cot.id_usuario = usu.id 
				JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
				WHERE (cot.folio LIKE '%".$parametro."%'
				OR cot.titulo LIKE '%".$parametro."%')
				ORDER BY cot.fecha_alta DESC
				")->result();

			echo json_encode($cotizaciones);
		}
	}

	public function traeClientes()
	{
		$clientes = $this->db->query("SELECT id,nombre FROM catcliente WHERE tipo=0 AND estadoActivo=1 ORDER BY nombre")->result();
		echo json_encode($clientes);
	}

	public function traeAccounts()
	{
		$accounts = $this->db->query("SELECT id,nombre FROM account_manager WHERE activo='S'")->result();
		echo json_encode($accounts);
	}

	public function traeStatus()
	{
		$status = $this->db->query("SELECT id, clave nombre FROM cat_status_cotizacion")->result();
		echo json_encode($status);
	}

	public function buscaPorFechaAlta(){

		if(isset($_POST)){

			$fecha_inicio = $_POST["fecha_inicio"];
			$fecha_fin = $_POST["fecha_fin"];

			$query_fecha = "SELECT cli.nombre nombre_cli, usu.nombre nombre_acc,con.nombre nombre_con, con.apellido apellido_con, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
				FROM cotizacion_account cot
				JOIN catcliente cli ON cot.id_cliente = cli.id
				JOIN contacto con ON cot.id_contacto = con.id
				JOIN catusuario usu ON cot.id_usuario = usu.id 
				JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
				WHERE (cot.fecha_alta >='".$fecha_inicio."' AND cot.fecha_alta<='".$fecha_fin."')
				ORDER BY cot.fecha_alta DESC";
			$cotizaciones = $this->db->query($query_fecha)->result();

			echo json_encode($cotizaciones);

		}
	}

	public function buscaPorCliente(){

		if(isset($_POST)){

			$idCliente = $_POST["id"];

			$query_cliente = "SELECT cli.nombre nombre_cli, usu.nombre nombre_acc,con.nombre nombre_con, con.apellido apellido_con, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
				FROM cotizacion_account cot
				JOIN catcliente cli ON cot.id_cliente = cli.id
				JOIN contacto con ON cot.id_contacto = con.id
				JOIN catusuario usu ON cot.id_usuario = usu.id 
				JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
				WHERE cot.id_cliente=".$idCliente." 
				ORDER BY cot.fecha_alta DESC";
			$cotizaciones = $this->db->query($query_cliente)->result();

			echo json_encode($cotizaciones);

		}
	}

	public function buscaPorAccount(){

		if(isset($_POST)){

			$idAccount = $_POST["id"];

			$query_account = "SELECT cli.nombre nombre_cli, usu.nombre nombre_acc,con.nombre nombre_con, con.apellido apellido_con, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
				FROM cotizacion_account cot
				JOIN catcliente cli ON cot.id_cliente = cli.id
				JOIN contacto con ON cot.id_contacto = con.id
				JOIN catusuario usu ON cot.id_usuario = usu.id 
				JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
				WHERE cot.id_usuario=".$idAccount." 
				ORDER BY cot.fecha_alta DESC";
			$cotizaciones = $this->db->query($query_account)->result();

			echo json_encode($cotizaciones);

		}
	}

	public function buscaPorStatus(){

		if(isset($_POST)){

			$idStatus = $_POST["id"];

			$query_status = "SELECT cli.nombre nombre_cli, usu.nombre nombre_acc,con.nombre nombre_con, con.apellido apellido_con, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
				FROM cotizacion_account cot
				JOIN catcliente cli ON cot.id_cliente = cli.id
				JOIN contacto con ON cot.id_contacto = con.id
				JOIN catusuario usu ON cot.id_usuario = usu.id 
				JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
				WHERE cot.status_cotizacion_id=".$idStatus." 
				ORDER BY cot.fecha_alta DESC";
			$cotizaciones = $this->db->query($query_status)->result();

			echo json_encode($cotizaciones);

		}
	}


	public function buscaPorFacturaVigente(){

		if(isset($_POST)){

			$fecha = $_POST["fecha"];

			$query_fecha = "SELECT cli.nombre nombre_cli, usu.nombre nombre_acc,con.nombre nombre_con, con.apellido apellido_con, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
				FROM cotizacion_account cot
				JOIN catcliente cli ON cot.id_cliente = cli.id
				JOIN contacto con ON cot.id_contacto = con.id
				JOIN catusuario usu ON cot.id_usuario = usu.id 
				JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
				WHERE (cot.fecha_fin_servicio>='".$fecha."')
				ORDER BY cot.fecha_alta DESC";
			$cotizaciones = $this->db->query($query_fecha)->result();

			echo json_encode($cotizaciones);

		}
	}

	public function buscaPorFacturaVencida(){

		if(isset($_POST)){

			$fecha = $_POST["fecha"];

			$query_fecha = "SELECT cli.nombre nombre_cli, usu.nombre nombre_acc,con.nombre nombre_con, con.apellido apellido_con, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
				FROM cotizacion_account cot
				JOIN catcliente cli ON cot.id_cliente = cli.id
				JOIN contacto con ON cot.id_contacto = con.id
				JOIN catusuario usu ON cot.id_usuario = usu.id 
				JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
				WHERE (cot.fecha_fin_servicio<'".$fecha."')
				ORDER BY cot.fecha_alta DESC";
			$cotizaciones = $this->db->query($query_fecha)->result();

			echo json_encode($cotizaciones);

		}
	}


	public function traeTodo(){
		$cotizaciones = $this->db->query("SELECT cli.nombre nombre_cli, usu.nombre nombre_acc,con.nombre nombre_con, con.apellido apellido_con, con.correo correo, sta.clave clave_status, cot.*, DATE_FORMAT(cot.fecha_alta, '%d-%m-%Y')  fecha_alta, DATE_FORMAT(cot.fecha_inicio_servicio, '%d-%m-%Y') fecha_inicio, DATE_FORMAT(cot.fecha_fin_servicio, '%d-%m-%Y') fecha_fin
		FROM cotizacion_account cot
		JOIN catcliente cli ON cot.id_cliente = cli.id
		JOIN contacto con ON cot.id_contacto = con.id
		JOIN catusuario usu ON cot.id_usuario = usu.id
		JOIN cat_status_cotizacion sta ON cot.status_cotizacion_id=sta.id
		ORDER BY cot.fecha_alta DESC")->result();

		echo json_encode($cotizaciones);
	}

	public function aprobarCotizacion(){

		if(isset($_POST)){

			$idCotizacion = $_POST["idCotizacion"];

			$query_aprobacion = "UPDATE cotizacion_account SET status_cotizacion_id=2 WHERE id=".$idCotizacion;

			$this->db->query($query_aprobacion);

			echo "Cotización aprobada con éxito";

		}
	}

	public function cancelarCotizacion(){

		if(isset($_POST)){

			$idCotizacion = $_POST["idCotizacion"];

			$query_cancelacion = "UPDATE cotizacion_account SET status_cotizacion_id=3 WHERE id=".$idCotizacion;

			$this->db->query($query_cancelacion);

			echo "Cotización cancelada con éxito";

		}
	}

	public function duplicarCotizacion(){
		if(isset($_POST)){

			$idCotizacion = $_POST["idCotizacion"];

			$query_trae_cotizacion = "SELECT * FROM cotizacion_account WHERE id=".$idCotizacion;
			$cot = $this->db->query($query_trae_cotizacion)->row();
			if($cot->nombre_archivo == ""){
				$cot->nombre_archivo = "NULL";
			}
			$max = $this->db->query("SELECT (max(folio)+1) folio, (max(id)+1) idCotizacion FROM cotizacion_account")->row();

			//Se inserta cotización
			$this->db->query("INSERT INTO cotizacion_account (id, folio, id_contacto, id_cliente, id_usuario, titulo, importe_total, fecha_inicio_servicio, fecha_fin_servicio, status_cotizacion_id, tipo_cotizacion_id, nombre_archivo, introduccion, objetivo, nota) 
			values (".$max->idCotizacion.",'".$max->folio."',".$cot->id_contacto.",".$cot->id_cliente.",".$cot->id_usuario.",'".$cot->titulo."',".$cot->importe_total.",'".$cot->fecha_inicio_servicio."','".$cot->fecha_fin_servicio."',".$cot->status_cotizacion_id.",".$cot->tipo_cotizacion_id.",".$cot->nombre_archivo.",'".$cot->introduccion."','".$cot->objetivo."','".$cot->nota."')");

			$alc_cot = $this->db->query("SELECT ac.* FROM alcance_cotizacion ac 
										 JOIN cotizacion_account ca ON ca.id=ac.id_cotizacion_account 
										 WHERE ca.id=".$idCotizacion)->result();

			$max_alc_cot = $this->db->query("SELECT	(max(id)+1) idAlcance FROM alcance_cotizacion")->row();

			//Se insertan alcances por cotizacion
			for ($i=0,$n=count($alc_cot); $i < $n; $i++) { 
				$this->db->query("INSERT INTO alcance_cotizacion (id, orden,id_clasificacion_servicio, id_cotizacion_account, titulo, entregables, requerimientos, fecha_inicio_servicio, fecha_fin_servicio, monto_total)
					VALUES(".($max_alc_cot->idAlcance+$i).",".$alc_cot[$i]->orden.",".$alc_cot[$i]->id_clasificacion_servicio.",".$max->idCotizacion.",'".$alc_cot[$i]->titulo."','".$alc_cot[$i]->entregables."','".$alc_cot[$i]->requerimientos."','".$alc_cot[$i]->fecha_inicio_servicio."','".$alc_cot[$i]->fecha_fin_servicio."',".$alc_cot[$i]->monto_total.")");
			}

			//Se insertan pagos recurrentes
			if($cot->tipo_cotizacion_id == 1){
				$pag_rec = $this->db->query("SELECT pr.* FROM subtipo_pago_recurrente pr
											 JOIN alcance_cotizacion ac ON pr.id_alcance = ac.id
											 JOIN cotizacion_account ca ON ac.id_cotizacion_account = ca.id
											 WHERE ca.id = ".$idCotizacion)->result();

				for ($j=0, $m=count($pag_rec); $j < $m; $j++) { 
					$this->db->query("INSERT INTO subtipo_pago_recurrente (id_alcance,id_periodicidad,numero_parcialidades,monto_parcialidad)
						VALUES(".($max_alc_cot->idAlcance+$j).",".$pag_rec[$j]->id_periodicidad.",".$pag_rec[$j]->numero_parcialidades.",".$pag_rec[$j]->monto_parcialidad.")");
				}
			}elseif ($cot->tipo_cotizacion_id==2) { //Se insertan pagos fijos
		
				for ($w=0,$r=count($alc_cot); $w < $r; $w++) { 
					$max_pf = $this->db->query("SELECT (max(id)+1) idPagosFijos FROM subtipo_pago_fijo")->row();

					$pag_fij = $this->db->query("SELECT * FROM subtipo_pago_fijo WHERE id_alcance=".$alc_cot[$w]->id)->result();

					for ($q=0, $a = count($pag_fij); $q < $a; $q++) { 
						$this->db->query("INSERT INTO subtipo_pago_fijo (id,id_alcance,concepto,fecha,porcentaje_monto,monto_parcialidad)
							VALUES(".($max_pf->idPagosFijos+$q).",".($max_alc_cot->idAlcance+$w).",'".$pag_fij[$q]->concepto."','".$pag_fij[$q]->fecha."',".$pag_fij[$q]->porcentaje_monto.",".$pag_fij[$q]->monto_parcialidad.")");
					}
				}
			}

			//Se insertan descripciones por alcance
			for ($p=0, $v = count($alc_cot); $p < $v; $p++) { 
				$max_desc = $this->db->query("SELECT (max(id)+1) idDesc FROM descripcion_alcance")->row();

				$desc = $this->db->query("SELECT * FROM descripcion_alcance WHERE id_alcance=".$alc_cot[$p]->id)->result();

				for ($d=0, $h=count($desc); $d < $h; $d++) { 
					$this->db->query("INSERT INTO descripcion_alcance (id,id_alcance,titulo,descripcion)
						VALUES(".($max_desc->idDesc+$d).",".($max_alc_cot->idAlcance+$p).",'".$desc[$d]->titulo."','".$desc[$d]->descripcion."')");
				}
			}

			echo "Cotización duplicada con éxito";
		}
	}

	public function generaPDF(){
		if (isset($_POST)) {
			$idCotizacion = $_POST["idCotizacion"];

			$cotizacion = $this->db->query("SELECT ca.*, DATE_FORMAT(ca.fecha_alta,'%d/%m/%Y') fechaCot, u.id idAccount, u.nombre nombreAccount, u.correo correoAccount, u.telefono telAccount, p.nombre puesto, cli.nombre nombreCliente, con.nombre nombreContacto, con.apellido apellidoContacto    
				FROM cotizacion_account ca  JOIN contacto con ON ca.id_contacto = con.id 
				JOIN catcliente cli ON ca.id_cliente = cli.id 
				JOIN catusuario u ON ca.id_usuario = u.id
				JOIN catpuesto p ON u.idPuesto = p.id
				WHERE ca.id=".$idCotizacion)->row();
			
			$alcances = $this->db->query("SELECT ac.*, cs.clave clasServicio FROM alcance_cotizacion ac
				JOIN catclasificacion_servicio cs ON ac.id_clasificacion_servicio =  cs.id
				WHERE ac.id_cotizacion_account=".$idCotizacion." ORDER BY orden")->result();

			
			for ($i=0, $n=count($alcances); $i < $n; $i++) {
				$id_alcance = $alcances[$i]->id;
				$descripciones = $this->db->query("SELECT * FROM descripcion_alcance 
													WHERE id_alcance=".$id_alcance)->result();

				$alcances[$i]->descripciones = $descripciones;

				$clasificaciones =$this->db->query("SELECT * FROM catclasificacion_servicio 
					WHERE id_servicio=	(SELECT cs.id_servicio FROM catclasificacion_servicio cs
										JOIN alcance_cotizacion ac ON cs.id = ac.id_clasificacion_servicio
										WHERE ac.id=".$id_alcance.")")->result();

				$alcances[$i]->clasificaciones = $clasificaciones;
				for($j=0,$k=count($clasificaciones);$j<$k;$j++){
					if($alcances[$i]->id_clasificacion_servicio == $clasificaciones[$j]->id){
						$alcances[$i]->tipo_concepto_sel = $clasificaciones[$j]->id_servicio;
					}
				}
			}
			
			if (($cotizacion->tipo_cotizacion_id)==1) { //Pagos recurrentes
			
				for ($i=0,$n=count($alcances); $i < $n ; $i++) {
					$id_alcance = $alcances[$i]->id; 
					$pago_recurrente= $this->db->query("SELECT * FROM subtipo_pago_recurrente 
														WHERE id_alcance =".$id_alcance)->row();
					$alcances[$i]->pago_recurrente = $pago_recurrente;
				}

			}elseif(($cotizacion->tipo_cotizacion_id)==2){ //Pagos fijos

				for ($i=0, $n=count($alcances); $i < $n; $i++) { 
					$id_alcance = $alcances[$i]->id;
					$parcialidades = $this->db->query("SELECT * FROM subtipo_pago_fijo WHERE id_alcance=".$id_alcance)->result();
					$alcances[$i]->parcialidades = $parcialidades;
				}
			}


			/////////////////////////Aquí empieza la generación del PDF
			$archivo_estilos = fopen($_SERVER['DOCUMENT_ROOT'].'includes/cotizacion/css/main.css', "r") or die("Unable to open file!");
			$archivo_css =  fread($archivo_estilos,filesize($_SERVER['DOCUMENT_ROOT'].'includes/cotizacion/css/main.css'));
			fclose($archivo_estilos);

			
			$body1 = '
			<!doctype html>
			<html class="no-js" lang="">

			<head>
			    <meta charset="utf-8">
			    <meta http-equiv="x-ua-compatible" content="ie=edge">
			    <title>Codice</title>
			    <meta name="description" content="">
			    <meta name="viewport" content="width=device-width, initial-scale=1">

			    <!-- Place favicon.ico in the root directory -->
			    <style>'.$archivo_css.'</style>
			</head>
			<body>
			    <!--[if lt IE 8]>
			            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
			        <![endif]-->

			    <div class="container">
			        <div class="logoCodice" align="center">
			            <img src="'.base_url().'includes/cotizacion/img/logo-codice.png" width="139.33" height="33.33" alt="">
			        </div>
			        <br><br><br><br><br><br><br><br><br>
			        <div class="bloqueLeft" align="center">
			           
			            <img src="'.base_url().'includes/cotizacion/img/direccion.PNG" alt="">
			        </div>
			    </div>
			</body>
			</html>';

			$body2='
			<!doctype html>
			<html class="no-js" lang="">

			<head>
			    <meta charset="utf-8">
			    <meta http-equiv="x-ua-compatible" content="ie=edge">
			    <title>Codice</title>
			    <meta name="description" content="">
			    <meta name="viewport" content="width=device-width, initial-scale=1">

			    <!-- Place favicon.ico in the root directory -->
			    <style>'.$archivo_css.'</style>
			</head>
			<body>
				<div class="container">
			       
			        <div class="clearfix"></div>
			        <div class="contenedor">
			        	<div align="right">
			            	<span class="fecha">Fecha: '.$cotizacion->fechaCot.'</span>
			            </div>
			            <h2>'.$cotizacion->nombreContacto.' '.$cotizacion->apellidoContacto.'</h2>
			            <b><span style="color: #000;">'.$cotizacion->nombreCliente.'</span></b>
			            <p>'.$cotizacion->introduccion.'</p>

			            <div class="atencion">
			                <p>Atentamente,</p>

			                <p class="txt">'.$cotizacion->nombreAccount.'</p>
			                <span>'.$cotizacion->puesto.'</span><br>
			                <span class="txt"><br>Cel. 044 '.$cotizacion->telAccount.'</span>
			            </div>

			        </div>
			        <div class="clearfix"></div>
			        
			    </div>
			</body>
			</html>';

			$body3= '
			<!doctype html>
			<html class="no-js" lang="">

			<head>
			    <meta charset="utf-8">
			    <meta http-equiv="x-ua-compatible" content="ie=edge">
			    <title>Codice</title>
			    <meta name="description" content="">
			    <meta name="viewport" content="width=device-width, initial-scale=1">

			    <!-- Place favicon.ico in the root directory -->
			    <style>'.$archivo_css.'</style>
			</head>
			<body>

			    <div class="container">
			        
			        <div class="contenedor"><br><br>
			            <h2>1.OBJETIVOS</h2>
			            <p>'.$cotizacion->objetivo.'</p>
			            <h2>2.SERVICIOS</h2>
			            <ul>';

			foreach ($alcances as $a) {

			            $body3.= '<li>'.$a->clasServicio.'.</li>';
			}

			$body3.=    '</ul><br><br>
						<h2>3.DESCRIPCIÓN DE SERVICIOS</h2>';

			foreach ($alcances as $a) {
				
				$body3.= '<h2>'.$a->titulo.'.</h2>';	

				for($i=0, $n=count($a->descripciones); $i<$n; $i++){
					$d = $a->descripciones[$i];
					$body3.='<span class="txt" style="color: #000;">'.$d->titulo.'</span>';
					$body3.='<p>'.$d->descripcion.'</p>';

				}
			}

			$body3.= '<br><h2>4.PROPUESTA ECONÓMICA</h2>
			        <table align="center" border="1" cellpadding="1" >
			            <tr style="background-color: #7f7f7f; color:#fff;">
			                <th align="center">Descripción</th>
			                <th align="center">Monto</th>
			            </tr>';

			foreach ($alcances as $a) {
				
				$body3.='<tr style="background-color: #efefef;">
			                <td align="center">'.$a->titulo.'</td>
			                <td align="center">'.number_format($a->monto_total,2).'</td>
			            </tr>';
			}

			$body3.=	'<tr style="background-color: #efefef;">
				            <td align="center" style="color: #000"><b>TOTAL</b></td>
				            <td align="center" style="color: #000"><b>'.number_format($cotizacion->importe_total,2).'</b></td>
				        </tr>
			        </table>

			    </div>
			    
			    <div class="clearfix"></div>
			</div>

			</body>

			</html>';

			$pdf = new MYPDF();

			//$pdf->SetPrintHeader(false);
			//pdf->SetPrintFooter(false);

			//$pdf->SetImageScale(2);
			$pdf->SetMargins(20,100,20,true);
			$pdf->AddPage();
			$pdf->WriteHTML($body1,true,false,true,false,'');
			$pdf->SetMargins(20,10,20,true);
			$pdf->AddPage();
			//$pdf->AddFont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/cotizacion/fonts/gotham-black.ttf','',$_SERVER['DOCUMENT_ROOT'].'JOBS/includes/tcpdf/fonts/gotham-bold.php');
			//$pdf->SetFont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/cotizacion/fonts/gotham-black.ttf','','');
			$gotham_medium = TCPDF_FONTS::addTTFfont($_SERVER['DOCUMENT_ROOT'].'includes/tcpdf/fonts/gotham-medium.ttf','TrueTypeUnicode','',32);
			$pdf->SetFont($gotham_medium,'',9,'',false);
			$pdf->WriteHTML($body2,true,false,true,false,'');
			$pdf->SetMargins(20,20,20,true);
			$pdf->AddPage();
			//$pdf->AddFont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/cotizacion/fonts/gotham-medium.ttf','',$_SERVER['DOCUMENT_ROOT'].'JOBS/includes/tcpdf/fonts/gotham-medium.php');
			//$pdf->SetFont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/cotizacion/fonts/gotham-medium.ttf','','');
			$pdf->WriteHTML($body3,true,false,true,false,'');
			//$pdf->Image($img64);

			$folder = $_SERVER['DOCUMENT_ROOT']."img/";

			$nombre = "pdf_";

			while (file_exists($folder.$nombre.".pdf")) {
				$nombre .= rand(0,9);			
			}

			$nombre .= ".pdf";

			$pdf->Output($folder.$nombre,'F');

			echo $nombre;
		}
	}

}

//Se declara el header y el footer del PDF a generar
	class MYPDF extends TCPDF{
				
		public function Header(){
			$header ='<div class="logoTop" align="right">
		            <img src="'.base_url().'includes/cotizacion/img/logo-top-codice.png" alt="">
		          </div>';
		    $this->SetY(5);
			$this->WriteHTML($header,true,false,true,false,'');
		}

		public function Footer(){
			$footer ='
				    <div class="bottom" align="right">
				        <span style="color: #7e7e7e;">Aplicaciones Códice parar Internet, SC</span>
				    </div>';
			$gotham_medium = TCPDF_FONTS::addTTFfont($_SERVER['DOCUMENT_ROOT'].'includes/tcpdf/fonts/gotham-medium.ttf','TrueTypeUnicode','',32);
			$this->SetFont($gotham_medium,'',9,'',false);

			$this->SetY(-15);
			//$this->AddFont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/cotizacion/fonts/gotham-black.ttf','',$_SERVER['DOCUMENT_ROOT'].'JOBS/includes/tcpdf/fonts/gotham-black.php');
			//$this->SetFont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/cotizacion/fonts/gotham-black.ttf','',9);
			$this->WriteHTML($footer,true,false,true,false,'');
		}
	}
?>