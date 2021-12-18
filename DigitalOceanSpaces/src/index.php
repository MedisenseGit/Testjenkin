<?php

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

// Autoload files using the Composer autoloader.
require_once __DIR__ . '/../vendor/autoload.php';

#Todo: Move to config / environment / constants file
$key             = 'UDKKQ26QH6W4YD3ZIPAX';
$secret          = 'xr564SPJD2BWnonKsVa7zkM04NxBU/xhweOC5cM0Dxw';
$region          = 'fra1';
$version         = 'latest'; //'2006-03-01'
$endpoint        = 'fra1.digitaloceanspaces.com';
$space_name      = 'medisensemd';
$upload_dir      = 'Test';
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

    $adapter    = new AwsS3Adapter($client, $space_name);
    $filesystem = new Filesystem($adapter);

    //If file uploaded
    //$stream = fopen($_FILES[$uploadName]['tmp_name'], 'r+');

    $stream = fopen('sales_app_login_bg.jpg', 'r+');

    //If file uploaded
    //$filesystem->writeStream("{$upload_dir}/".$_FILES[$uploadName]['name'], $stream, ['visibility' => $file_visibility]);

    $filesystem->writeStream("{$upload_dir}/sales_app_login_bg.jpg", $stream, ['visibility' => $file_visibility]);

    if (is_resource($stream)) {
        fclose($stream);
    }

    #Todo: Show / Display Success notification
    echo "File Uploaded" . PHP_EOL;

    //If file uploaded
    // $file_url = "https://{$space_name}.{$endpoint}/{$upload_dir}/".$_FILES[$uploadName]['name'];

    #Todo: Public file URL to view uploaded file or save in DB for later use
    $file_url = "https://{$space_name}.{$endpoint}/{$upload_dir}/sales_app_login_bg.jpg";

    echo $file_url;

} 
catch (Exception $e) {
    echo $e->getMessage();
}

