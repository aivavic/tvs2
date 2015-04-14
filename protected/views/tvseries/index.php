

<div class="serials-container-list">
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $dataProvider,
        'itemView' => '_view',
        'ajaxUpdate'=>false,
        'afterAjaxUpdate'=>"function() {
        jQuery('.rating-block input').rating({'readOnly':true});
        jQuery([id*='dialog-animation'] ).dialog();
    }",

        'sorterCssClass'=>'tvs-list-head-panel',
        'sorterHeader'=>'Сортировать по ',
        'sortableAttributes'=>array(
            'name',
            'Date',
            'imdbRating',

        ),
    )); ?>
</div>