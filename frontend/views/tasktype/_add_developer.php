<?php
    use yii\widgets\ActiveForm;
    use kartik\select2\Select2;
    use yii\web\JsExpression;
    use yii\helpers\Html;
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            $form = ActiveForm::begin([
                'id' => 'create-oauth-form',
                'options' => ['class' => ''],
            ]);
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php
                        echo $form->field($tasktypeDeveloper, 'user_id')->widget(Select2::classname(), [
                            'options' => [
                                'placeholder' => Yii::t('app', 'Select developers ...'),
                                'multiple' => true
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 2,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return '".Yii::t('app', 'Waiting for results...')."'; }"),
                                ],
                                'ajax' => [
                                    'url' => \yii\helpers\Url::to(['developer-list', 'task_type_id' => $model->id]),
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(data) { return data.email+" ("+data.username+")"; }'),
                                'templateSelection' => new JsExpression('function (data) { return data.email+" ("+data.username+")"; }'),
                            ],
                        ])->label(false);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('common', 'Add a developers to the app'), ['class' => 'btn btn-primary', 'style' => 'margin: 0 0 20px 0']) ?>
                    </div>
                </div>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
