<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title><?php echo RHtml::encode(Rays::app()->client()->getHeaderTitle()); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <?php $baseUrl = Rays::baseUrl(); ?>

    <link rel="stylesheet" type="text/css" href="<?= $baseUrl ?>/assets/css/main.css"/>

    <?= RHtml::linkCssArray(Rays::app()->client()->css); ?>

    <script type="text/javascript" src="<?= $baseUrl ?>/assets/js/main.js"></script>
</head>

<body>
<div class="content">
    <div id="header" class="container">
        <ul class="main-menu">
            <li><?= RHtml::linkAction("site", "Home", "index", null, array("style" => 'font-weight:bold;')) ?></li>
            <li><?= RHtml::linkAction("site", "About", "about") ?></li>
            <li style="float: right;"><?= RHtml::link("Rays Github", "Rays Github", "https://github.com/Raysmond/Rays") ?></li>
        </ul>
    </div>
    <div id="main-content" class="container">
        <hr>
        <div id="message">
            <?php RHtml::showFlashMessages(false); ?>
        </div>
        <div class="clearfix"></div>
        <div id="content">
            <?php if (isset($content)) echo $content; ?>
        </div>
    </div>

    <div class="container">
        <div id="footer">
            <hr>
            © Copyright <?= Rays::app()->getName() ?> 2013, All Rights Reserved. by <a
                href="http://raysmond.com">Raysmond</a>
                <span style="float: right;"> Powered by <a
                        href="https://github.com/Raysmond/Rays">Rays</a> framework!</span>
            <br/>
                <span
                    style="color: gray;">Page generated in <?= sprintf("%.2f", (microtime(true) - Rays::$startTime) * 1000); ?>
                    ms</span>
        </div>
    </div>

</div>
<?php
// link custom script files
echo RHtml::linkScriptArray(Rays::app()->client()->script);
?>
</body>
</html>