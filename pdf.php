<?php
include "_settings.php";
session_start();

if(empty($_SESSION["characters_session"]["characters"])) exit;

if(empty($_SESSION["characters_admin"])) Helper::manageCounters($settings["paths"]["counters"]."exports.inc");

$content = array("specific" => simplexml_load_file($settings["paths"]["langs"].$_SESSION["characters_session"]["curlang"]."/characters.xml"));

$pdf = 1;

require $settings["paths"]["layouts"]."list.php";

require __DIR__."/vendor/mpdf/mpdf.php";
$mpdf=new mPDF('c');

$mpdf->WriteHTML($output);
$mpdf->Output();
exit;
