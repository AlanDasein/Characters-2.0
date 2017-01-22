<?php

if(empty($access) || empty($_POST["message"])) exit;

Helper::setData($connection, "gn_contact", array(
    "time" => "NOW()",
    "ip" => Helper::getIp(),
    "name" => trim($_POST["name"]),
    "email" => Helper::validMail($_POST["email"]) ? $_POST["email"] : "",
    "subject" => trim($_POST["subject"]),
    "message" => trim($_POST["message"])
));

$_SESSION["characters_session"]["alert"] = (string)$content["specific"]->texts->item[0];

header("location: ".$route["lang"]."/contact");

exit;

?>
