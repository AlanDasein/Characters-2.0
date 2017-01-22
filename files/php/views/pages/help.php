<?php if(empty($access)) exit; ?>

<div class="content" dt-modules="#tabs#nav#">

    <p class="text-justify line-20"><br/><?= $content["specific"]->introduction; ?><br/></p>

    <?php $index = 0; foreach($content["specific"]->topics->item as $v) { ?>

        <div class="tab<?= ($index++ > 0 ? " hidden" : "") ?>">

            <div class='box'>
                <div class='panel panel-default'>
                    <div class='panel-body sheet'>

                        <?php
                            if($v["type"] == "manual") {
                                $vars = array($settings["ranges"]["age"]["min"], $settings["ranges"]["age"]["max"], $settings["ranges"]["options"]["min"], $settings["ranges"]["options"]["max"]);
                                $masks = array("@age:min", "@age:max", "@options:min", "@options:max");
                                $output = $v;
                                foreach($vars as $k => $v) $output = str_replace($masks[$k], $v, $output);
                                echo $output;
                            }
                            else {
                        ?>

                        <p class='text-justify line-20'>
                            <?= str_replace("#", "<b>".$content["specific"]->bar->item[$index - 1]."</b>", $content["specific"]->texts->item[0]); ?>
                        </p>
                        <hr/>
                        <div class='group'>

                        <?php
                            $aux = explode(",", $v);
                            $where = array();
                            foreach($aux as $w) $where[] = "`categories` LIKE '%#".$w."#%'";
                            $where = implode("OR ", $where)." AND `language` = '".$_SESSION["characters_session"]["curlang"]."'";
                            $records = Helper::getData(
                                $connection,
                                array("fields" => array("`value`"), "table" => "`ch_features`", "where" => array($where), "orderby" => array("`value`"))
                            );
                            foreach($records as $w) {
                        ?>

                            <div class='tag'><?= Helper::applyFormat($w["value"], 1, $_SESSION["characters_session"]["curlang"]); ?></div>

                        <?php } ?>

                        </div>

                        <?php } ?>

                    </div>
                </div>
            </div>

        </div>

    <?php } ?>

    <button type='button' class='btn btn-default btn-gotop pull-right'><?= $content["common"]->texts->item[1]; ?></button>

</div>