<?php
use Aws\S3\S3Client;

    	
class S3
{
	public $s3;
	function __construct()
	{
		/*create connection*/
		$this->s3 = new S3Client([
			"version" => "latest",
			"region" => $_ENV['S3_REGION'],
			"endpoint" => $_ENV['S3_HOST'],
			"use_path_style_endpoint" => true,
			"credentials" => [
				"key" => $_ENV["S3_ACCESS_KEY"],
				"secret" => $_ENV["S3_SECRET_KEY"],
			]
		]);
	}

	/**
	 *  to create bucket storage
	 * 
	 *  @param string $config["bucketName"] nama bucket
	 **/

	function createBucket($bucketName = ""){

	    $result = $this->s3->createBucket([
	        'Bucket' => $bucketName,
	    ]);
		return $result;
	}

	/**
	 *  Put object to bucket storage
	 * 
	 *  @param string $config["acl"] untuk mengatur hak akses file cth: private|public-read|public-read-write|authenticated-read|aws-exec-read|bucket-owner-read|bucket-owner-full-control // permission
	 *  @param string $config["bucket"] nama bucket,jika tidak diisi maka akan diambil dari default default $_ENV["S3_BUCKET"]
	 *  @param string $config["file_name"]  nama file di yang akan disimpan
	 *  @param string $config["body"] bisa diisi url path / base64
	 * 	@param string $config["contentType"] content type dari file yang di upload
	 * @return object
	 **/

	function putObject($config = []){
		$opr = $this->s3->putObject([
			"ACL" => isset($config["acl"]) ? $config["acl"] : "public-read",
			// "Body" => $config["body"],
			"Bucket" => isset($config["bucket"]) ? $config["bucket"] : $_ENV["S3_BUCKET"],
			"Key" => $config["file_name"],
			"SourceFile" => $config["body"],
			"ContentType" => $config["contentType"],
		]);

		return $opr;
	}

	/**
	 * To delete object from bucket storage
	 * 
	 *  @param string $config["bucket"] nama bucket,jika tidak diisi maka akan diambil dari default default $_ENV["S3_BUCKET"]
	 * @param string $config["file_name"] nama file yang akan dihapus
	 **/

	function deleteObject($config = []){
		$checkObject = $this->s3->doesObjectExist(isset($config["bucket"]) ? $config["bucket"] : $_ENV["S3_BUCKET"],$config["file_name"]);

		if($checkObject){
			$opr = $this->s3->deleteObject([
				"Bucket" => isset($config["bucket"]) ? $config["bucket"] : $_ENV["S3_BUCKET"],
				"Key" => $config["file_name"]
			]);
		}
	}

	/**
	 * To get object from bucket storage
	 * 
	 * @param string $config["bucket"] nama bucket,jika tidak diisi maka akan diambil dari default default $_ENV["S3_BUCKET"]
	 * @param string $config["file_name"] nama file yang akan dihapus
	 * @return object
	 **/

	function getObject($config = []){
		$checkObject = $this->s3->doesObjectExist(isset($config["bucket"]) ? $config["bucket"] : $_ENV["S3_BUCKET"],$config["file_name"]);

		$opr = [
			"isExists" => false,
			"Body" => [],
			"ContentType" => "",
		];

		if($checkObject){
			$opr = $this->s3->getObject([
				"Bucket" => isset($config["bucket"]) ? $config["bucket"] : $_ENV["S3_BUCKET"],
				"Key" => $config["file_name"],
				// "ResponseContentDisposition" => "inline",
			]);
			$opr["isExists"] = true;
		}
		return $opr;
	}

}