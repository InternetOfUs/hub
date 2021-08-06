<?php
    use yii\widgets\ActiveForm;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use frontend\models\WenetApp;
    use frontend\models\AppBadge;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Badges');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'My apps'), 'url' => ['developer/index']];
    $this->params['breadcrumbs'][] = ['label' => $app->name, 'url' => ['developer/details', 'id' => $app->id, 'tab' => 'badges']];
    $this->params['breadcrumbs'][] = $model->isNewRecord ? Yii::t('badge', 'Create a badge') : Yii::t('badge', 'Update badge');
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

                        $transactionLabelMap = [];
                        foreach (AppBadge::getTransactionLabels($app) as $label) {
                            $transactionLabelMap[$label] = $label;
                        }

                        $messageCallbackLabelMap = [];
                        foreach (AppBadge::getMessageCallbackLabels($app) as $label) {
                            $messageCallbackLabelMap[$label] = $label;
                        }

                        if ($model->isNewRecord) {

                            $labels = [
                                'null' => Yii::t('badge', 'No label'),
                                'transactions' => $transactionLabelMap,
                                'callbacks' => $messageCallbackLabelMap,
                            ];

                            echo $form->field($model, 'label')->widget(Select2::classname(), [
                                'data' => $labels,
                                'options' => [
                                    'placeholder' => Yii::t('badge', 'Select label ...'),
                                    'multiple' => false
                                ],
                            ]);
                            echo '<p style="margin:-10px 0 20px 0;">'.Yii::t('badge', 'create badge label hint').'</p>';
                        } else {
                            if($model->details()->isTransactionBadge()){
                                echo $form->field($model, 'label')->widget(Select2::classname(), [
                                    'data' => ['transactions' => $transactionLabelMap],
                                    'options' => [
                                        'placeholder' => Yii::t('badge', 'Select label ...'),
                                        'multiple' => false
                                    ],
                                ]);
                            } else if ($model->details()->isMessageCallbackBadge()) {
                                echo $form->field($model, 'label')->widget(Select2::classname(), [
                                    'data' => ['callbacks' => $messageCallbackLabelMap],
                                    'options' => [
                                        'placeholder' => Yii::t('badge', 'Select label ...'),
                                        'multiple' => false
                                    ],
                                ]);
                            } else if ($model->details()->isTaskBadge()){
                                echo '<div class="form-group field-appbadge-label">
                                        <label class="control-label" for="appbadge-label">'.Yii::t('badge', 'Label').'</label>
                                        <p>'. Yii::t('badge', 'This is a task badge, it is not possible to select a label.') . '</p>
                                    </div>';
                            }
                        }

                    ?>
                    <?php echo $form->field($model, 'threshold'); ?>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <?php
                        if (!$model->isNewRecord) {
                            echo '<div class="form-group field-appbadge-label">
                                <label class="control-label" for="appbadge-label">'.Yii::t('badge', 'Incentive Server ID').'</label>
                                <pre>'. $model->incentive_server_id . '</pre>
                            </div>';
                        }
                    ?>
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
