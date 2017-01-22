<?php
require "../../../_settings.php";
session_start();

if(empty($_POST["file"]) || !file_exists($settings["paths"]["counters"].$_POST["file"].".inc") || empty($_SESSION["characters_session"]["characters"])) exit;

if(empty($_SESSION["characters_admin"])) Helper::manageCounters($settings["paths"]["counters"].$_POST["file"].".inc");