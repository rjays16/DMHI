<div class="row-fluid">
    <div class="span6">
        <?php

        echo $form->DateFieldRow($prenatalVisit, 'date_visit', array(
            'class' => 'input-medium span7',
            'id' => 'date_visit',
        ));
        ?>
    </div>
    <div class="span6">
        <?php

        echo $form->TextFieldRow($prenatalVisit, 'aog', array(
            'class' => 'input-medium span7',
            'id' => 'aog',
            'readonly' => true,

        ));
        ?>
    </div>

</div>


<div class="row-fluid">
    <div class="span6">
        <?php

        echo $form->TextFieldRow($prenatalVisit, 'weight', array(
            'class' => 'input-medium span7',
            'id' => 'weight',

        ));
        ?>
    </div>
    <div class="span6">
        <?php

        echo $form->TextFieldRow($prenatalVisit, 'cardiac_rate', array(
            'class' => 'input-medium span7',
            'id' => 'cardiac_rate',

        ));
        ?>
    </div>

</div>


<div class="row-fluid">
    <div class="span6">
        <?php

        echo $form->TextFieldRow($prenatalVisit, 'respiratory_rate', array(
            'class' => 'input-medium span7',
            'id' => 'respiratory_rate',

        ));
        ?>
    </div>
    <div class="span6">
        <?php

        echo $form->TextFieldRow($prenatalVisit, 'bp', array(
            'class' => 'input-medium span7',
            'id' => 'bp',

        ));
        ?>
    </div>

</div>


<div class="row-fluid">
    <div class="span6">
        <?php

        echo $form->TextFieldRow($prenatalVisit, 'temperature', array(
            'class' => 'input-medium span7',
            'id' => 'temperature_prenatal',

        ));
        ?>
    </div>

</div>


<div class="row-fluid">
    <div class="span12">
        <?php
        $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'fa fa-save',
                'label' => 'Save',
                'htmlOptions' => array(
                    'id' => 'save-prenatal-visit',
                    'class' => 'pull-right'
                ),
            )
        );
        ?>

    </div>
</div>
<hr>
<?php
$columns = array(
    array(
        'name'   => 'date_visit',
        'header' => 'Date Visit',
    ),
    array(
        'name'   => 'aog',
        'header' => 'AOG',
        'htmlOptions' => array(),
        // 'style' => 'text-align:center;width:5%;',


        // 'type'   => 'datetime',
        // 'value'  => function ($data) {
        //     return $data['encounter_date'];
        // },
    ),
    array(
        'name' => 'weight',
        'header' => 'Weight',
    ),

    array(
        'name' => 'cardiac_rate',
        'header' => 'Cardiac Rate',
    ),

    array(
        'name' => 'respiratory_rate',
        'header' => 'Respiratory Rate',
    ),



    array(
        'name' => 'bp',
        'header' => 'BP',
    ),

    array(
        'name' => 'temperature',
        'header' => 'Temperature',
    ),
    array(
        'header'      => 'Action',
        'class'       => 'CButtonColumn',
        'htmlOptions' => array(
            // 'style' => 'text-align:center;width:5%;',
        ),
        'buttons'     => array(
            'Delete' => array(
                'label'   => 'Delete',
                'icon'    => ' fa fa-trash',

                // 'visible' => \PersonnelCatalog::model()->checkDoctor(),
                // 'url' => function ($data) {
                //     return Yii::app()->urlManager->createUrl('doctor/patient/dashboard',[
                //         'id' => $data['id']
                //     ]);
                // },
                'options' => array(
                    'id'       => 'delete_prenatal_visits',
                    'class'    => 'btn btn-danger delete_prenatal_visits',
                ),
            ),
        ),
        'template'    => '{Delete}',
    ),
);
$this->widget('bootstrap.widgets.TbGridView', array(
    'id'                    => 'date-prenatal-visits-view',
    'type'                  => array('condensed', 'bordered', 'striped', 'hover'),
    'columns'               => $columns,
    'filter'                => null,
    'dataProvider'          => $prenatalVisits,
    // 'rowCssClassExpression' => function ($row, $data) {
    //     $ts = strtotime($data['encounter_no']);
    //     if ($ts && date('Ymd', $ts) == date('Ymd')) {
    //         return 'success bold';
    //     }
    // },
    'rowHtmlOptionsExpression' => function ($row, $data) {
        return array(
            'data-id-prenatal-consultation' => $data['id']
        );
    },
    'template' => '<div class="margin-bottom-10">{items}</div>
    {summary}
    {pager}',
));

