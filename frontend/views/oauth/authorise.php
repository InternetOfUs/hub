<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\bootstrap4\Accordion;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Authorise');
    $app = $model->app();
    $user = $model->user();
    // print_r($app);
    // print_r($user);
?>

<div class="site-authorisation">
    <p style="text-align:center; margin-top:10px; font-size:16px;">
        <strong><?php echo "App name "; ?></strong>
        <?php echo Yii::t('authorisation', 'app text') ?>:
    </p>
    <?php $form = ActiveForm::begin(['id' => 'authorisation-form']); ?>
        <?php
            $items = [];

            if (count($model->publicScope) > 0) {
                $items[] = [
                    'label' => Yii::t('authorisation', 'access your public profile info'),
                    'content' => $form->field($model, 'allowedPublicScope[]')->checkboxList($model->publicScope, ['itemOptions' => ['checked' => 'checked', 'disabled' => 'disabled']])
                ];
            }

            if (count($model->readScope) > 0) {
                $items[] = [
                    'label' => Yii::t('authorisation', 'access your profile data'),
                    'content' => $form->field($model, 'allowedReadScope[]')->checkboxList($model->readScope, ['itemOptions' => ['checked' => 'checked']])
                ];
            }

            if (count($model->writeScope) > 0) {
                $items[] = [
                    'label' => Yii::t('authorisation', 'write your profile data'),
                    'content' => $form->field($model, 'allowedWriteScope[]')->checkboxList($model->writeScope, ['itemOptions' => ['checked' => 'checked']])
                ];
            }

            echo Accordion::widget([
                'items' => $items
            ]);
        ?>
        <div class="form-group" style="margin-top:15px;">
            <?= Html::submitButton( Yii::t('common', 'continue'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    <hr>
    <?= Html::a( Yii::t('authorisation', 'cancel')); ?>
</div>

<script type="text/javascript">

    if($('.accordion div.card div.collapse').hasClass('show')){
        $('.accordion div.card div.collapse').removeClass('show');
    }

</script>
