<div>
    <?php
    if (empty($posts)) {
        echo "No new posts!";
    } else {
        ?>
        <h2>New posts</h2>
        <ul class="post-list">
            <?php
            foreach ($posts as $post) {
                echo '<li class="post-item">';
                echo RHtmlHelper::linkAction("post",$post->title,"view",$post->id);
                echo " by ".RHtmlHelper::linkAction("user",$post->user->name,"view",$post->user->id);
                echo '</li>';
            }?>
        </ul>

    <?php
    }
    ?>
</div>

