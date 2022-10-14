<?php
use Aws\S3\S3Client;

    	
class S3
{
	public $s3;
	// private $s3_bucket = $this->config->item("s3_bucket");
	// private $s3_region = $this->config->item("s3_region");
	// private $s3_host = $this->config->item("s3_host");
	// private $s3_access_key = $this->config->item("s3_access_key");
	// private $s3_secret_key = $this->config->item("s3_secret_key");
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

	function createBucket($bucketName = ""){

	    $result = $this->s3->createBucket([
	        'Bucket' => $bucketName,
	    ]);
		return $result;
	}

	/**
	 *  Put object to bucket storage
	 * 
	 *  ACL : $config["acl"] private|public-read|public-read-write|authenticated-read|aws-exec-read|bucket-owner-read|bucket-owner-full-control // permission
	 * 	Body : $config["body"] // file yang akan diupload
	 *  Bucket : $config["bucket"] default $_ENV["S3_BUCKET"] // bucket name
	 *  Key : $config["file_name"] // nama file di bucket
	 *  SourceFile : $config["body"] // url path / base64
	 * 	ContentType : $config["contentType"]
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
	 * Bucket : $config["bucket"] 
	 * Key : $config["file_name"]
	 **/

	function deleteObject($config = []){
		$opr = $this->s3->deleteObject([
			"Bucket" => isset($config["bucket"]) ? $config["bucket"] : $_ENV["S3_BUCKET"],
			"Key" => $config["file_name"]
		]);
	}

	function getObject($config = []){
		$opr = $this->s3->getObject([
			"Bucket" => isset($config["bucket"]) ? $config["bucket"] : $_ENV["S3_BUCKET"],
			"Key" => $config["file_name"],
			// "ResponseContentDisposition" => "inline",
		]);
		return $opr;
	}

}