<h1>Contact</h1>
<?=RFormHelper::openForm("site/contact",['class'=>'vform','style'=>'max-width: 600px;'])?>
<?=RFormHelper::label("Your name","name")?>
<?=RFormHelper::input("name",isset($form["name"])?$form["name"]:"")?>

<br/>

<?=RFormHelper::label("Your email","email")?>
<?=RFormHelper::input("email",isset($form["email"])?$form["email"]:"")?>

<br/>
<?=RFormHelper::label("Content","content")?>
<br/>
<textarea name="content" cols="70" rows="7"><?=(isset($form["content"])?$form['content']:"")?></textarea>

<br/>
<button type="submit">Save</button>
<?=RFormHelper::endForm()?>