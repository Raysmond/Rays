<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title><?php echo RHtmlHelper::encode(Rays::app()->getClientManager()->getHeaderTitle()); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <meta name="description" content=""/>
    <?php $baseUrl = Rays::baseUrl(); ?>
    <link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/public/css/main.css"/>
    <?php
    // link custom css files
    echo RHtmlHelper::linkCssArray(Rays::app()->getClientManager()->css);
    ?>
    <script type="text/javascript" src="<?=$baseUrl?>/public/js/main.js"></script>

</head>

<body class="index page-<?= Rays::router()->getControllerId() . '-' . Rays::router()->getActionId() ?>">
<div id="header" class="container">
    <ul class="main-menu">
        <li><?=RHtmlHelper::linkAction("site","Home","index")?></li>
        <li><?=RHtmlHelper::linkAction("site","About","about")?></li>
        <li><?=RHtmlHelper::linkAction("site","Contact","contact")?></li>
    </ul>
</div>

<div id="main-content" class="container">
    <div id="content">
        <?php if(isset($content)) echo $content; ?>
    </div>
</div>


<div class="container">
    <div id="footer">
        <hr>
        © Copyright <?=Rays::app()->getName()?> 2013, All Rights Reserved.  by <a href="http://raysmond.com">Raysmond</a>
    </div>
</div>


<?php
// link custom script files
echo RHtmlHelper::linkScriptArray(Rays::app()->getClientManager()->script);
?>
</body>
</html>