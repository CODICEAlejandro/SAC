<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'includes/tcpdf/tcpdf.php';

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
    <style>'.(file_get_contents(base_url()."includes/cotizacion/css/main.css")).'</style>
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
           
            <img src="'.base_url().'includes/cotizacion/img/direccion.png" alt="">
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
    <style>'.(file_get_contents(base_url()."includes/cotizacion/css/main.css")).'</style>
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
    <style>'.(file_get_contents(base_url()."includes/cotizacion/css/main.css")).'</style>
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
		$gotham_medium = TCPDF_FONTS::addTTFfont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/tcpdf/fonts/gotham-medium.ttf','TrueTypeUnicode','',32);
		$this->SetFont($gotham_medium,'',9,'',false);

		$this->SetY(-15);
		//$this->AddFont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/cotizacion/fonts/gotham-black.ttf','',$_SERVER['DOCUMENT_ROOT'].'JOBS/includes/tcpdf/fonts/gotham-black.php');
		//$this->SetFont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/cotizacion/fonts/gotham-black.ttf','',9);
		$this->WriteHTML($footer,true,false,true,false,'');
	}
}

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
$gotham_medium = TCPDF_FONTS::addTTFfont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/tcpdf/fonts/gotham-medium.ttf','TrueTypeUnicode','',32);
$pdf->SetFont($gotham_medium,'',9,'',false);
$pdf->WriteHTML($body2,true,false,true,false,'');
$pdf->SetMargins(20,20,20,true);
$pdf->AddPage();
//$pdf->AddFont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/cotizacion/fonts/gotham-medium.ttf','',$_SERVER['DOCUMENT_ROOT'].'JOBS/includes/tcpdf/fonts/gotham-medium.php');
//$pdf->SetFont($_SERVER['DOCUMENT_ROOT'].'JOBS/includes/cotizacion/fonts/gotham-medium.ttf','','');
$pdf->WriteHTML($body3,true,false,true,false,'');
//$pdf->Image($img64);

$folder = $_SERVER['DOCUMENT_ROOT']."JOBS/img/";

$nombre = "pdf_prueba";

while (file_exists($folder.$nombre.".pdf")) {
	$nombre .= rand(0,9);			
}

$nombre .= ".pdf";

$pdf->Output($folder.$nombre,'F');

echo $body1."<br>".$body2."<br>".$body3."<br>".$nombre;

