<?php
/* @var $this PageController */
/* @var $data Page */
?>

<div class="view">

	<h4 class="page-header"><?php echo CHtml::encode($data->title); ?></h4>
	<?php echo CHtml::link('Open', array('view', 'id'=>$data->id), array('class' => 'page-open')); ?>

</div>