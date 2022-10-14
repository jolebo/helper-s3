<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	if ( ! function_exists('convertImageToBase64')){
		function convertImageToBase64($files = []){
			$data = file_get_contents($files["tmp_name"]);
			$data = base64_encode($data);

			return [
				"data" => "data:{$files["type"]};base64,".$data,
				"mimeType"=>$files["type"],
				"ext" => ".".explode(".", $files["name"])[1],
			];
		}
	}
}