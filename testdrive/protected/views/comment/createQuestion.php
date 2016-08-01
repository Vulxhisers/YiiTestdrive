<?php
/* @var $this CommentController */
/* @var $model Comment */
/* @var $commentsModel Comment[] */
/* @var $pageModel Page */

$this->breadcrumbs=array(
	'Comments'=>array('page/view', 'id' => $pageModel->id),
	'Create Question',
);

?>

<h1>Create Question</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>

<?php $this->renderPartial('//comment/_pageComments', array('pageModel'=>$pageModel, 'commentsModel' => $commentsModel, 'showButtons' => false)); ?>

