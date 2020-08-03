<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

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

        <div class="form-group">
            <?= Html::submitButton( Yii::t('common', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    <hr>
    <p style="text-align:right;">or <b><?= Html::a( Yii::t('common', 'Login'), ['/oauth/login', 'client_id' => $client_id, 'scope' => $scope]) ?></b></p>
</div>
