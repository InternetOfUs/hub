<?php
    use yii\grid\GridView;
    use yii\grid\ActionColumn;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use common\models\User;
    use frontend\models\TaskType;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Manage developers');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps logic'), 'url' => ['tasktype/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['tasktype/details', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Manage developers');
?>

<?php echo Yii::$app->controller->renderPartial('_add_developer', ['model' => $model, 'tasktypeDeveloper' => $tasktypeDeveloper]); ?>

<?php echo GridView::widget([
    'id' => 'developer_apps_logic_grid',
    'layout' => "{items}\n{summary}\n{pager}",
    'dataProvider' => $provider,
    'columns' => [
        [
            'attribute' => 'username',
            'format' => 'raw',
            'value' => function ($data) {
                return User::findUser($data->user_id)->username;
            },
        ],
        [
            'attribute' => 'email',
            'format' => 'raw',
            'value' => function ($data) {
                return User::findUser($data->user_id)->email;
            },
        ],
        [
            'headerOptions' => [
                'class' => 'action-column',
            ],
            'class' => ActionColumn::className(),
            'template' => '{unlink}',
            'visibleButtons' => [
                'unlink' => function ($data) {
                    $appLogic = TaskType::find()->where(["id" => $data->task_type_id])->one();
                    return $appLogic->isCreator(Yii::$app->user->id) && Yii::$app->user->id != $data->user_id;
                }
            ],
            'buttons'=>[
                'unlink' => function ($url, $model) {
                    $url = Url::to(['/tasktype/delete-developer', 'task_type_id' => $model->task_type_id, 'user_id' => $model->user_id]);
                    return Html::a('<span class="actionColumn_btn"><i class="fa fa-chain-broken"></i></span>', $url, [
                        'title' => Yii::t('common', 'delete developer'),
                    ]);
                }
            ]
        ]
    ]
]); ?>
