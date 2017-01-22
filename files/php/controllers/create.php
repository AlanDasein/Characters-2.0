<?php

if(empty($access)) exit;

$data = json_decode($_POST["data"], true);

$param = array("values" => array());
$param["values"][] = array("attribute" => (string)$content["specific"]->texts->item[5], "value" => "", "active" => 1);

$groups = array();

foreach($data as $k => $v) {
    $value = "";
    $attribute = $_SESSION["characters_session"]["categories"][$k]["value"];
    $aux = json_decode($v, true);
    if($aux["active"] === 1) {
        $rand = count($aux["range"]) > 0 ? rand($aux["range"][0], $aux["range"][1]) : (count($aux["options"]) > 0 ? rand(0, count($aux["options"]) - 1) : 1);
        if(count($aux["options"]) > 0) {
            $value = $_SESSION["characters_session"]["categories"][$k]["alias"] === "sexuality" && $age < 12 ? "" : $aux["options"][$rand];
            if($_SESSION["characters_session"]["categories"][$k]["alias"] === "sex") {
                $param2 = array("fields" => array("`sort`"), "table" => "`ch_features`", "where" => array("`language` = '".$route["lang"]."'", "AND `categories` = '#sex#'", "AND `value` = ?"), "limit" => 1);
                $input = array($value);
                $extra = Helper::getData($connection, $param2, $input);
                $sex = (int)$extra[0]["sort"];
            }
        }
        else {
            if($_SESSION["characters_session"]["categories"][$k]["alias"] === "age") {
                $value = $rand;
                $age = $value;
            }
            else {
                $param2 = array(
                    "fields" => array("`value`"),
                    "table" => "`ch_features`",
                    "where" => array(
                        "`language` = '".$route["lang"]."'",
                        "AND `categories` LIKE '%#".$_SESSION["characters_session"]["categories"][$k]["alias"]."#%'",
                        "AND (`sex` = 0".($sex > 0 ? " OR `sex` = ".$sex : "").")",
                        "AND `age` <= ".$age
                    ),
                    "orderby" => array("RAND()"),
                    "limit" => $rand
                );
                $stop = $_SESSION["characters_session"]["categories"][$k]["alias"] === "appearance" ? 3 : 1;
                $value = "";
                for($i = 0;$i < $stop;$i++) {
                    $param2["where"][4] = "AND `type` = ".$i;
                    if(!empty($groups[$_SESSION["characters_session"]["categories"][$k]["link"]])) {
                        $param2["where"][] = "AND `value` NOT IN (".implode(",", $groups[$_SESSION["characters_session"]["categories"][$k]["link"]]).")";
                    }
                    $values = Helper::getData($connection, $param2);
                    if($values) {
                        foreach($values as $v) {
                            $value .= $v["value"].". ";
                            if($_SESSION["characters_session"]["categories"][$k]["link"] > 0) {
                                if(empty($groups[$_SESSION["characters_session"]["categories"][$k]["link"]])) {
                                    $groups[$_SESSION["characters_session"]["categories"][$k]["link"]] = array();
                                }
                                $groups[$_SESSION["characters_session"]["categories"][$k]["link"]][] = "'".$v["value"]."'";
                            }
                        }
                    }
                }
            }
        }
    }
    else {
        if($_SESSION["characters_session"]["categories"][$k]["alias"] === "sex") $sex = 0;
        if($_SESSION["characters_session"]["categories"][$k]["alias"] === "age") $age = $settings["ranges"]["age"]["max"];
    }
    $param["values"][] = array("attribute" => $attribute, "value" => Helper::applyFormat(trim($value), $sex, $route["lang"]), "active" => 1);
}

$_SESSION["characters_session"]["characters"]->add($param);

if(empty($_SESSION["characters_session_admin"])) Helper::manageCounters($settings["paths"]["counters"]."characters.inc");

header("location: ".$route["lang"]."/".$route["page"]);

exit;

?>