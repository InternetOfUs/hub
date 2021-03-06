<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Log in');
?>

<div class="site-login">
    <h1><?= Yii::t('common', 'Log in'); ?></h1>

    <p><?php echo Yii::t('signup', 'Please fill out the following fields to login') ?>:</p>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <?= $form->field($model, 'username_or_email')->textInput(['autofocus' => true])->label(Yii::t('common', 'Username or Email')); ?>
        <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('common', 'Password')); ?>

        <div style="margin:20px 0;">
            <p class="light">
                <?= Yii::t('signup', 'If you forgot your password you can'); ?> <?= Html::a( Yii::t('signup', 'reset it'), ['user/request-password-reset']) ?>.
                <br>
                <?= Yii::t('signup', 'Need new verification email?'); ?> <?= Html::a( Yii::t('signup', 'Resend'), ['user/resend-verification-email']) ?>
            </p>
        </div>

        <div class="form-group">
            <?= Html::submitButton( Yii::t('common', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    <hr>
    <p style="text-align:right;">or <b><?= Html::a( Yii::t('signup', 'create an account'), ['/oauth/signup', 'client_id' => $client_id, 'scope' => $scope]) ?></b></p>
</div>
