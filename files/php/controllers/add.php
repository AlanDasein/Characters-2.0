<?php
require "../models/Characters.php";
session_start();

if(empty($_POST) || empty($_SESSION["characters_session"]["characters"])) exit;

$content = array("specific" => simplexml_load_file("../../xml/".$_SESSION["characters_session"]["curlang"]."/characters.xml"));

$oper = $_SESSION["characters_session"]["characters"]->add(
    array("index" => $_POST["index"], "values" => array("0" => array("attribute" => $_POST["attribute"], "value" => "")))
);

echo json_encode(
    array(
        "index" => $_POST["index"],
        "subindex" => $oper,
        "labels" => array("title" => (string)$content["specific"]->tooltips->item[2], "default" => (string)$content["specific"]->texts->item[7]))
);