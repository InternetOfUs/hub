<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use frontend\models\AuthorisationForm;
    use frontend\models\AppSocialLogin;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Social Login');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer'), 'url' => ['/developer/index']];
    $this->params['breadcrumbs'][] = ['label' => $app->name, 'url' => ['/developer/details', 'id' => $app->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Social Login');

    $itemOptions = [];
    if($model->scenario == AppSocialLogin::SCENARIO_CREATE){
        $itemOptions = ['checked' => 'checked'];
    }
    
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
                    <?php
                        echo $form->field($model, 'callback_url');
                        echo $form->field($model, 'allowedPublicScope[]')->checkboxList(AuthorisationForm::publicScope(), ['itemOptions' => ['checked' => 'checked', 'disabled' => 'disabled']]);
                        echo $form->field($model, 'allowedReadScope[]')->checkboxList(AuthorisationForm::readScope(), ['itemOptions' => $itemOptions]);
                        echo $form->field($model, 'allowedWriteScope[]')->checkboxList(AuthorisationForm::writeScope(), ['itemOptions' => $itemOptions]);
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
