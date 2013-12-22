<h1>Rays Blog</h1>
<p>
    Welcome to Rays blog site!
</p>

<div class="post-list">
    <?php if (empty($posts)) {
        echo "No posts in the site yet!";
    } else {
        foreach ($posts as $post) {
            ?>
            <div class="post-item">
                <h2 class="post-title"><?= RHtml::linkAction('post', $post->title, 'view', $post->id) ?></h2>

                <div class="post-meta">
                    <?= RHtml::linkAction('user', $post->user->name, 'view', $post->user->id) ?> post at <?= $post->createdTime ?>
                </div>

                <div class="post-content">
                    <?php
                    if (mb_strlen($post->content) > 600) {
                        echo RString::utf8_substring($post->content, 0, 600) . "...";
                    } else
                        echo $post->content;
                    ?>
                </div>
            </div>
            <hr>
        <?php
        }
    }?>
</div>
<div class="clearfix"></div>

<div><?=isset($pager)?$pager:""?></div>
