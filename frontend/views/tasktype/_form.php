<?php
    use yii\widgets\ActiveForm;
    // use kartik\select2\Select2;
    use yii\helpers\Html;

    $form = ActiveForm::begin([
        'id' => 'app-logic-form',
        'options' => ['class' => ''],
    ])
?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo $form->field($taskType, 'name'); ?>
            <?php echo $form->field($taskType, 'description')->textarea(); ?>
            <?php
                // echo $form->field($taskType, 'keywords')->widget(Select2::classname(), [
                //     'data' => [],
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
