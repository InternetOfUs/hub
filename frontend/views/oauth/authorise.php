<?php
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model \common\models\LoginForm */

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Authorise');
    $app = $model->app();
    $user = $model->user();
    print_r($app);
    print_r($user);
?>

<div class="site-login">
    <h1><?= Yii::t('common', 'Authorise'); ?></h1>

    <p><?php echo Yii::t('signup', 'App ') ?>:</p>

    <?php $form = ActiveForm::begin(['id' => 'authorisation-form']); ?>

        <p>public profile info</p>

        <?php
            if (count($model->readScope) > 0) {
                echo '<p>read</p>';
                foreach ($model->readScope as $key => $value) {
                    // code...
                }
            }
        ?>


        <?php
            if (count($model->writeScope) > 0) {
                echo '<p>write</p>';
                foreach ($model->writeScope as $key => $value) {
                    // code...
                }

            }
        ?>

        <div class="form-group">
            <?= Html::submitButton( Yii::t('common', 'Authorise'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
