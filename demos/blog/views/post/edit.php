<h1>New post</h1>
<?php
if (isset($errors)) {
    RHtmlHelper::showValidationErrors($errors);
}
?>

<?php
if (!isset($post)){
    echo RFormHelper::openForm("post/new", array('class'=>'vform','style'=>'max-width: 600px;'));
    $self->setHeaderTitle("New post");
}
else{
    echo RFormHelper::openForm("post/edit/" . $post->id,array('class'=>'vform','style'=>'max-width: 600px;'));
    $self->setHeaderTitle("Edit : ".$post->title);
}
?>

<?=(isset($post)? RFormHelper::hidden("id", $post->id) : "")?>

<?= RFormHelper::input([
    'name'=>'title',
    'value'=>isset($form['title']) ? $form["title"] : (isset($post) ? $post->title : ""),
    'placeholder'=>'Post title']
) ?>

<br/>

<?= RFormHelper::label("Content", "content") ?>
<br/>

<textarea style="height: 240px;" name="content" placeholder="Post content"><?= (isset($form["content"]) ? $form["content"] : (isset($post) ? $post->content : "")) ?></textarea>

<br/>
<button type="submit">Save</button>

<?= RFormHelper::endForm() ?>