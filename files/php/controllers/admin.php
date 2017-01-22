<?php
session_start();

if(empty($_POST["action"])) exit;
else $action = $_POST["action"];

switch ($action) {

    case "login":
        if(!empty($_POST["p"]) && $_POST["p"] === "WAKASAKAconquesofrito") $_SESSION["characters_admin"] = "45hy(9oKjhG6y-098uJHGGht;l09iHjk";
        break;

}

header("Location: ../../../admin.php");