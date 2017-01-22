<?php if(empty($access)) exit; ?>

<div class="content" dt-modules="#tabs#options#form#modal#ajax#">

    <p class="text-justify line-20"><br/><?= $content["specific"]->introduction; ?><br/></p>

    <div class="tab">

        <form class='box' method="post" action="<?= $route["lang"] ?>/<?= $route["page"] ?>/message">
            <div class='panel panel-default'>
                <div class='panel-body'>

                    <div class="sheet">
                        <div class="header"><label class="txt"><?= $content["specific"]->form->labels->item[0]; ?></label></div>
                        <div class="text"><input type="text" name="name" class="txt" /><span class="bar"></span></div>
                    </div>

                    <div class="sheet">
                        <div class="header"><label class="txt"><?= $content["specific"]->form->labels->item[1]; ?></label></div>
                        <div class="text"><input type="email" name="email" class="txt" /><span class="bar"></span></div>
                    </div>

                    <div class="sheet">
                        <div class="header"><label class="txt"><?= $content["specific"]->form->labels->item[2]; ?></label></div>
                        <div class="text">
                            <select name="subject" class="txt">
                                <option></option>
                                <?php foreach($content["specific"]->form->options->item as $v) { ?><option><?= $v; ?></option><?php } ?>
                            </select>
                            <span class="bar"></span>
                        </div>
                    </div>

                    <div class="sheet">
                        <div class="header"><label class="txt"><?= $content["specific"]->form->labels->item[3]; ?></label><span class="bar"></span></div>
                        <div class="text textarea"><textarea name="message" class="txt"></textarea><span class="bar"></span></div>
                    </div>

                    <div class="sheet">
                        <div class="header">
                            <label class="txt">
                                <?php $kaptcha = Helper::getKaptcha(); ?>
                                <?= $content["specific"]->form->placeholder->item[0]; ?> <?= $content["specific"]->form->placeholder->item[is_numeric($kaptcha[0]) ? 1 : 2]; ?>&nbsp;
                                <span class="text-warning"><?= $kaptcha[0]; ?> - <span class="kaptcha" dt-ref="<?= $kaptcha[1]; ?>">?</span> - <?= $kaptcha[2]; ?></span>
                            </label>
                        </div>
                        <div class="text"><input type="text" name="kaptcha" class="txt" /><span class="bar"></span></div>
                    </div>

                    <div class="text-right text-warning">
                        <small><i><?= $content["specific"]->form->sign; ?></i></small>&nbsp;<br/><br/>
                        <button type="reset" class="btn btn-warning"><?= $content["specific"]->buttons->item[0]; ?></button>
                        <button type="submit" class="btn btn-danger"><?= $content["specific"]->buttons->item[1]; ?></button>
                    </div>

                </div>
            </div>
        </form>

    </div>

    <div class="tab hidden">

        <?php $survey_taken = !empty($_SESSION["characters_session"]["survey"]); ?>

        <?php if($survey_taken) { ?>

            <div class="box">

        <?php } else { ?>

            <form class='box' method="post" action="<?= $route["lang"] ?>/<?= $route["page"] ?>/survey">
                <input type="hidden" name="data" />

        <?php } ?>

            <div class='panel panel-default'>
                <div class='panel-body'>

                    <?php if(!$survey_taken) { ?><p class="text-justify line-20"><?= $content["specific"]->survey->introduction; ?></p><hr/><?php } ?>

                    <?php

                    $index = 0;

                    if($survey_taken) {

                        $groups = array();
                        $param = array("table" => "gn_survey", "fields" => array(), "where" => array("id > ".$settings["fake_data"]["votes"]));

                        $param["fields"][] = "COUNT(*) as total";

                        foreach($content["specific"]->survey->sections->item as $v) {
                            $groups[] = count($v->topics->item);
                            foreach($v->topics->item as $w) $param["fields"][] = "SUM(`p".(++$index)."`) as `t".$index."`";
                        }

                        $results = Helper::getData($connection, $param);

                        foreach($results[0] as $k => $v) {
                            if($k !== "total" && $results[0][$k] > 0) $results[0][$k] = round(($results[0][$k] / $results[0]["total"]), 1);
                        }

                        $results[0]["groups"] = array();

                        $index = 0;

                        foreach($groups as $v) {
                            $aux = 0;
                            for($i = $index, $j = $index + $v;$i < $j;$i++) $aux += $results[0]["t".($i + 1)];
                            $results[0]["groups"][] = $aux > 0 ? round(($aux / $v), 1) : $aux;
                            $index += $v;
                        }

                        $aux = 0;

                        foreach($results[0]["groups"] as $v) $aux += $v;

                        $general = $aux > 0 ? round($aux / count($groups), 1) : $aux;

                        $points = count($content["specific"]->survey->buttonLabels->item);

                        $index = 0;
                        $counter = 0;

                    }

                    foreach($content["specific"]->survey->sections->item as $v) {

                    ?>

                    <div class="sheet">
                        <div class="group">
                            <span class="cool-text"><?= $v->title; ?></span>

                            <?php if($survey_taken) { $aux = $results[0]["groups"][$counter++]; ?>

                                <div class="pull-right">
                                    <span class="score text-warning"><b><?= Helper::formatNumber($aux); ?></b></span>
                                    <div class="stars">
                                        <div style="width:<?= ($aux > 0 ? ($aux / $points) * 100 : 0); ?>%"></div><img src="files/assets/stars.png">
                                    </div>
                                </div>

                            <?php } foreach($v->topics->item as $w) { ?>

                                <div class="group header"><label class="txt"><?= $w; ?></label></div>
                                <div class="group">
                                    <div class="group checkgroup serie">

                                        <?php if($survey_taken) { $aux = Helper::formatNumber($results[0]["t".(++$index)]); ?>

                                            <div class="stars">
                                                <div style="width:<?= ($aux > 0 ? ($aux / $points) * 100 : 0); ?>%"></div><img src="files/assets/stars.png">
                                            </div>
                                            <label class="score text-warning"><?= $aux; ?></label>

                                        <?php } else { foreach($content["specific"]->survey->buttonLabels->item as $x) { ?>

                                            <div class="checkbutton clickable alpha" role="control">
                                                <span class="checkmark">&#x2713;</span>&nbsp;&nbsp;<?= $x; ?>
                                            </div>

                                        <?php }} ?>

                                    </div>
                                </div>

                            <?php } ?>

                        </div>
                    </div><br/>

                    <?php } if($survey_taken) { ?>

                        <hr/>
                        <div class="sheet">
                            <div class="group">
                                <span class="cool-text"><?= $content["specific"]->texts->item[2]; ?></span>
                                <div class="pull-right">
                                    <span class="score text-warning"><b><?= Helper::formatNumber($general); ?></b></span>
                                    <div class="stars">
                                        <div style="width:<?= ($general > 0 ? ($general / $points) * 100 : 0); ?>%"></div><img src="files/assets/stars.png">
                                    </div>
                                    <div class="text-right">
                                        <label><?= $content["specific"]->texts->item[3]; ?>:</label>&nbsp;&nbsp;
                                        <span class="text-warning"><?= Helper::NumberShort($results[0]["total"] + $settings["fake_data"]["votes"]); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } else { ?>

                        <div class="text-right text-warning">
                            <button type="submit" class="btn btn-danger"><?= $content["specific"]->buttons->item[1]; ?></button>
                        </div>

                    <?php } ?>

                </div>
            </div>

            <?php if($survey_taken) { ?></div><?php } else { ?></form><?php } ?>

    </div>

    <div class="tab hidden">

        <div class='box'>
            <div class='panel panel-default'>
                <div class='panel-body'>
                    <p class="text-justify line-20"><?= $content["specific"]->donation->introduction; ?></p><hr/><br/>
                    <label><?= $content["specific"]->donation->text; ?></label><br/>
                    1KtA6PXUeFY2aeQa9XnG3zmtvVBgi52Yh3<br/><br/>
                    <img src="files/assets/qr.png?v=<?= $time; ?>" />
                </div>
            </div>
        </div>

    </div>

</div>
