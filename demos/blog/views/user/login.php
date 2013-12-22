<?php
$self->setHeaderTitle("Login"); ?>
<h1>Login</h1>
<?php
if(isset($errors) && !empty($errors)){
    echo '<div>';
    RHtmlHelper::showValidationErrors($errors);
    echo '</div>';
}
?>
<?=RFormHelper::openForm("user/login",array('class'=>'vform'))?>

<?=RFormHelper::label("Username","name")?>
<?=RFormHelper::input("name",isset($form)?$form["name"]:"")?>

<br/>

<?=RFormHelper::label("Password","password")?>
<?=RFormHelper::input(['type'=>"password","name"=>"password"],isset($form)?$form["password"]:"")?>

<br/>
<button type="submit">Login</button>

<?=RFormHelper::endForm()?>
