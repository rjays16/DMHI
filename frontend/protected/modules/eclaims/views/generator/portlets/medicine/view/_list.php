<?php
/**
 * _list.php
 *
 * @author Jan Chris S. Ogel <iamjc93@gmail.com>
 * @copyright (c) 2019, Segworks Technologies Corporation (http://www.segworks.com)
 */

\Yii::import('bootstrap.widgets.TbButton');

$columns = array(
    array(
        'name' => 'generic',
        'header' => 'Drug Description',
        'type' => 'raw',
    ),
    array(
        'name' => 'route',
        'header' => 'Route',
        'type' => 'raw',
    ),
    array(
        'name' => 'frequency',
        'header' => 'Frequency',
        'type' => 'raw',
    ),
    array(
        'name' => 'quantity',
        'header' => 'Quantity',
        'type' => 'raw',
        'headerHtmlOptions' => array('style' => 'text-align: center; width: 100px;'),
    ),
    array(
        'name' => 'cost',
        'header' => 'Total Amount',
        'type' => 'raw',
        'headerHtmlOptions' => array('style' => 'text-align: center; width: 100px;'),
    ),
    array(
        /* Below are the button changes if returned or not. */
        'header' => 'Action',
        'class' => 'CButtonColumn',
        'headerHtmlOptions' => array('style' => 'text-align: center; width: 100px;'),
//        'htmlOptions' => array('style' => 'align: center; text-align: center;'),
        'buttons' => array(
            'Delete' => array(
                'label' => 'Delete',
                'icon' => ' fa fa-trash',
                'options' => array(
                    'id' => 'medicine_delete',
                    'class' => 'btn btn-danger medicine_delete',
                ),
            ),
        ),
        'template' => '{Delete}',
    ),
);


$dataProvider = Cf4Medicine::model()->getMedicine($encounter->encounter_nr);

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'medicine_list',
    'type' => array('condensed', 'bordered', 'striped', 'hover'),
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    'rowHtmlOptionsExpression' => function ($row, $data) {
        return array(
            'data-id' => $data->id,
        );
    },
    'template' => "{items}\n{summary}\n{pager}",
));

?>

<script>
    $(document).ready(function () {
        jQuery(function ($) {
            var medicine_delete = $('.medicine_delete');
            medicine_delete.livequery(function (e) {
                $(this).click(function (e) {
                    e.preventDefault();
                    var $this = $(this).parents('tr');
                    var id = $this.data('id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            destroyMedicine(id);
                        } else {
                            return false;
                        }
                    });
                });
            });
        });
    });

    function destroyMedicine(id) {
        const $form = $("#medicine-form");
        const url = $form.data('url-delete');
        const encounter_nr = $form.data('encounter_nr');
        const pid = $form.data('pid');

        $.ajax({
            url: url,
            type: 'post',
            data: {
                encounter_nr: encounter_nr,
                pid: pid,
                id: id,
            },
            dataType: 'json',
            beforeSend: () => {
            },
            success: (response) => {
                console.log(response);
                if (response.status) {
                    Swal.fire({
                        title: 'The data has been deleted!',
                        type: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        title: 'Something went wrong!',
                        text: response.message,
                        type: 'error',
                        showConfirmButton: true,
                    });
                }
                $.fn.yiiGridView.update("medicine_list");
            },
            error: (response) => {
                Swal.fire({
                    title: 'Something went wrong, Please contact your administrator',
                    type: 'error',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }
</script>