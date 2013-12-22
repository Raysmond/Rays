<h1>Contact</h1>
<?=RForm::openForm("site/contact",array('class'=>'vform','style'=>'max-width: 600px;'))?>
<?=RForm::label("Your name","name")?>
<?=RForm::input("name",isset($form["name"])?$form["name"]:"")?>

<br/>

<?=RForm::label("Your email","email")?>
<?=RForm::input("email",isset($form["email"])?$form["email"]:"")?>

<br/>
<?=RForm::label("Content","content")?>
<br/>
<textarea name="content" cols="70" rows="7"><?=(isset($form["content"])?$form['content']:"")?></textarea>

<br/>
<button type="submit">Save</button>
<?=RForm::endForm()?>