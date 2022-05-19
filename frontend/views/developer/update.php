<?php
    use yii\widgets\ActiveForm;
    use kartik\select2\Select2;
    use kartik\switchinput\SwitchInput;
    use yii\helpers\Html;
    use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Update app') . ' - ' . $app->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'My apps'), 'url' => ['developer/index']];
    $this->params['breadcrumbs'][] = ['label' => $app->name, 'url' => ['developer/details', 'id' => $app->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Update app');
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="alert alert-info" role="alert" style="margin-top:-15px;">
            <?php echo Yii::t('app', 'INFO - Be careful! all this information are shown to the end-user.'); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            $form = ActiveForm::begin([
                'id' => 'app-update-form',
                'options' => ['class' => ''],
            ])
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php echo $form->field($app, 'status')->widget(SwitchInput::classname(), [
                        'pluginOptions' => [
                            'onText' => Yii::t('app', 'Live'),
                            'offText' => Yii::t('app', 'In development')
                        ]
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php echo $form->field($app, 'name')->textInput(['dir' => 'auto']); ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php echo $form->field($app, 'image_url'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php echo $form->field($app, 'description')->textarea(['dir' => 'auto']); ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php
                    echo $form->field($app, 'associatedCategories')->widget(Select2::classname(), [
                        'data' => WenetApp::tagsWithLabels(),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Select tags ...'),
                            'multiple' => true
                        ],
                    ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <hr style="margin-top:0px;">
                    <p style="color:#00a3b6; font-weight:bold; margin-bottom:20px;"><?php echo Yii::t('app', 'Insert here the links where the end user can find the application'); ?>:</p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <?php echo $form->field($app, 'slFacebook'); ?>
                    <?php echo $form->field($app, 'slTelegram'); ?>
                    <?php echo $form->field($app, 'slAndroid'); ?>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <?php echo $form->field($app, 'slIos'); ?>
                    <?php echo $form->field($app, 'slWebApp'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <hr style="margin-top:0px;">
                    <p style="color:#00a3b6; font-weight:bold; margin-bottom:20px;"><?php echo Yii::t('app', 'Insert here the data related to the Privacy Policy of your app'); ?>:</p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php echo $form->field($app, 'privacy_policy_url'); ?>
                    <?php echo $form->field($app, 'privacy_policy_text')->textarea(['dir' => 'auto']); ?>
                    <div class="alert alert-info" role="alert" style="margin-top:-15px;">
                        <strong style="padding-bottom:10px; display:block;"><?php echo Yii::t('app', 'Are your data stored in EU or outside the EU?'); ?></strong>
                        <p style="padding-bottom:10px;"><?php echo Yii::t('app', 'Use one of these hints to let it know to your users. This will be the text shown right next to the checkbox field for accepting your Privacy Policy.'); ?></p>
                        <ul style="list-style-type: disc; margin-left:15px;">
                            <li style="padding-bottom:10px;"><?php echo Yii::t('app', 'I consent to the processing of data for the purposes described in the privacy policy that I have read and understood.'); ?></li>
                            <li><?php echo Yii::t('app', 'I consent to the processing of data and to  the transfer of data outside the EU for the purposes and within the limits of what is indicated in the privacy policy that I have read and understood.'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
