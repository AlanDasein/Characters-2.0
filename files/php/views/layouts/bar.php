<header class="submenu">
    <div class="row">
        <div class="col-xs-5 col-sm-7 col-md-8"><span class="caption cool-text"><?= $content["specific"]->title; ?></span></div>
        <div class="col-xs-7 col-sm-5 col-md-4 dropdown">
            <button id="submenu" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                <span class="btn-label"><?= $content["specific"]->bar->item[0]; ?></span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu " aria-labelledby="submenu" dt-pointer="<?= (empty($_SESSION["characters_session"]["survey"]) && $route["page"] === "contact") || ($_SESSION["characters_session"]["characters"]->counter === 0 && $route["page"] === "characters") ? 1 : 0 ?>">
                <?php foreach($content["specific"]->bar->item as $v) { ?><li><a href="#"><?= $v; ?></a></li><?php } ?>
            </ul>
        </div>
    </div>
</header>