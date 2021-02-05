<?php
    use yii\widgets\ActiveForm;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Create new app');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer'), 'url' => ['developer/index']];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Create new app');
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
                'id' => 'app-create-form',
                'options' => ['class' => ''],
            ])
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php echo $form->field($model, 'name'); ?>
                    <?php echo $form->field($model, 'description')->textarea(); ?>
                    <?php
                        echo $form->field($model, 'associatedCategories')->widget(Select2::classname(), [
                            'data' => WenetApp::tagsWithLabels(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Select tags ...'),
                                'multiple' => true
                            ],
                        ]);
                    ?>
                    <?php echo $form->field($model, 'image_url'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <hr style="margin-top:0px;">
                    <p><?php echo Yii::t('app', 'Insert here the links where the end user can find the application'); ?>:</p>
                    <?php echo $form->field($model, 'slFacebook'); ?>
                    <?php echo $form->field($model, 'slTelegram'); ?>
                    <?php echo $form->field($model, 'slAndroid'); ?>
                    <?php echo $form->field($model, 'slIos'); ?>
                    <?php echo $form->field($model, 'slWebApp'); ?>
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
