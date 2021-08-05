<?php
    use yii\widgets\ActiveForm;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use frontend\models\WenetApp;
    use frontend\models\AppBadge;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Create new app');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'My apps'), 'url' => ['developer/index']];
    $this->params['breadcrumbs'][] = ['label' => $app->name, 'url' => ['developer/details', 'id' => $app->id, 'tab' => 'badges']];
    $this->params['breadcrumbs'][] = Yii::t('badge', 'Create a badge');
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            $form = ActiveForm::begin([
                'id' => 'badge-create-form',
                'options' => ['class' => ''],
            ])
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <?php echo $form->field($model, 'name'); ?>
                    <?php echo $form->field($model, 'description')->textarea(); ?>
                    <?php echo $form->field($model, 'message')->textarea(); ?>
                    <?php
                        $transactionLabels['null'] = Yii::t('badge', 'No transaction label');

                        echo $form->field($model, 'label')->widget(Select2::classname(), [
                            'data' => $transactionLabels,
                            'options' => [
                                'placeholder' => Yii::t('badge', 'Select transaction label ...'),
                                'multiple' => false
                            ],
                        ]);
                        echo '<p style="margin:-10px 0 20px 0;">'.Yii::t('badge', 'create badge transaction label hint').'</p>';
                    ?>
                    <?php echo $form->field($model, 'threshold'); ?>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <?php
                        $imageData = [];
                        foreach (AppBadge::badgeFiles() as $imageUrl) {
                            $imageData[$imageUrl] = $imageUrl;
                        }
                        echo $form->field($model, 'image')->radioList(
                            array_map(function($i) {
                                return '<div class="badge_image" style="background-image:url('.$i.')";></div>';},
                                        $imageData),
                                        ['encode' => false]
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
