<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/Mexico_City');

class Reporte_ingresos_ctrl extends CI_Controller{

	public function index(){

		$data['menu'] = $this->load->view('Menu_principal',null,true);
		$this->load->view("Reporte_ingresos_vw", $data);
	}

	public function generaReporte(){

		if (isset($_POST)) {
			if($_POST["periodo"]==1){

				$mes = $_POST["mes"];

				$anio = date('Y');

				$ultimo_dia = date("d",(mktime(0,0,0,$mes+1,1,$anio)-1));

				$fechaInicio = $anio."-".$mes."-01";
				$fechaFin = $anio."-".$mes."-".$ultimo_dia;

			}elseif($_POST["periodo"]==2){

				$anio = date('Y');
				$trimestre = $_POST["trimestre"];

				if($trimestre==1){
					$fechaInicio = $anio."-01-01";
					$fechaFin = $anio."-03-31";
				}elseif ($trimestre==2) {
					$fechaInicio = $anio."-04-01";
					$fechaFin = $anio."-06-30";
				}elseif ($trimestre==3) {
					$fechaInicio = $anio."-07-01";
					$fechaFin = $anio."-09-30";
				}elseif ($trimestre==4) {
					$fechaInicio = $anio."-10-01";
					$fechaFin = $anio."-12-31";
				}

			}elseif($_POST["periodo"]==3){

				$anio = date('Y');
				$semestre = $_POST["semestre"];

				if($semestre == 1){
					$fechaInicio = $anio."-01-01";
					$fechaFin = $anio."-06-30";
				}elseif ($semestre==2) {
					$fechaInicio = $anio."-07-01";
					$fechaFin = $anio."-12-31";
				}

			}elseif($_POST["periodo"]==4){

				$anio = $_POST["anio"];

				$fechaInicio = $anio."-01-01";
				$fechaFin = $anio."-12-31";

			}elseif($_POST["periodo"]==5){

				$fechaInicio = $_POST["fechaInicio"];
				$fechaInicioAux = explode("/", $fechaInicio);
				$fechaInicio = $fechaInicioAux[2]."-".$fechaInicioAux[1]."-".$fechaInicioAux[0];
				$fechaFin = $_POST["fechaFin"];
				$fechaFinAux = explode("/", $fechaFin);
				$fechaFin = $fechaFinAux[2]."-".$fechaFinAux[1]."-".$fechaFinAux[0];
			}


			//Trae los conceptos para el periodo seleccionado

			$query_trae_conceptos = "select
					ff.id id,
					ff.importe montoFechaFactura,
					ff.idEstadoFactura idEstadoFactura,
					ff.importe subtotal,
					ff.fecha_final fechaPago,
					round((ff.importe * ((con_cot.iva/100)+1)),2) total,
					round((ff.importe - (ff.importe * ((con_cot.iva/100)+1))),2) cantidadIVA,
					con_cot.iva tasa,
					con_cot.total totalConceptoCotizacion,
					IFNULL(clas_serv.clave, 'SIN SERVICIO') servicio,
					tipo_con.id idClasificacion,
					tipo_con.descripcion clasificacion,
					cli.id idCliente,
					cli.nombre cliente,
					edo_fac.descripcion estadoFactura
				from
					fecha_factura ff
					inner join catestadofactura edo_fac on edo_fac.id = ff.idEstadoFactura
					left join concepto_cotizacion con_cot on con_cot.id = ff.idConceptoCotizacion
					left join catclasificacion_servicio clas_serv on con_cot.idClasificacion_servicio = clas_serv.id
					left join cotizacion c on c.id = con_cot.idCotizacion
					left join catcliente cli on cli.id = c.idCliente
					left join cattipoconcepto tipo_con on tipo_con.id = con_cot.idTipoConcepto
				where
					con_cot.estadoActivo = 1
                    AND ff.`idEstadoFactura` = 21
                    AND ff.`fecha_final` BETWEEN '".$fechaInicio."' AND '".$fechaFin."'
                    ORDER BY cli.nombre ASC";

			$conceptos = $this->db->query($query_trae_conceptos)->result();

			//Comienza con el cálculo de los totales de ingresos por cliente
			$numCliente = 0; //Lleva la cuenta de número de clientes
			for($i = 0, $n =count($conceptos); $i<$n ; $i++) {
				$clientes[$numCliente] = new \stdClass; //Crea un nuevo objeto
				$clientes[$numCliente]->nombre = $conceptos[$i]->cliente;
				$clientes[$numCliente]->inversion = 0;
				$clientes[$numCliente]->otrosIngresos = 0;
				$clientes[$numCliente]->servicios = 0;
				$clientes[$numCliente]->total = 0;


				do{
					//Dependiendo de la clasificación hará la suma
					switch ($conceptos[$i]->idClasificacion) {
						case 15: //Desarollo
							$clientes[$numCliente]->servicios += $conceptos[$i]->total;
							break;
						case 16: //Marketing
							$clientes[$numCliente]->servicios += $conceptos[$i]->total;
							break;
						case 17: //Inversión
							$clientes[$numCliente]->inversion += $conceptos[$i]->total;
							break;
						case 18: //ADM
							$clientes[$numCliente]->otrosIngresos += $conceptos[$i]->total;
							break;
						case 21: //Gasto directo
							$clientes[$numCliente]->otrosIngresos += $conceptos[$i]->total;
							break;
					}
					$i++;
					if($i==$n){
						break;
					}

				}while(($conceptos[$i]->idCliente) == ($conceptos[$i-1]->idCliente));
				$i--;

				$clientes[$numCliente]->total = $clientes[$numCliente]->servicios+$clientes[$numCliente]->inversion+$clientes[$numCliente]->otrosIngresos;

				$numCliente++;
			}

			//Se calcula los totales generales
			$totales = new \stdClass;
			$totales->inversion = 0;
			$totales->servicios = 0;
			$totales->otrosIngresos = 0;
			$totales->total = 0;
			foreach ($clientes as $c) {
				
				if(isset($c->inversion)) 
					$totales->inversion += $c->inversion;
				if(isset($c->servicios))
					$totales->servicios += $c->servicios;
				if(isset($c->otrosIngresos))
					$totales->otrosIngresos += $c->otrosIngresos;
				if(isset($c->total))
					$totales->total += $c->total;

			}

			//Aquí se genera el Excel del reporte de ingresos

			$this->load->model("XLSSheetDriver");
			$xls = new $this->XLSSheetDriver();
			$xls->setTitle("Reporte de Ingresos - CODICE");

			$xls->setCellValue("Cliente"); $xls->nextCol();
			$xls->setCellValue("INVERSIÓN"); $xls->nextCol();
			$xls->setCellValue("OTROS INGRESOS"); $xls->nextCol();
			$xls->setCellValue("SERVICIOS"); $xls->nextCol();
			$xls->setCellValue("Total General");
			$xls->setCellBackground("ED7D31","A1:".$xls->getPosition());
			$xls->nextLine();

			foreach ($clientes as $c) {
				$xls->setCellValue($c->nombre); $xls->nextCol();
				if($c->inversion != 0)
					$xls->setCellValue(number_format($c->inversion,2)); $xls->nextCol();
				if($c->otrosIngresos != 0)
					$xls->setCellValue(number_format($c->otrosIngresos,2)); $xls->nextCol();
				if($c->servicios != 0)
					$xls->setCellValue(number_format($c->servicios,2)); $xls->nextCol();
				$xls->setCellValue(number_format($c->total,2)); $xls->nextCol();
				$xls->nextLine();
			}

			$xls->setCellValue("Total general"); $xls->nextCol();
			$xls->setCellValue(number_format($totales->inversion,2)); $xls->nextCol();
			$xls->setCellValue(number_format($totales->otrosIngresos,2)); $xls->nextCol();
			$xls->setCellValue(number_format($totales->servicios,2)); $xls->nextCol();
			$xls->setCellValue(number_format($totales->total,2)); 
			$xls->setCellBorders("ED7D31","A".$xls->getRow().":".$xls->getPosition());
			$xls->nextLine();

			
			$xls->setCellValue("Clasificación","H1"); $xls->nextCol();
			$xls->setCellValue("Total","I1"); 
			$xls->setCellValue("INVERSIÓN","H2"); $xls->nextCol();
			$xls->setCellValue(number_format($totales->inversion,2),"I2"); 
			$xls->setCellValue("OTROS INGRESOS","H3"); $xls->nextCol();
			$xls->setCellValue(number_format($totales->otrosIngresos,2),"I3"); 
			$xls->setCellValue("SERVICIOS","H4"); $xls->nextCol();
			$xls->setCellValue(number_format($totales->servicios,2),"I4"); 
			$xls->setCellValue("Total General","H5"); $xls->nextCol();
			$xls->setCellValue(number_format($totales->total,2),"I5");
			$xls->setCellBorders("ED7D31","H5:I5"); 
			$xls->setCellBackground("ED7D31","H1:I1");
			$xls->setCellValue("**Montos incluyen IVA","H7");
			$xls->setCellValue("Todo expresado en moneda nacional","H8");

			$xls->autoSizeColumns();
			$xls->out("Reporte_Ingresos-CODICE.xls");

		}

	}
}

?>