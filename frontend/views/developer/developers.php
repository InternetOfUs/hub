<?php
    use yii\grid\GridView;
    use yii\grid\ActionColumn;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use common\models\User;
    use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Manage developers');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer'), 'url' => ['developer/index']];
    $this->params['breadcrumbs'][] = ['label' => $app->name, 'url' => ['developer/details', 'id' => $app->id]];
    $this->params['breadcrumbs'][] = Yii::t('common', 'Manage developers');
?>

<?php echo Yii::$app->controller->renderPartial('_add_developer', ['app' => $app, 'appDeveloper' => $appDeveloper]); ?>

<?php echo GridView::widget([
    'id' => 'developer_apps_grid',
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
                    $app = WenetApp::find()->where(["id" => $data->app_id])->one();
                    $owner = $app->isOwner($data->user_id);
                    if(!$owner){
                        return true;
                    } else {
                        return false;
                    }
                }
            ],
            'buttons'=>[
                'unlink' => function ($url, $model) {
                    $url = Url::to(['/developer/delete-developer', 'app_id' => $model->app_id, 'user_id' => $model->user_id]);
                    return Html::a('<span class="actionColumn_btn"><i class="fa fa-chain-broken"></i></span>', $url, [
                        'title' => Yii::t('common', 'delete developer'),
                    ]);
                }
            ]
        ]
    ]
]); ?>
