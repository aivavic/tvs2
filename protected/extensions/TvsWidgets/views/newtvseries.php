<div class="serials-container-list">
<?php
$this->widget('zii.widgets.CListView', array(
'dataProvider'=>$dataProvider,
'itemView'=>'_ntvs_view',
    'ajaxUpdate'=>true,
));
?>
</div>
