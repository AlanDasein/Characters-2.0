<?php

$output = "";

for($i = $_SESSION["characters_session"]["characters"]->counter - 1, $j = -1;$i > $j;$i--) {

    if($_SESSION["characters_session"]["characters"]->active[$i] === 1) {

        $output .= empty($pdf) ? "<div class='box'><div class='panel panel-default'><div class='panel-body'>" : "<table width='100%'>";

        if(empty($pdf)) $output .= "<div class='sheets' dt-index='".$i."'>";

        foreach($_SESSION["characters_session"]["characters"]->values[$i] as $k => $v) {

            if($k === 0 || $v["active"] === 1) {

                if(empty($pdf)) {

                    $commands = $k === 0 ? "" : "<p class='actions'>
                                                    ".($k < count($_SESSION["characters_session"]["categories"]) + 1 ? "<button type='button' title='".$content["specific"]->tooltips->item[1]."' class='btn btn-warning btn-edit' dt-action='refill' dt-data='{\"index\":".$i.",\"subindex\":".$k.",\"which\":\"value\"}' dt-extra='".($_SESSION["characters_session"]["categories"][$k - 1]["mode"] > 1 && $_SESSION["characters_session"]["categories"][$k - 1]["alias"] !== "age" ? $content["specific"]->texts->item[9] : "")."'>&#8634;</button>" : "")."
                                                    <button type='button' title='".$content["specific"]->tooltips->item[2]."' class='btn btn-danger btn-edit' dt-action='delete_subsection' dt-data='{\"index\":".$i.",\"subindex\":".$k.",\"which\":\"value\"}'>x</button>
                                                 </p>";

                    $output .= "<div class='sheet'>
                                    <div class='header' dt-default='".$v["attribute"]."' dt-data='{\"index\":".$i.",\"subindex\":".$k.",\"which\":\"attribute\"}'>
                                        <label class='txt' contenteditable>".$v["attribute"]."</label><span class='bar'></span>
                                    </div>
                                    <div class='text' dt-default='".$content["specific"]->texts->item[$k === 0 ? 6 : 7]."' dt-data='{\"index\":".$i.",\"subindex\":".$k.",\"which\":\"value\"}'>
                                        <span class='txt".(empty($v["value"]) ? " text-warning" : "")."' contentEditable>".(empty($v["value"]) ? $content["specific"]->texts->item[$k === 0 ? 6 : 7] : $v["value"])."</span><span class='bar'></span>
                                    </div>
                                    ".$commands."
                                </div>";

                }
                else {

                    $output .= (
                    $k === 0
                        ? "<tr><td colspan='2' valign='top'><h1>".(empty($v["value"]) ? $content["specific"]->texts->item[6] : $v["value"])."</h1><hr/></td></tr>"
                        : "<tr><td valign='top'>".$v["attribute"]."</td><td valign='top'>".$v["value"]."</td></tr>"
                    );

                }

            }

        }

        if(empty($pdf)) $output .= "</div>
                                <div class='icons text-right'>
                                    <button type='button' class='btn btn-default btn-gotop pull-left'>".$content["common"]->texts->item[1]."</button>
                                    <button type='button' title='".$content["specific"]->tooltips->item[4]."' class='btn btn-warning' role='command' dt-action='recover_subsections' dt-data='{\"index\":".$i."}'>&#8634;</button>
                                    <button type='button' title='".$content["specific"]->tooltips->item[0]."' class='btn btn-warning btn-edit' dt-action='add' dt-data='{\"index\":".$i.",\"attribute\":\"".$content["specific"]->texts->item[8]."\"}'>+</button>
                                    <button type='button' title='".$content["specific"]->tooltips->item[3]."' class='btn btn-danger btn-edit' dt-action='delete_section' dt-data='{\"index\":".$i."}'>x</button>
                                </div>";

        $output .= empty($pdf) ? "</div></div></div>" : "</table>".($i > 0 ? "<pagebreak></pagebreak>" : "");

    }

}

?>