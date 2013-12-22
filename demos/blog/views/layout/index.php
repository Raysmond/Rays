<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title><?php echo RHtmlHelper::encode(Rays::app()->getClientManager()->getHeaderTitle()); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <meta name="description" content=""/>
    <?php $baseUrl = Rays::baseUrl(); ?>
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl ?>/public/css/ivory.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl ?>/public/css/main.css"/>
    <?php
    // link custom css files
    echo RHtmlHelper::linkCssArray(Rays::app()->getClientManager()->css);
    ?>
    <script type="text/javascript" src="<?= $baseUrl ?>/public/js/main.js"></script>

</head>

<body class="index page-<?= Rays::router()->getControllerId() . '-' . Rays::router()->getActionId() ?>">
<div class="content">
    <div id="header" class="container row">
        <div class="g960">
        <ul class="main-menu">
            <li><?= RHtmlHelper::linkAction("site", "Rays Blog", "index", null, array("style" => 'font-weight:bold;')) ?></li>
            <?php if (Rays::isLogin()) {
                ?>
                <li><?= RHtmlHelper::linkAction("post", "My posts") ?></li>
            <?php
            } ?>
            <li><?= RHtmlHelper::linkAction("site", "About", "about") ?></li>
            <li><?= RHtmlHelper::linkAction("site", "Contact", "contact") ?></li>
            <?php
            if (!Rays::isLogin()) {
                ?>
                <li><?= RHtmlHelper::linkAction("user", "Login", "login") ?></li>
                <li><?= RHtmlHelper::linkAction("user", "Register", "register") ?></li>
            <?php
            } else {
                ?>
                <li><?= RHtmlHelper::linkAction("user", "Logout", "logout") ?></li> <?php
            }
            ?>
        </ul>
        </div>
    </div>

    <div class="grid">
        <div id="main-content" class="container g960 space-bot">
            <hr>
            <div id="message">
                <?php RHtmlHelper::showFlashMessages(false); ?>
            </div>
            <div class="clearfix"></div>
            <div id="content">
                <?php if (isset($content)) echo $content; ?>
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
echo RHtmlHelper::linkScriptArray(Rays::app()->getClientManager()->script);
?>
</body>
</html>