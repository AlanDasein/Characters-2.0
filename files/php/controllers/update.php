<?php
require "../../../_settings.php";
session_start();

if(empty($_POST) || empty($_SESSION["characters_session"]["characters"])) exit;

$value = trim($_POST["value"]);

if(isset($_POST["refill"])) {

    if($_SESSION["characters_session"]["categories"][$_POST["subindex"] - 1]["alias"] === "age") {
        $value = rand($settings["ranges"]["age"]["default"]["min"], $settings["ranges"]["age"]["default"]["max"]);
    }
    else if($_SESSION["characters_session"]["categories"][$_POST["subindex"] - 1]["alias"] === "sex") {
        $record = Helper::getData($connection, array("fields" => array("`value`"), "table" => "`ch_features`", "where" => array("`categories` = '#sex#'", "AND `language` = '".$_SESSION["characters_session"]["curlang"]."'"), "orderby" => array("RAND()"), "limit" => 1));
        $value = $record[0]["value"];
    }
    else {

        $sex_index = array_search("sex", array_column($_SESSION["characters_session"]["categories"], "alias"));
        $age_index = array_search("age", array_column($_SESSION["characters_session"]["categories"], "alias"));

        $sex_label = $_SESSION["characters_session"]["characters"]->values[$_POST["index"]][$sex_index + 1]["value"];

        $record = Helper::getData(
            $connection, array(
                "fields" => array("`sort`"),
                "table" => "`ch_features`",
                "where" => array("`categories` = '#sex#'", "AND `value` = '".$sex_label."'"),
                "limit" => 1
            )
        );

        $sex = $record ? (int)$record[0]["sort"] : 1;

        $age = $_SESSION["characters_session"]["characters"]->values[$_POST["index"]][$age_index + 1]["value"];
        if($age === "") $age = $settings["ranges"]["age"]["max"];

        $param = array(
            "fields" => array("`value`"),
            "table" => "`ch_features`",
            "where" => array(
                "`language` = '".$_SESSION["characters_session"]["curlang"]."'",
                "AND `categories` LIKE '%#".$_SESSION["characters_session"]["categories"][$_POST["subindex"] - 1]["alias"]."#%'",
                "AND (`sex` = 0".($sex > 0 ? " OR `sex` = ".$sex : "").")",
                "AND `age` <= ".$age
            ),
            "orderby" => array("RAND()"),
            "limit" => 1
        );

        $link = $_SESSION["characters_session"]["categories"][$_POST["subindex"] - 1]["link"];

        if($link > 0 || $_SESSION["characters_session"]["categories"][$_POST["subindex"] - 1]["alias"] === "circumstances") {
            $exclude = array();
            foreach($_SESSION["characters_session"]["categories"] as $k => $v) {
                if($v["link"] === $link || $k === (int)$_POST["subindex"] - 1) {
                    $aux = explode(".", $_SESSION["characters_session"]["characters"]->values[$_POST["index"]][$k + 1]["value"]);
                    foreach($aux as $w => $y) $aux[$w] = trim($y);
                    $exclude[] = implode(",", $aux);
                }
            }
            $exclude = count($exclude) > 0 ? "'".str_replace(",", "','", implode(",", $exclude))."'" : "";
        }

        $aux = array();
        $stop = $_SESSION["characters_session"]["categories"][$_POST["subindex"] - 1]["alias"] === "appearance" ? 3 : 1;

        for($i = 0;$i < $stop;$i++) {
            $param["where"][4] = "AND `type` = ".$i;
            if(!empty($exclude)) $param["where"][] = "AND `value` NOT IN (".$exclude.")";
            $record = Helper::getData($connection, $param);
            if($record) $aux[] = Helper::applyFormat($record[0]["value"], $sex, $_SESSION["characters_session"]["curlang"]);
        }

        $aux = count($aux) > 0 ? implode(". ", $aux) : "";

        $value = ($_SESSION["characters_session"]["categories"][$_POST["subindex"] - 1]["mode"] < 2 && $_SESSION["characters_session"]["categories"][$_POST["subindex"] - 1]["alias"] !== "age") || $aux === "" ? $aux : $value.($value === "" ? "" : " ").$aux.($value === "" ? "" : ".");

    }

}

$_SESSION["characters_session"]["characters"]->update(
    array("index" => $_POST["index"], "subindex" => $_POST["subindex"], "which" => $_POST["which"], "value" => $value)
);

echo json_encode($value);
