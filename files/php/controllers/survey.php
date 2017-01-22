<?php

if(empty($access)) exit;

$values = json_decode($_POST["data"], true);
$param = array("time" => "NOW()", "ip" => Helper::getIp());

foreach($values as $k => $v) $param["p".($k + 1)] = (int)$v + 1;

Helper::setData($connection, "gn_survey", $param);

$_SESSION["characters_session"]["survey"] = true;
$_SESSION["characters_session"]["alert"] = (string)$content["specific"]->texts->item[1];

header("location: ".$route["lang"]."/contact");

exit;

?>
