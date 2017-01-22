<nav class="navbar navbar-inverse navbar-fixed-top cool-text" role="navigation">
    <div class="container">
        <!-- Logo and responsive toggle -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="logo" href="<?= $route["lang"]; ?>/home">
                <img src="files/assets/logo.png?v=<?= $time; ?>" />
                <span><?= $content["common"]->title; ?></span>
            </a>
        </div>
        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav navbar-right">
                <?= Helper::drawSection($content["common"]->menu->item, $route); ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <?= $content["common"]->texts->item[0]; ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="about-us">
                        <?= Helper::drawSection($content["common"]->languages->item, $route, "SELECTOR"); ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>