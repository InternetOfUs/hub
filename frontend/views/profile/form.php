<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use frontend\models\Profile;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Update profile');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Profile'), 'url' => ['view']];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Update profile');
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            $form = ActiveForm::begin([
                'id' => 'profile-form',
                'options' => ['class' => ''],
            ])
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                    <?php echo $form->field($model, 'first_name'); ?>
                </div>
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <?php echo $form->field($model, 'middle_name'); ?>
                </div>
                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                    <?php echo $form->field($model, 'last_name'); ?>
                </div>
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <?php echo $form->field($model, 'prefix_name'); ?>
                </div>
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <?php echo $form->field($model, 'suffix_name'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    birthdate
                    <?php //echo $form->field($model, 'description')->textarea(); ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php
                        echo $form->field($model, 'gender')->dropDownList(
                            Profile::genderLabels(),
                            ['prompt' => Yii::t('profile', 'Select gender ...')]
                        );
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php
                        echo $form->field($model, 'locale')->dropDownList(
                            Profile::languageLabels(),
                            ['prompt' => Yii::t('profile', 'Select language ...')]
                        );
                    ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <?php
                        echo $form->field($model, 'nationality')->dropDownList(
                            Profile::nationalityLabels(),
                            ['prompt' => Yii::t('profile', 'Select nationality ...')]
                        );
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
