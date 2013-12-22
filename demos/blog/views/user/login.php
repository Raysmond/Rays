<?php
$self->setHeaderTitle("Login"); ?>
<h1>Login</h1>
<?php
if(isset($errors) && !empty($errors)){
    echo '<div>';
    RHtml::showValidationErrors($errors);
    echo '</div>';
}
?>
<?=RForm::openForm("user/login",array('class'=>'vform'))?>

<?=RForm::label("Username","name")?>
<?=RForm::input("name",isset($form)?$form["name"]:"")?>

<br/>

<?=RForm::label("Password","password")?>
<?=RForm::input(array('type'=>"password","name"=>"password"),isset($form)?$form["password"]:"")?>

<br/>
<button type="submit">Login</button>

<?=RForm::endForm()?>
