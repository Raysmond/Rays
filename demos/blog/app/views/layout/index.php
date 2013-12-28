<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title><?php echo RHtml::encode(Rays::app()->client()->getHeaderTitle()); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <meta name="description" content=""/>
    <?php $baseUrl = Rays::baseUrl(); ?>
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl ?>/assets/css/ivory.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl ?>/assets/css/main.css"/>
    <?=RHtml::linkCssArray(Rays::app()->client()->css);?>

    <script type="text/javascript" src="<?= $baseUrl ?>/assets/js/main.js"></script>

</head>

<body class="index page-<?= Rays::router()->getControllerId() . '-' . Rays::router()->getActionId() ?>">
<div class="content">
    <div id="header" class="container row">
        <div class="g960">
        <ul class="main-menu">
            <li><?= RHtml::linkAction("site", "Rays Blog", "index", null, array("style" => 'font-weight:bold;')) ?></li>
            <?php if (Rays::isLogin()) {
                ?>
                <li><?= RHtml::linkAction("post", "My posts") ?></li>
            <?php
            } ?>
            <li><?= RHtml::linkAction("site", "About", "about") ?></li>
            <li><?= RHtml::linkAction("site", "Contact", "contact") ?></li>
            <?php
            if (!Rays::isLogin()) {
                ?>
                <li><?= RHtml::linkAction("user", "Login", "login") ?></li>
                <li><?= RHtml::linkAction("user", "Register", "register") ?></li>
            <?php
            } else {
                ?>
                <li><?= RHtml::linkAction("user", "Logout", "logout") ?></li> <?php
            }
            ?>
            <li style="float: right;"><a href="https://github.com/Raysmond/Rays">Rays Github</a></li>
        </ul>
        </div>
    </div>

    <div class="grid">
        <div id="main-content" class="container g960 space-bot">
            <div class="c9 first">
                <div id="message">
                    <?php RHtml::showFlashMessages(false); ?>
                </div>
                <div class="clearfix"></div>
                <div id="content">
                    <?php if (isset($content)) echo $content; ?>
                </div>
            </div>
            <div class="c3 last">
                <?php $self->module("new_posts"); ?>
            </div>

        </div>

        <div class="container g960">
            <div id="footer">
                <hr>
                © Copyright <?= Rays::app()->getName() ?> 2013, All Rights Reserved. by <a
                    href="http://raysmond.com">Raysmond</a>
                    <span style="float: right;"> Powered by <a href="https://github.com/Raysmond/Rays">Rays</a> framework!</span>
                <br/>
                <span style="color: gray;">Page generated in <?=sprintf("%.2f", (microtime(true)-Rays::$startTime) * 1000); ?> ms</span>
            </div>
        </div>
    </div>

</div>
<?php
// link custom script files
echo RHtml::linkScriptArray(Rays::app()->client()->script);
?>
</body>
</html>