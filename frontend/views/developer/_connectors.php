<?php
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;
    use kartik\switchinput\SwitchInput;
    use frontend\models\WenetApp;

    $class = 'hidden_content';
    $display = 'none';
    if($app->conversational_connector === WenetApp::ACTIVE_CONNECTOR){
        $class = '';
        $display = 'block';
    }

    if($app->conversational_connector === WenetApp::ACTIVE_CONNECTOR){ $disabled = ''; }
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <hr>
        <h4 class="oauth_info_title" style="margin-bottom:20px;"><?php echo Yii::t('app', 'Connectors'); ?></h4>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="box_container">
            <h3><?php echo Yii::t('app', 'Conversational'); ?></h3>
            <?php if($app->message_callback_url === null) { ?>
                <hr>
                <a
                    href="<?= Url::to(['/developer/conversational-connector', 'id' => $app->id]); ?>"
                    style="margin-right:10px;"
                    class="btn btn-primary pull-right"
                    title="<?php echo Yii::t('common', 'add'); ?>"
                >
                    <i class="fa fa-plus"></i> <?php echo Yii::t('common', 'add'); ?>
                </a>
            <?php } else { ?>
                <div class="disabler <?php echo $class; ?>">
                    <p>
                        <strong><?php echo Yii::t('app', 'Message Callback Url'); ?>:</strong>
                        <pre><?php echo $app->message_callback_url;?></pre>
                    </p>
                </div>
                <hr>
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'switch-form',
                        'options' => ['class' => ''],
                    ])
                ?>
                    <?php echo $form->field($app, 'conversational_connector')->widget(SwitchInput::classname(), [
                        'pluginEvents' => [
                            'switchChange.bootstrapSwitch' => "function(e){sendConversationalRequest(e.currentTarget.checked);}"
                        ],
                        'pluginOptions' => [
                            'onText' => Yii::t('app', 'Enabled'),
                            'offText' => Yii::t('app', 'Disabled')
                        ]
                    ])->label(false); ?>
                <?php ActiveForm::end() ?>
                <a
                    href="<?= Url::to(['/developer/conversational-connector', 'id' => $app->id]); ?>"
                    style="margin-bottom:10px; display: <?php echo $display; ?>"
                    class="edit_conversational btn btn-primary pull-right"
                    title="<?php echo Yii::t('common', 'edit'); ?>"
                >
                    <i class="fa fa-pencil"></i> <?php echo Yii::t('common', 'edit'); ?>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="box_container">
            <h3><?php echo Yii::t('app', 'Data'); ?></h3>
            <?php if(!$app->hasWritePermit()){ ?>
                <div class="alert alert-warning" role="alert">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo Yii::t('app', 'WARNING - The data pusher connector require at least one write permit to be requested during the OAuth2 authentication phase!'); ?>
                </div>
            <?php } else {?>
                <hr>
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'switch-form',
                        'options' => ['class' => ''],
                    ])
                ?>
                    <?php echo $form->field($app, 'data_connector')->widget(SwitchInput::classname(), [
                        'pluginEvents' => [
                            'switchChange.bootstrapSwitch' => "function(e){sendDataRequest(e.currentTarget.checked);}"
                        ],
                        'pluginOptions' => [
                            'onText' => Yii::t('app', 'Enabled'),
                            'offText' => Yii::t('app', 'Disabled')
                        ]
                    ])->label(false); ?>
                <?php ActiveForm::end() ?>
            <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript">

    function sendConversationalRequest(status){
        var url = '<?php echo Url::to("/developer/disable-conversational-connector?id=" . $app->id); ?>';
        if(status){
            url = '<?php echo Url::to("/developer/enable-conversational-connector?id=" . $app->id); ?>';
        }
        $.ajax({
            url: url,
            method:'post',
            data:{status:status},
            success:function(data){
                data = JSON.parse(data);
                if($('.disabler').hasClass('hidden_content')){
                    $('.disabler').removeClass('hidden_content');
                    $( ".edit_conversational" ).css( "display", "block" );
                } else {
                    $('.disabler').addClass('hidden_content');
                    $( ".edit_conversational" ).css( "display", "none" );
                }
                $('ul.breadcrumb').after('<div class="alert-'+data["alert_type"]+' alert fade in">'+data["message"]+'</div>');
            },
            error:function(jqXhr,status,error){
                $('ul.breadcrumb').after('<div class="alert-error alert fade in"><?php echo Yii::t('app', 'Error, please retry later.'); ?></div>');
            }
        });
    }

    function sendDataRequest(status){
        var url = '<?php echo Url::to("/developer/disable-data-connector?id=" . $app->id); ?>';
        if(status){
            url = '<?php echo Url::to("/developer/enable-data-connector?id=" . $app->id); ?>';
        }
        $.ajax({
            url: url,
            method:'post',
            data:{status:status},
            success:function(data){
                data = JSON.parse(data);
                $('ul.breadcrumb').after('<div class="alert-'+data["alert_type"]+' alert fade in">'+data["message"]+'</div>');
            },
            error:function(jqXhr,status,error){
                $('ul.breadcrumb').after('<div class="alert-error alert fade in"><?php echo Yii::t('app', 'Error, please retry later.'); ?></div>');
            }
        });
    }

</script>

<style media="screen">

    #switch-form{float: left;}
    div.disabler.hidden_content{opacity: 0.5;}

</style>
