<?php

$this->breadcrumbs=array(
    'Register'
);

?>

    <h1>Register</h1>

<?php $this->renderPartial('_registerForm', array('model'=>$model)); ?>