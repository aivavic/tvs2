<?php

?>

<table class="series_grid">

    <?php
for($i=0; $i<count($data); $i++) : ?>

    <tr>
        <td>
           <?php  echo CHtml::link("Серия - " . $data[$i]->name, array('/Series/view/'.$data[$i]->id)); ?>
        </td>
        <td>
            <?php echo $data[$i]->caption; ?>
        </td>
        <td>
            <?php echo $data[$i]->date ?>
        </td>
    </tr>

<?php endfor; ?>
</table>