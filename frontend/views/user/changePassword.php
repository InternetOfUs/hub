<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    $this->params['breadcrumbs'][] = Yii::$app->user->identity->username;
    $this->params['breadcrumbs'][] = Yii::t('common', 'Account & Profile');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Change password');

?>

<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <?php echo Yii::$app->controller->renderPartial('_menu', []); ?>
	</div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <?php
            $form = ActiveForm::begin([
                'id' => 'form-change-password'
            ]);
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <?= $form->field($model, 'password')->passwordInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <?= $form->field($model, 'password_repeat')->passwordInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <?= Html::submitButton( Yii::t('common', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
