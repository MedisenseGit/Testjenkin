<?php 
use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

// Autoload files using the Composer autoloader.
require_once __DIR__ . '/../vendor/autoload.php';

function fileuploadFunc($folder_name,$sub_folder,$filename,$file_url)
{
	
	$key             = 'UDKKQ26QH6W4YD3ZIPAX';
	$secret          = 'xr564SPJD2BWnonKsVa7zkM04NxBU/xhweOC5cM0Dxw';
	$region          = 'fra1';
	$version         = 'latest'; //'2006-03-01'
	$endpoint        = 'fra1.digitaloceanspaces.com';
	$space_name      = 'medisensemd';
	$upload_dir      = $folder_name."/".$sub_folder;//'Test1';
	$file_visibility = 'public'; 

	try {

    $client = new S3Client([
        'credentials' => [
            'key'    => $key,
            'secret' => $secret,
        ],
        'region'      => $region,
        'version'     => $version,
        'endpoint'    => "https://{$endpoint}",
    ]);
	//echo"upload_dir :".$upload_dir."<br>";

    $adapter    = new AwsS3Adapter($client, $space_name);
    $filesystem = new Filesystem($adapter);

    //If file uploaded
    //$stream = fopen($_FILES[$uploadName]['tmp_name'], 'r+');

    $stream = fopen($file_url, 'r+');

    //If file uploaded
    //$filesystem->writeStream("{$upload_dir}/".$_FILES[$uploadName]['name'], $stream, ['visibility' => $file_visibility]);

    $filesystem->writeStream("{$upload_dir}/{$filename}", $stream, ['visibility' => $file_visibility]);

    if (is_resource($stream)) {
        fclose($stream);
    }

    #Todo: Show / Display Success notification
    echo "File Uploaded" . PHP_EOL;

    //If file uploaded
    // $file_url = "https://{$space_name}.{$endpoint}/{$upload_dir}/".$_FILES[$uploadName]['name'];

    #Todo: Public file URL to view uploaded file or save in DB for later use
    $file_url = "https://{$space_name}.{$endpoint}/{$upload_dir}/{$filename}";

    echo $file_url;

} 
catch (Exception $e) {
    echo $e->getMessage();
}
	
}	
	?>