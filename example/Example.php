<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Aws\S3\S3Client;

class Playground extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('S3');
	}


	public function uploadToMinio(){
		$image = convertImageToBase64($_FILES["gambar"]);

		$s3 = (new S3());

		$opr = $s3->putObject([
			"body"=> $image["data"],
			"file_name"=> 'foto/'.gen_uuid().$image["ext"],
			"contentType"=> $image["mimeType"]
		]);

		echo "<pre>";
		print_r($opr);exit;
	}

	public function deleteFromMinio($filename = ""){
		$s3 = (new S3());

		$opr = $s3->deleteObject([
			"file_name" => $filename
		]);

		echo "<pre>";
		print_r ($opr);
		echo "</pre>";
	}

	public function getImageFromMinio($id = ""){
		$s3 = (new S3());
		$data = $this->db->get_where('minio',["minio_id" => $id])->row_array();
		if($data){
			$opr = $s3->getObject([
				"file_name" => $data["minio_filepath"].$data["minio_filename"],
			]);

			$contentDIsposition = $opr["ContentType"] == "image/jpeg" || $opr["ContentType"] == "image/png" ? "inline" : "attachment";

			header('Content-Type: ' . $opr["ContentType"]);
			header('Content-Disposition: '.$contentDIsposition.";filename=\"{$data["minio_filename"]}\"");
			echo $opr["Body"];
		}
	}
}