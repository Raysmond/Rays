<h1><?= $user->name ?></h1>
<div class="user-info">
    <div>Email: <?= $user->email ?></div>
    <div>
        <h2>Latest posts</h2>

        <div class="post-list">
            <?php
            if (!isset($posts) || empty($posts)){
                echo "No posts!";
            }
            else{
            ?>
            <table>
                <thead>
                <th>Title</th>
                <th>Create time</th>
                </thead>
                <?php
                foreach ($posts as $post) {
                    echo '<tr>';
                    echo '<td>' . RHtml::linkAction("post", $post->title, "view", $post->id) . '</td>';
                    echo '<td>' . $post->createdTime . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                }
                ?>
        </div>
    </div>
</div>