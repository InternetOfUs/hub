<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use frontend\models\Profile;
    use frontend\models\Nationality;
    use kartik\date\DatePicker;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Profile');
    $this->params['breadcrumbs'][] = Yii::$app->user->identity->username;
    $this->params['breadcrumbs'][] = Yii::t('common', 'Account & Profile');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Profile');
?>

<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <?php echo Yii::$app->controller->renderPartial('_menu', []); ?>
	</div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <?php
            $form = ActiveForm::begin([
                'id' => 'profile-form',
                'options' => ['class' => ''],
            ])
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <?php echo $form->field($model, 'first_name'); ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <?php echo $form->field($model, 'middle_name'); ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <?php echo $form->field($model, 'last_name'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <?php echo $form->field($model, 'prefix_name'); ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <?php echo $form->field($model, 'suffix_name'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <?php
                                echo $form->field($model, 'gender')->dropDownList(
                                    Profile::genderLabels(),
                                    ['prompt' => Yii::t('profile', 'Select gender ...')]
                                );
                                echo $form->field($model, 'locale')->dropDownList(
                                    Profile::languageLabels(),
                                    ['prompt' => Yii::t('profile', 'Select language ...')]
                                );
                                echo $form->field($model, 'nationality')->dropDownList(
                                    Nationality::nationalityLabels(),
                                    ['prompt' => Yii::t('profile', 'Select nationality ...')]
                                );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                    <?php
                        echo $form->field($model, 'birthdate')->widget(DatePicker::classname(), [
                            'value' => '',
                            'type' => DatePicker::TYPE_INLINE,
                            'pluginOptions' => [
                                'format' => 'dd-mm-yyyy',
                                'multidate' => false,
                                'startView' => 2,
                                'showWeekDays' => false,
                                'templates' => [
                                    'leftArrow' => '<i class="fa fa-chevron-left"></i>',
                                    'rightArrow' => '<i class="fa fa-chevron-right"></i>'
                                ]
                            ]
                        ]);
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
