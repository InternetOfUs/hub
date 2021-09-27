<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Update community') . ' - ' . $app->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'My apps'), 'url' => ['developer/index']];
    $this->params['breadcrumbs'][] = ['label' => $app->name, 'url' => ['/developer/details', 'id' => $app->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Community Norms');
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'app-logic-form',
        'options' => ['class' => ''],
    ]);
?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo $form->field($community, 'norms')->textarea(); ?>
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
