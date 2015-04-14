
<?php  foreach($model as $data):?>

    <?php
    $series = Series::model()->lastseries(1)->find('id_tvseries=:id_tvseries', [':id_tvseries' => $data['id']]);

    ?>

    <div class="item"><img src="/upload/<?php  echo $data['image'] ?>" alt="Owl Image">
    <h4><?php echo CHtml::link($data['name'] , ['tvseries/detail/name/'. $data['name']]); ?></h4>
     <h4>   <?php echo $series->id_season . ' Сезон - '. $series->name . ' Cерия' ?> </h4>

    </div>



<?php endforeach; ?>


<!---->
<!--<img src="images/carousel_01.jpg" alt=""/>-->
<!---->
<!--<div class="carousel-navigation">-->
<!--    <i class="fa fa-caret-left"></i>-->
<!--    <i class="fa fa-caret-right"></i>-->
<!--</div>-->
<!--<div class="carousel-caption-index">-->
<!--    <h3>Сериал Интерны</h3><br/>-->
<!--    <h4>4 сезон – 21серия</h4>-->
<!--</div>-->
