<?php
require "_settings.php";
session_start();

$route = Helper::getEnv($settings, Helper::getPath(__FILE__, $_SERVER["REQUEST_URI"]));

$content = array(
    "common" => simplexml_load_file($settings["paths"]["langs"].$route["lang"]."/_global.xml"),
    "specific" => simplexml_load_file($settings["paths"]["langs"].$route["lang"]."/".$route["page"].".xml")
);

if(!empty($route["action"])) require $settings["paths"]["controllers"].$route["action"].".php";

if(empty($_SESSION["characters_session"]) || $_SESSION["characters_session"]["curlang"] !== $route["lang"]) {
    if(empty($_SESSION["characters_session"]) && empty($_SESSION["characters_admin"])) {
        Helper::manageCounters($settings["paths"]["counters"]."visitors.inc");
    }
    $param = array("fields" => array("`value`", "`alias`", "`mode`", "`link`"), "table" => "`ch_categories`", "where" => array("`language` = '".$route["lang"]."'"), "orderby" => array("`sort`"));
    $categories = Helper::getData($connection, $param);
    $_SESSION["characters_session"] = array(
        "characters" => empty($_SESSION["characters_session"]["characters"]) ? new Characters() : $_SESSION["characters_session"]["characters"],
        "categories" => $categories,
        "curlang" => $route["lang"],
        "survey" => empty($_SESSION["characters_session"]["survey"]) ? "" : $_SESSION["characters_session"]["survey"]
    );
}

header("Content-Type: text/html; charset=utf-8");
header("Content-Security-Policy: frame-ancestors none");
?>

<!DOCTYPE html>
<html>
<head>
    <?php require $settings["paths"]["layouts"]."meta.php"; ?>
</head>
<body class="hidden" dt-settings='<?= json_encode(array("age" => array($settings["ranges"]["age"]["min"], $settings["ranges"]["age"]["max"]), "options" => array($settings["ranges"]["options"]["min"], $settings["ranges"]["options"]["max"]))); ?>'>
<header>
    <?php require $settings["paths"]["layouts"]."header.php"; ?>
</header>
<section class="container page">
    <?php if(!empty($content["specific"]->title)) require $settings["paths"]["layouts"]."bar.php"; ?>
    <article><?php require $settings["paths"]["pages"].$route["page"].".php"; ?></article>
</section>
<footer class="hidden">
    <span dt-action="result"><?php if(!empty($_SESSION["characters_session"]["alert"])) echo $_SESSION["characters_session"]["alert"]; ?></span>
    <?php foreach($content["common"]->alerts->item as $v) { ?><span dt-action="<?= $v["action"]; ?>"><?= $v; ?></span><?php } ?>
</footer>
<div id="modal" class="modal fade" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body"></div>
            <div class="modal-footer" dt-btn="<?= $content["common"]->modalButtons->item[0]; ?>,<?= $content["common"]->modalButtons->item[1]; ?>"></div>
        </div>
    </div>
</div>
</body>
</html>

<?php $_SESSION["characters_session"]["alert"] = ""; ?>