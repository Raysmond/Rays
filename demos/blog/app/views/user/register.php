<?php
$self->setHeaderTitle("Register"); ?>
<h1>Register</h1>
<?php
if(isset($errors) && !empty($errors)){
    echo '<div>';
    RHtml::showValidationErrors($errors);
    echo '</div>';
}
?>
<?=RForm::openForm("user/register",array('class'=>'vform'))?>

<?=RForm::label("Username","name")?>
<?=RForm::input("name",isset($form["name"])?$form["name"]:"")?>

<br/>

<?=RForm::label("Email","email")?>
<?=RForm::input("email",isset($form["email"])?$form["email"]:"")?>

<br/>

<?=RForm::label("Password","password")?>
<?=RForm::input(array('type'=>"password","name"=>"password"),isset($form["password"])?$form["password"]:"")?>

<br/>

<?=RForm::label("Password confirm","password-confirm")?>
<?=RForm::input(array('type'=>"password","name"=>"password-confirm"),isset($form["password-confirm"])?$form["password-confirm"]:"")?>

<br/>

<button type="submit">Register</button>

<?=RForm::endForm()?>
