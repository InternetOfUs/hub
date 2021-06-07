<?php
    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Update app') . ' - ' . $taskType->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps logic'), 'url' => ['tasktype/index']];
    $this->params['breadcrumbs'][] = ['label' => $taskType->name, 'url' => ['tasktype/details', 'id' => $taskType->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Update app logic');
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php echo Yii::$app->controller->renderPartial('_form', ['taskType' => $taskType]); ?>
    </div>
</div>
