<?php
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model \frontend\models\PasswordResetRequestForm */

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Request password reset');
?>

<div class="site-request-password-reset">
    <h1><?= Yii::t('common', 'Request password reset'); ?></h1>

    <p><?php echo Yii::t('signup', 'Please fill out your email. A link to reset password will be sent there') ?>.</p>

    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Send'), ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
