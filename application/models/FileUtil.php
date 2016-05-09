<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FileUtil extends CI_Model {
	public function download($path){
		$data = file_get_contents(base_url().'img/'.$path);

		$partes = explode(".", $path);
		$name = 'archivoDeEvidencia.'.$partes[count($partes)-1];

		force_download($name, $data);		
	}
}
?>