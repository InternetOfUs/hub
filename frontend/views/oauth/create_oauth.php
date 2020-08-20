<?php
    use yii\widgets\ActiveForm;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use frontend\models\AuthorisationForm;
    use frontend\models\AppSocialLogin;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'OAuth2 configuration');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer'), 'url' => ['/developer/index']];
    $this->params['breadcrumbs'][] = ['label' => $app->name, 'url' => ['/developer/details', 'id' => $app->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'OAuth2 configuration');

    $itemOptions = [];
    if($model->scenario == AppSocialLogin::SCENARIO_CREATE){
        $itemOptions = ['checked' => 'checked'];
    }
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            $form = ActiveForm::begin([
                'id' => 'create-oauth-form',
                'options' => ['class' => ''],
            ])
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php echo $form->field($model, 'callback_url'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php
                        echo $form->field($model, 'allowedScopes')->widget(Select2::classname(), [
                            'data' => AuthorisationForm::scope(),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Select permissions ...'),
                                'multiple' => true
                            ],
                        ]);
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
