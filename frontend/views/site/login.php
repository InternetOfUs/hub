<?php
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model \common\models\LoginForm */

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Login');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Login');
?>

<div class="site-login">
    <h1><?= Yii::t('common', 'Login'); ?></h1>

    <p><?php echo Yii::t('signup', 'Please fill out the following fields to login') ?>:</p>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <?= $form->field($model, 'username_or_email')->textInput(['autofocus' => true])->label(Yii::t('common', 'Username or Email')); ?>
        <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('common', 'Password')); ?>
        <?= $form->field($model, 'rememberMe')->checkbox()->label(Yii::t('signup', 'Remember me')); ?>

        <!-- <div style="margin:20px 0;">
            <p class="light">
                <?//= Yii::t('signup', 'If you forgot your password you can'); ?> <?//= Html::a( Yii::t('signup', 'reset it'), ['site/request-password-reset']) ?>.
                <br>
                <?//= Yii::t('signup', 'Need new verification email?'); ?> <?//= Html::a( Yii::t('signup', 'Resend'), ['site/resend-verification-email']) ?>
            </p>
        </div> -->

        <div class="form-group">
            <?= Html::submitButton( Yii::t('common', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
