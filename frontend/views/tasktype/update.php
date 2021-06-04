<?php
    use yii\widgets\ActiveForm;
    use kartik\select2\Select2;
    use kartik\switchinput\SwitchInput;
    use yii\helpers\Html;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Update app') . ' - ' . $taskType->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps logic'), 'url' => ['tasktype/index']];
    $this->params['breadcrumbs'][] = ['label' => $taskType->name, 'url' => ['tasktype/details', 'id' => $taskType->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Update app logic');
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            $form = ActiveForm::begin([
                'id' => 'app-logic-update-form',
                'options' => ['class' => ''],
            ])
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php echo $form->field($taskType, 'name'); ?>
                    <?php echo $form->field($taskType, 'description')->textarea(); ?>
                    <?php
                    echo $form->field($taskType, 'keywords')->widget(Select2::classname(), [
                        'data' => [],
                        'options' => [
                            'placeholder' => Yii::t('app', 'Select tags ...'),
                            'multiple' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php echo $form->field($taskType, 'public')->widget(SwitchInput::classname(), [
                        'pluginOptions' => [
                            'onText' => Yii::t('tasktype', 'Public'),
                            'offText' => Yii::t('tasktype', 'Private')
                        ]
                    ]); ?>
                    <br>
                    <div class="alert alert-info" role="alert" style="margin-top:-15px;">
                        <?php echo Yii::t('app', 'INFO - Once you decide to set the app logic as a public one, you will be no more able to edit or delete it. A copy of the current app logic will be automatically created.'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <hr style="margin-top:0px;">
                    <?php echo $form->field($taskType, 'attributes')->textarea(); ?>
                    <?php echo $form->field($taskType, 'transactions')->textarea(); ?>
                    <?php echo $form->field($taskType, 'callbacks')->textarea(); ?>
                    <?php echo $form->field($taskType, 'norms')->textarea(); ?>
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
