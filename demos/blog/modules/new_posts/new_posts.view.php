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
                echo RHtml::linkAction("post",$post->title,"view",$post->id);
                echo " by ".RHtml::linkAction("user",$post->user->name,"view",$post->user->id);
                echo '</li>';
            }?>
        </ul>

    <?php
    }
    ?>
</div>