?>
<script>
    $("#save-prenatal-visit").on("click", function(e) {
        e.preventDefault();
        // if (confirm('Do you really want to save?')) {
        //     savePrenatalVisit();
        // } else {
        //     return false;
        // }
        const date_visit = $("#date_visit").val();
        const aog = $("#aog").val();

        if (date_visit == "") {
            Swal.fire(
                'Date of visit is required!',
                'Please dont leave it blank!',
                'error'
            )

            return false;
        }

        if (aog == "") {
            Swal.fire(
                'Aog is required!',
                'Please dont leave it blank!',
                'error'
            )

            return false;
        }

        Swal.fire({
            title: 'Save Date of Prenatal Visits',
            text: "Are you sure do you want to save?",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save it!'
        }).then((result) => {
            if (result.value) {
                savePrenatalVisit();
            } else {
                return false;
            }
        })
    });

    $("#date_visit").on("change", function() {

        var date = new Date($(this).val());
        var newdate = new Date(date);

        newdate.setDate(newdate.getDate() + 280);

        var dd = newdate.getDate();
        var mm = newdate.getMonth() + 1;
        var y = newdate.getFullYear();

        var someFormattedDate = mm + '/' + dd + '/' + y;
        console.log(someFormattedDate);
        // $("#aog").val(someFormattedDate);
        // document.getElementById('follow_Date').value = someFormattedDate;
    })

    function savePrenatalVisit() {
        // alert(1);
        const $form = $("#prenatal-consultation-information");
        const url = $form.data('url-save-prenatal-visit');
        const encounter_nr = $form.data('encounter_nr');
        const pid = $form.data('pid');
        const date_visit = $("#date_visit").val();
        const aog = $("#aog").val();
        const weight = $("#weight").val();
        const cardiac_rate = $("#cardiac_rate").val();
        const respiratory_rate = $("#respiratory_rate").val();
        const bp = $("#bp").val();
        const temperature = $("#temperature_prenatal").val();

        $.ajax({
            url: url,
            type: 'post',
            data: {
                pid: pid,
                encounter_nr: encounter_nr,
                date_visit: date_visit,
                aog: aog,
                weight: weight,
                cardiac_rate: cardiac_rate,
                respiratory_rate: respiratory_rate,
                bp: bp,
                temperature: temperature,
            },
            dataType: 'json',
            beforeSend: () => {
                // toastr.info('Updating changes, please wait...');
                Alerts.loading({
                    content: 'Adding data. Please wait...'
                });

            },
            success: (response) => {
                Alerts.close();
                if (response['status']) {
                    // swal("Info!", "Can`t Delete, Already Discharged!", "info");
                    $.fn.yiiGridView.update("date-prenatal-visits-view");
                    Swal.fire(
                        'Saved!',
                        response['message'],
                        'success'
                    )
                    // alert(response['message']);
                } else {
                    Swal.fire(
                        'Failed to save!',
                        response['message'],
                        'error'
                    )
                    // toastr.success('Past Medical History updated');
                    // alert(response['message']);
                }
            },

            error: () => {
                Swal.fire(
                    'Please contact your administrator!',
                    'Something went wrong!',
                    'error'
                )
                // alert('Something went wrong, Please contact your administrator');
                // toastr.error('Something went wrong, Please contact your administrator');
            }

        });
        // alert(url)
    }

    function deletePrenatalVisit(id) {

        const $form = $("#prenatal-consultation-information");
        const url = $form.data('url-delete-prenatal-visit');
        const encounter_nr = $form.data('encounter_nr');
        const pid = $form.data('pid');
        const prenatal_visit_id = id;


        $.ajax({
            url: url,
            type: 'post',
            data: {
                pid: pid,
                encounter_nr: encounter_nr,
                prenatal_visit_id: id,
            },
            dataType: 'json',
            beforeSend: () => {
                // toastr.info('Updating changes, please wait...');
                Alerts.loading({
                    content: 'Deleting data. Please wait...'
                });

            },
            success: (response) => {
                Alerts.close();
                if (response['status']) {
                    // swal("Info!", "Can`t Delete, Already Discharged!", "info");
                    $.fn.yiiGridView.update("date-prenatal-visits-view");
                    // alert(response['message']);
                    Swal.fire(
                        'Saved!',
                        response['message'],
                        'success'
                    )
                } else {
                    Swal.fire(
                        'Failed to save!',
                        response['message'],
                        'error'
                    )
                    // toastr.success('Past Medical History updated');
                    // alert(response['message']);
                }
            },

            error: () => {
                Swal.fire(
                    'Please contact your administrator!',
                    'Something went wrong!',
                    'error'
                )
                // alert('Something went wrong, Please contact your administrator');
                // toastr.error('Something went wrong, Please contact your administrator');
            }

        });
        // alert(url)
    }
</script>

<script>
    $(document).ready(function() {
        jQuery(function($) {
            var deletePrenatalVisits = $('.delete_prenatal_visits');
            deletePrenatalVisits.livequery(function(e) {
                $(this).click(function(e) {
                    e.preventDefault();
                    // var $this = $(this);

                    var $this = $(this).parents('tr');
                    var id = $this.data('id-prenatal-consultation');
                    // alert(id)
                    // if (confirm('Do you really want to delete?')) {
                    //     deletePrenatalVisit(id);
                    // } else {
                    //     return false;
                    // }
                    Swal.fire({
                        title: 'Delete Date of Prenatal Visit',
                        text: "Are you sure do you want to delete?",
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Delete it!'
                    }).then((result) => {
                        if (result.value) {
                            deletePrenatalVisit(id);
                        } else {
                            return false;
                        }
                    })
                });
            });
        });
    });
</script>