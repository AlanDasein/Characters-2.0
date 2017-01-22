<?php
require "../models/Characters.php";
session_start();

if(empty($_POST) || empty($_SESSION["characters_session"]["characters"])) exit;

$_SESSION["characters_session"]["characters"]->delete(
    !isset($_POST["subindex"]) ? array("index" => $_POST["index"]) : array("index" => $_POST["index"], "subindex" => $_POST["subindex"])
);