<?php
require "../models/Characters.php";
session_start();

$_SESSION["characters_session"]["characters"] = new Characters();

echo json_encode(1);