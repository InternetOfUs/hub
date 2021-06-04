<?php
    use yii\widgets\ActiveForm;
    // use kartik\select2\Select2;
    use yii\helpers\Html;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Create new app logic');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps logic'), 'url' => ['tasktype/index']];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Create new app logic');
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            $form = ActiveForm::begin([
                'id' => 'app-logic-create-form',
                'options' => ['class' => ''],
            ]);

            print_r($model);
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php //echo $form->field($model, 'name'); ?>
                    <?php //echo $form->field($model, 'description')->textarea(); ?>
                    <?php
                        //echo $form->field($model, 'associatedCategories')->widget(Select2::classname(), [
                            // 'data' => WenetApp::tagsWithLabels(),
                            // 'options' => [
                            //     'placeholder' => Yii::t('app', 'Select tags ...'),
                            //     'multiple' => true
                            // ],
                        // ]);
                    ?>
                    <?php //echo $form->field($model, 'image_url'); ?>
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
