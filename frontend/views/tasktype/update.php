<?php
    use yii\widgets\ActiveForm;
    // use kartik\select2\Select2;
    use kartik\switchinput\SwitchInput;
    use yii\helpers\Html;
    // use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Update app') . ' - ' . $taskType->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps logic'), 'url' => ['tasktype/index']];
    $this->params['breadcrumbs'][] = ['label' => $taskType->name, 'url' => ['tasktype/details', 'id' => $taskType->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Update app logic');
?>

<!-- <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="alert alert-info" role="alert" style="margin-top:-15px;">
            <?php //echo Yii::t('app', 'INFO - Be careful! all this information are shown to the end-user.'); ?>
        </div>
    </div>
</div> -->
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            $form = ActiveForm::begin([
                'id' => 'applogic--update-form',
                'options' => ['class' => ''],
            ])
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php //echo $form->field($app, 'name'); ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php echo $form->field($taskType, 'public')->widget(SwitchInput::classname(), [
                        'pluginOptions' => [
                            'onText' => Yii::t('tasktype', 'Public'),
                            'offText' => Yii::t('tasktype', 'Private')
                        ]
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php //echo $form->field($app, 'description')->textarea(); ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php
                    //echo $form->field($app, 'associatedCategories')->widget(Select2::classname(), [
                    //     'data' => WenetApp::tagsWithLabels(),
                    //     'options' => [
                    //         'placeholder' => Yii::t('app', 'Select tags ...'),
                    //         'multiple' => true
                    //     ],
                    // ]);
                    ?>
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
