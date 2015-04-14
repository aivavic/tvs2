<?php
/* @var $this TvseriesController */
/* @var $model Tvseries */

?>

<h1>View Tvseries #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'Country',
		'image',
		'Date',
		'Genre',
		'imdbRating'
	),
)); ?>
