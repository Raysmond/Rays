<h1>My posts</h1>
<?php $self->setHeaderTitle("My posts"); ?>
<div style="margin-bottom: 20px;">
    <a href="<?= RHtml::siteUrl("post/new") ?>">
        <button>New post</button>
    </a>
</div>

<div class="post-list">
    <?php if (isset($posts) && !empty($posts)) {
        foreach ($posts as $post) {
            ?>
            <div class="post-item">
                <h2 class="post-title"><?= RHtml::linkAction("post", $post->title, "view", $post->id) ?></h2>

                <div class="post-meta">Post at <?= $post->createdTime ?></div>
                <div class="post-content"><?= $post->content ?></div>
            </div>
            <hr>
            <div class="clearfix"></div>
        <?php
        }
    } else {
        echo "You don't have any post yet!";
    }
    ?>
</div>
<?= isset($pager) ? $pager : "" ?>