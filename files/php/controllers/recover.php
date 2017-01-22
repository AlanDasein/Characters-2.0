<?php
require "../models/Characters.php";
session_start();

if(empty($_POST) || empty($_SESSION["characters_session"]["characters"])) exit;

$_SESSION["characters_session"]["characters"]->recover(!isset($_POST["index"]) ? array() : array("index" => $_POST["index"]));

echo json_encode(1);