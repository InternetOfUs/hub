<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Add Telegram platform');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer'), 'url' => ['/wenetapp/index-developer']];
    $this->params['breadcrumbs'][] = ['label' => $app->name, 'url' => ['/wenetapp/details-developer', 'id' => $app->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Add Telegram platform');
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            $form = ActiveForm::begin([
                'id' => 'telegram-create-form',
                'options' => ['class' => ''],
            ])
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php echo $form->field($model, 'bot_username'); ?>
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
