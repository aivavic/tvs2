<?php
echo $Values . "<br/>";
?>
<div class="calendar_box">
    <?php
    $this->widget('ext.Calendar.YiiCalendar', [
        'linksArray' => $calendar,

    ]); ?>
</div>
<div id="qvv_values">

    <?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl . '/upload/' . $subscribes[0]['image'], CHtml::encode($subscribes[0]['name']),[
        'class' => 'img_calendar'
    ]), ['/tvseries/detail/name/'.$subscribes[0]['name'],

    ]) ?>
    <h6><?php echo CHtml::encode($subscribes[0]['name']). ' (' . $subscribes[0]['Country'] . ') ' ?></h6>
</div>