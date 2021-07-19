<?php
    use kartik\dialog\Dialog;

    echo Dialog::widget([
        'options' => [
            'type' => Dialog::TYPE_DANGER, // bootstrap contextual color
            'title' =>  $title,
            'closable' => true,
            'btnOKClass' => 'btn-danger',
            'btnOKLabel' => '<i class="fa fa-trash"></i> '.Yii::t('common', 'delete'),
            'btnCancelLabel' => Yii::t('common', 'cancel')
        ]
    ]);
?>

<script type="text/javascript">

    $('.open_modal').parent().on('click', function(event) {
        event.preventDefault();
        // console.log(event.delegateTarget.href);

        krajeeDialog.confirm("<?php echo Yii::t('common', 'Are you sure you want to proceed?'); ?>", function (result) {
            if (result) {
                window.location.href = event.delegateTarget.href;
            }
        });
    });

</script>
