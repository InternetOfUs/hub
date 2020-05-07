<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    $this->params['breadcrumbs'][] = Yii::$app->user->identity->username;
    $this->params['breadcrumbs'][] = Yii::t('common', 'Change password');

    // print_r($user);
    // exit();
?>

<div class="site-change-password">
    <h1><?= Yii::t('common', 'Change password'); ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'form-change-password']); ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_repeat')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton( Yii::t('common', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
