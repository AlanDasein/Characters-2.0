<?php

require __DIR__."/files/php/models/Connection.php";
require __DIR__."/files/php/models/Helper.php";
require __DIR__."/files/php/models/Characters.php";

$connection = new Connection("root", "", "al_characters");

$settings = array(
    "paths" => array(
        "assets" => array(
            "images" => __DIR__."/files/assets/",
            "styles" => __DIR__."/files/css/",
            "scripts" => __DIR__."/files/js/"
        ),
        "layouts" => __DIR__."/files/php/views/layouts/",
        "langs" => __DIR__."/files/xml/",
        "pages" => __DIR__."/files/php/views/pages/",
        "controllers" => __DIR__."/files/php/controllers/",
        "counters" => __DIR__."/files/inc/"
    ),
    "langs" => array("sp"),
    "pages" => array("home", "characters", "help", "contact"),
    "controllers" => array("create", "message", "survey"),
    "ranges" => array(
        "age" => array("min" => 5, "max" => 100, "default" => array("min" => 10, "max" => 60)),
        "options" => array("min" => 1, "max" => 5, "default" => array("min" => 3, "max" => 5))
    ),
    "fake_data" => array("visitors" => 1471, "characters" => 3289, "exports" => 588, "votes" => 85)
);

$time = time();
$access = true;