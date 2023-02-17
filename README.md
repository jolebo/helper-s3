[![Build Status](https://camo.githubusercontent.com/f5054ffcd4245c10d3ec85ef059e07aacf787b560f83ad4aec2236364437d097/68747470733a2f2f696d672e736869656c64732e696f2f62616467652f636f6e747269627574696f6e732d77656c636f6d652d627269676874677265656e2e7376673f7374796c653d666c6174)]()
## Helper S3

***Helper S3** adalah sebuah kelas yang mengextend [aws s3](https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html), dengan tujuan untuk menyimpan file didalam object storage.* menggunakan Codeigniter 3

### Features

1. Create Bucket
2. Save file to object storage
3. Delete file from object storage
4. Get file from object storage
5. And many more

### Example
  Klik [disini](https://gitlab.skwn.dev/yaayakk.sekawanmedia/minio-s3/-/tree/main/example) untuk melihat contoh penggunaan helper

### Installation

Gunakan [Composer](https://getcomposer.org/download/) untuk menginstall aws/aws-sdk-php pada project ini :

```shell
$ composer require aws/aws-sdk-php
```

### Setting env
```shell
S3_BUCKET=""
S3_HOST=""
S3_ACCESS_KEY=""
S3_SECRET_KEY=""
S3_REGION="us-east-1"
```

### Deklarasi Class
```php
	$s3 = (new S3());
```

### Create Bucket
```php
    $s3 = (new S3());	
    $bucketName = "test_bucket";
	$s3->createBucket($bucketName);
```

### Save file to Object Storage
```php
  $s3 = (new S3());		

   $image = convertImageToBase64($_FILES["gambar"]);
   $path = 'foto/';
   $filename = gen_uuid().$image["ext"];
   $opr = $s3->saveFile([
       "body"=> $image["data"],
		"file_name"=> $path.$filename,
		"contentType"=> $image["mimeType"]
	]);
```
***Keterangan*** untuk upload bisa dari file di folder diserver atau menggunakan base64 langsung

### Delete file from Object Storage
```php
    $s3 = (new S3());
	$opr = $s3->deleteFile([
		"file_name" => $filename
	]);
```

### Get file from Object Storage
```php
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
```
***Keterangan*** atau bisa langsung diconvert ke base64

```php
   $s3 = (new S3());
   $filepath = 'foto/';
   $filename = "gambar.png";
		
   $opr = $s3->getFile([
	   "file_name" => $filepath.$filename,
   ]);

   	$data = "";
	if($opr["isExists"]){
		$data = "data:{$opr["ContentType"]};base64," . base64_encode($opr["Body"]);
	}
```
