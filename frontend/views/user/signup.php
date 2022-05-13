<?php
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model \frontend\models\SignupForm */

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Url;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Sign up');
?>

<div class="site-signup">
    <h1><?= Yii::t('common', 'Sign up'); ?></h1>

    <p><?php echo Yii::t('signup', 'Please fill out the following fields to signup') ?>:</p>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_repeat')->passwordInput() ?>

        <div class="checkboxes">
			<input value="" name="privacy_consent" id="signup_privacy_consent" type="checkbox">
			<label for="signup_privacy_consent" class="checkbox_labels">
				<?php echo Yii::t('signup', 'privacy_consent1'); ?>
				<a href="<?php echo Url::base().'/files/WeNet_Privacy-Policy.pdf'; ?>" title="<?php echo Yii::t('index', 'Privacy Policy'); ?>"><?php echo Yii::t('index', 'Privacy Policy'); ?></a>
				<?php echo Yii::t('signup', 'privacy_consent2'); ?>
			</label>
    	</div>

        <div class="g-recaptcha" data-sitekey="<?php echo Yii::$app->params['google.reCaptcha.site']; ?>" style="display:inline-block; margin-top:20px;"></div>

        <div class="form-group">
            <?= Html::submitButton( Yii::t('common', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
