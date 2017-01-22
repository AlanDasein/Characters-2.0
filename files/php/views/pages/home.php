<?php if(empty($access)) exit; ?>

<div class="content main" dt-modules="#social#ajax#">

    <h1 class="col-xs-12 col-sm-5 col-md-4 title cool-text text-center">
        <?= $content["specific"]->head->title; ?>
        <div class="icons text-center">
            <a href="#" role="share" dt-target="facebook" parm-w="570" parm-h="380" class="pull-left"><img src="files/assets/icon_facebook.png" /></a>
            <a href="#" role="share" dt-target="google" parm-w="550" parm-h="570"><img src="files/assets/icon_google.png" /></a>
            <a href="#" role="share" dt-target="twitter" parm-w="570" parm-h="335" class="pull-right"><img src="files/assets/icon_twitter.png" /></a>
        </div>
    </h1>

    <blockquote class="col-xs-12 col-sm-7 col-md-8 title text-justify">
        <?= $content["specific"]->head->text; ?>
    </blockquote>

    <div class="clearfix"></div>

    <div class="jumbotron">
        <h2 class="cool-text text-left"><b><?= $content["specific"]->panel->title; ?></b></h2>
        <p class="text-justify"><?= $content["specific"]->panel->text; ?></p>
        <a href="<?= $route["lang"]; ?>/characters" class="btn btn-warning"><?= $content["specific"]->panel->button; ?></a>
    </div>

    <?php if(empty($_SESSION["characters_session"]["survey"])) { ?>

        <div class="alert alert-dismissable alert-warning">
            <a href="<?= $route["lang"]; ?>/contact"><?= $content["common"]->ads->item[0]; ?></a>
        </div>

    <?php } ?>

    <h1 class="caption"><b><?= $content["specific"]->body->title; ?></b></h1><br/>

    <?php
    $index = 0;
    foreach ($content["specific"]->body->text as $v) {
        echo ($index % 2 === 0 ? "<div class='col-xs-12 col-sm-6 text-justify body'>" : "").
             "<p>".str_replace("@lang", $route["lang"], $content["specific"]->body->text[$index])."</p>".
             ($index % 2 === 0 ? "" : "</div>");
        $index++;
    }
    ?>

    <div class="clearfix"></div>

    <div class="col-xs-12 info">

        <h1 class="caption"><b><?= $content["specific"]->stats->title; ?></b></h1><br/>

        <?php

        $info["visitors"] = file_get_contents("files/inc/visitors.inc");
        $info["characters"] = file_get_contents("files/inc/characters.inc");
        $info["exports"] = file_get_contents("files/inc/exports.inc");
        $info["charactersByVisitors"] = $info["characters"] / $info["visitors"];
        $info["exportsByVisitors"] = $info["exports"] / $info["visitors"];
        $info["charactersByExports"] = $info["characters"] / $info["exports"];

        $index = 0;

        foreach($info as $v) {
            echo "<div class='col-xs-12'>
                    <span class='cool-text'>".$content['specific']->stats->item[$index++]."</span>
                    <span class='numeric text-warning pull-right'>".($index < 3 ? number_format($v) : round($v, 1))."</span>
                  </div>";
        }

        ?>

    </div>

    <div class="clearfix"></div><br/>

    <div class="col-xs-12 jumbotron text-left">
        <h2 class="panel panel-default text-center"><div class="panel-heading">¿Qué opinión han tenido los grandes escritores de sus personajes?</div></h2><br/>
        <?php
        foreach($content['specific']->quotes->item as $v) {
            echo "<div class='panel'>
                    <div class='panel-body'>
                        <blockquote".(empty($style) ? "" : $style).">
                            <p>".$v->cite."</p>
                            <footer class='text-warning'>".$v->author."</footer>
                        </blockquote>
                    </div>
                  </div><br/>";
            $style = empty($style) ? " class='blockquote-reverse'" : "";
        }
        ?>
    </div>

    <div class="clearfix"></div>

</div>