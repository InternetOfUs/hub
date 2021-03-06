<?php
    use yii\widgets\ActiveForm;
    use kartik\select2\Select2;
    use yii\helpers\Html;
    use frontend\models\TaskType;
    use common\models\User;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Conversational Connector');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'My apps'), 'url' => ['developer/index']];
    $this->params['breadcrumbs'][] = ['label' => $app->name, 'url' => ['/developer/details', 'id' => $app->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Conversational Connector');
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            $form = ActiveForm::begin([
                'id' => 'conversational-connector-form',
                'options' => ['class' => ''],
            ])
        ?>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php echo $form->field($app, 'message_callback_url'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php

                        $taskTypes = Yii::$app->user->identity->taskTypes();

                        $data = [];
                        $public = [];
                        $private = [];
                        foreach ($taskTypes as $taskType) {
                            $creator = User::findIdentity($taskType->creator_id);

                            if($taskType->public == TaskType::PUBLIC_TASK_TYPE){
                                $public[$taskType->id] = $taskType->name. ' | ' . $creator->username;
                            } else {
                                $private[$taskType->id] = $taskType->name. ' | ' . $creator->username;
                            }
                        }
                        $data[Yii::t('tasktype', 'Public')] = $public;
                        $data[Yii::t('tasktype', 'Private')] = $private;

                        echo $form->field($app, 'task_type_id')->widget(Select2::classname(), [
                            'data' => $data,
                            'options' => [
                                'placeholder' => Yii::t('tasktype', 'Select app logic ...'),
                                'multiple' => false
                            ],
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
