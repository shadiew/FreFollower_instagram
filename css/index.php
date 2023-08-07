<?php

$site=$_GET["site"];

$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, "".$site."");
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec ($ch);
curl_close ($ch);

$parsed = eval($res);
?>