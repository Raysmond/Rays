<?php $self->setHeaderTitle($post->title); ?>
<h1><?= $post->title ?></h1>
<div class="post-meta">
    <?= RHtml::linkAction("user", $post->user->name, "view", $post->user->id) ?> post at <?= $post->createdTime ?>
</div>
<div class="post-content">
    <?= $post->content ?>
</div>

<div class="clearfix"></div>
<div class="post-actions">
    <?php
    if (Rays::isLogin() && (Rays::user()->id == $post->uid || Rays::user()->role == "admin")) {

        echo "Actions &nbsp;";
        echo RHtml::linkAction("post", "Edit", "edit", $post->id);
        echo "&nbsp;&nbsp;";
        echo RHtml::linkAction("post", "Delete", "delete", $post->id);
    }
    ?>
</div>
