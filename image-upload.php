<?php

// Import autoloader from vendor
// If not using PSR-4 is not configured in composer.json file for your project
require_once __DIR__ . '/vendor/autoload.php';

use ImageKit\ImageKit;

ob_start();

$public_key = "public_Rn6somC89vW931qoq6aWuiqtZrM=";
$your_private_key = "private_nre4GcDbo0FrFzpOBo6seWG6GA8=";
$url_end_point = "https://ik.imagekit.io/cov790a4f";
$sample_file_path = "/abc.png";

$imageKit = new ImageKit(
    $public_key,
    $your_private_key,
    $url_end_point
);

$authenticationParameters = $imageKit->getAuthenticationParameters();

ob_end_clean();

echo(json_encode($authenticationParameters));

?>