<?php
/* @var $this PageController */
/* @var $model Page */
/* @var $commentsModel Comment */

$this->breadcrumbs=array(
	'Pages'=>array('index'),
	$model->title,
);

?>

<h1><?php echo CHtml::encode($model->title) ?></h1>
<p><?php echo CHtml::encode($model->content) ?></p>


<?php $this->renderPartial('//comment/_pageComments', array('pageModel'=>$model, 'commentsModel' => $commentsModel, 'showButtons' => true)); ?>



