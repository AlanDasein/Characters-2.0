<?php
if(empty($access)) exit;

$actives = $_SESSION["characters_session"]["characters"]->reckon();
?>

<div class="content" dt-modules="#tabs#options#selectors#actions#form#modal#ajax#edit#nav#">

    <div class="tab">

        <?php
        if($_SESSION["characters_session"]["characters"]->counter === 0) echo "<div class='alert alert-warning'>".$content["specific"]->texts->item[0]."</div>";
        else {
            echo "<div class='commands'>".Helper::drawSection($content['specific']->section[0]->buttons->item, '', 'BUTTONS')."</div><div class='clearfix'></div><br/>";
            require $settings["paths"]["layouts"]."list.php";
            echo $output;
        }
        ?>

    </div>

    <div class="tab hidden">

        <div class="commands"><?= Helper::drawSection($content["specific"]->section[1]->buttons->item, "", "BUTTONS") ?></div>

        <div class="clearfix"></div><br/>

        <form method="post" action="<?= $route["lang"] ?>/<?= $route["page"] ?>/create">

            <input type="hidden" name="data" />

            <?php foreach($_SESSION["characters_session"]["categories"] as $k => $v) { ?>

            <div class="serie">
                <div class="bullet pull-left text-center"><?= $k + 1; ?></div>
                <div class="arrow pull-left"></div>
                <div class="box dialog">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="sheet" dt-type="<?php echo $v["alias"] === "age" ? "age" : "options"; ?>">
                                <div class="header"><label class="txt"><?= $v["value"]; ?></label></div>

                                    <?php if($v["mode"] == 1) { ?><div class="checkgroup"><?php } ?>

                                    <?php if($v["mode"] > 0) { ?>

                                    <div class="group">

                                        <?php
                                        if($v["mode"] == 1) {
                                            $param = array("fields" => array("`value`"), "table" => "`ch_features`", "where" => array("`language` = '".$route["lang"]."'", "AND `categories` = '#".$v["alias"]."#'"), "orderby" => array("`sort`"));
                                            $options = Helper::getData($connection, $param);
                                            foreach($options as $v2) {
                                        ?>

                                        <div class="checkbutton clickable alpha" role="slave" value="<?= $v2["value"]; ?>">
                                            <span class="checkmark">&#x2713;</span>&nbsp;&nbsp;<?= $v2["value"]; ?>
                                        </div>

                                        <?php } ?>

                                        <div class="checkbutton clickable text-warning alpha" role="control">
                                            <span class="checkmark">&#x2713;</span>&nbsp;&nbsp;<?= $content["specific"]->texts->item[4]; ?>
                                        </div>

                                        <?php } else { ?>

                                        <div>Seleccionar entre</div>

                                        <?php foreach($settings["ranges"][$v["alias"] === "age" ? "age" : "options"]["default"] as $k2 => $v2) { ?>

                                        <div class="actions selector">
                                            <button type="button" class="btn btn-warning selectButton">+</button>
                                            <label class="rangevalue text-center"><?= $v2; ?></label>
                                            <button type="button" class="btn btn-warning selectButton">-</button>
                                            &nbsp;&nbsp;<?= $content["specific"]->texts->item[$k2 === "min" ? 2 : 3]; ?>
                                        </div>

                                        <?php }} ?>

                                    </div>

                                    <div class="header"><label class="txt"></label></div>

                                    <?php } ?>

                                    <div class="group checkgroup">
                                        <div class="checkbutton clickable text-warning alpha" role="main">
                                            <span class="checkmark">&#x2713;</span>&nbsp;&nbsp;<?= $content["specific"]->texts->item[1]; ?>
                                        </div>
                                    </div>

                                    <?php if($v["mode"] == 1) { ?></div><?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php } ?>

        </form>

        <button type='button' class='btn btn-default btn-gotop pull-right'><?= $content["common"]->texts->item[1]; ?></button>

    </div>

</div>
