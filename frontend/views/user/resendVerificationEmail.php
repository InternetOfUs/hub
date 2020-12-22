<?php
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model \frontend\models\ResetPasswordForm */

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Resend verification email');
?>

<div class="site-resend-verification-email">
    <h1><?= Yii::t('common', 'Resend verification email'); ?></h1>

    <p><?php echo Yii::t('signup', 'Please fill out your email. A verification email will be sent there') ?>.</p>

    <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Send'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
