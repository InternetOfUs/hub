<?php
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model \frontend\models\ResetPasswordForm */

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Reset password');
?>

<div class="site-reset-password">
    <h1><?= Yii::t('common', 'Reset password'); ?></h1>

    <p><?php echo Yii::t('signup', 'Please choose your new password') ?>:</p>

    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
        <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'password_repeat')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
