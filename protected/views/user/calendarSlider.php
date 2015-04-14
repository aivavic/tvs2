<?php



?>


<?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl . '/upload/' . $model->image, CHtml::encode($model->name),[
    'class' => 'img_calendar'
]), ['/tvseries/detail/name/'.$model->name,

]) ?>
<h6><?php echo CHtml::encode($model->name). ' (' . $model->Country . ') ' ?></h6>
<h6><?php echo CHtml::encode($last_series[0]['id_season']) . " Сезон" ?></h6>
<h6><?php echo CHtml::encode($last_series[0]['date']. ' - '. 'Серия - ' . $last_series[0]['name']) ?></h6>
<h6><?php echo CHtml::encode($last_series[1]['date']. ' - '. 'Серия - ' . $last_series[1]['name']) ?></h6>