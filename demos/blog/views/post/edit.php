<h1>New post</h1>
<?php
if (isset($errors)) {
    RHtmlHelper::showValidationErrors($errors);
}
?>

<?php
if (!isset($post))
    echo RFormHelper::openForm("post/new");
else
    echo RFormHelper::openForm("post/edit/" . $post->id);
?>

<?=(isset($post)? RFormHelper::hidden("id", $post->id) : "")?>

<?= RFormHelper::label("Title", "title") ?>
<?= RFormHelper::input("title", isset($form['title']) ? $form["title"] : (isset($post) ? $post->title : "")) ?>

<br/>

<?= RFormHelper::label("Content", "content") ?>
<br/>

<textarea cols="70" rows="7" name="content"><?= (isset($form["content"]) ? $form["content"] : (isset($post) ? $post->content : "")) ?></textarea>

<br/>
<?= RFormHelper::input(["value" => "Save", "type" => "submit"]) ?>

<?= RFormHelper::endForm() ?>