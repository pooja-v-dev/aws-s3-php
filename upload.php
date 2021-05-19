<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// AWS Info
$bucketName = '';
$IAM_KEY = '';
$IAM_SECRET = '';

// Connect to AWS
try {
    
    $s3 = S3Client::factory(
        array(
            'credentials' => array(
                'key' => $IAM_KEY,
                'secret' => $IAM_SECRET
            ),
            'version' => 'latest',
            'region'  => 'ap-south-1'
        )
    );
} catch (Exception $e) {
    
    die("Error: " . $e->getMessage());
}


$keyName = 'test_example/' . basename($_FILES["fileToUpload"]['name']);
$pathInS3 = 'https://s3.ap-south-1.amazonaws.com/' . $bucketName . '/' . $keyName;

// Add it to S3
try {
    // Uploaded:
    $file = $_FILES["fileToUpload"]['tmp_name'];

    $s3->putObject(
        array(
            'Bucket' => $bucketName,
            'Key' =>  $keyName,
            'SourceFile' => $file,
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'ACL' => 'public-read'
        )
    );
} catch (S3Exception $e) {
    die('Error:' . $e->getMessage());
} catch (Exception $e) {
    die('Error:' . $e->getMessage());
}

$accessCode = rand ( 100000 , 999999 );

$servername = "localhost:3306";
$username = "pooja";
$password = "password";
$database = "s3DB";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_query($conn, "INSERT INTO s3Files(s3FilePath, accessCode) VALUES ('$keyName','$accessCode')") or die(mysqli_error($conn));

echo '<h4>File uploaded successfully</h4>'.'<br>';
echo '<button><a href="get.php">View Listing</a></button>';
