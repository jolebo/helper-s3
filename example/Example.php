<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Aws\S3\S3Client;

class Example extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('S3');
	}


	public function createBucketMinio(){
		$s3 = (new S3());	
		$bucketName = "test_bucket";

		$s3->createBucket($bucketName);
	}

	public function uploadToMinio(){
		$image = convertImageToBase64($_FILES["gambar"]);
		$path = 'foto/';
		$filename = gen_uuid().$image["ext"];

		$s3 = (new S3());

		$opr = $s3->saveFile([
			"body"=> $image["data"],
			"file_name"=> $path.$filename,
			"contentType"=> $image["mimeType"]
		]);

		echo "<pre>";
		print_r($opr);exit;
	}

	public function deleteFromMinio($filename = ""){
		$s3 = (new S3());

		$opr = $s3->deleteFile([
			"file_name" => $filename
		]);

		echo "<pre>";
		print_r ($opr);
		echo "</pre>";
	}

	public function getImageFromMinio(){
		$s3 = (new S3());
		$filepath = 'foto/';
		$filename = "gambar.png";
		
		$opr = $s3->getFile([
			"file_name" => $filepath.$filename,
		]);

		if($opr["isExists"]){
			/*jika gambar langsung tampilkan selain itu download */
			$contentDisposition = $opr["ContentType"] == "image/jpeg" || $opr["ContentType"] == "image/png" ? "inline" : "attachment";

			header('Content-Type: ' . $opr["ContentType"]);
			header('Content-Disposition: '.$contentDisposition.";filename=\"{$filename}\"");
			echo $opr["Body"];
		}

		/* atau bisa diconvert ke base64  */
		if($opr["isExists"]){
			$data = "data:{$opr["ContentType"]};base64," . base64_encode($opr["Body"]);
		}
	}
}