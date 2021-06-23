<?php
    use yii\grid\GridView;
    use yii\grid\ActionColumn;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use frontend\models\TaskType;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Apps logic');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Developer');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Apps logic');
?>

<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <?php echo Yii::$app->controller->renderPartial('../_developer_menu', []); ?>
	</div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <a href="<?= Url::to(['/tasktype/create']); ?>" class="btn btn-primary pull-right" style="margin: -10px 0 20px 0;">
            <i class="fa fa-plus" aria-hidden="true"></i>
            <?php echo Yii::t('tasktype', 'Create a new app logic'); ?>
        </a>

        <?php echo GridView::widget([
            'id' => 'apps_logic_grid',
            'layout' => "{items}\n{summary}\n{pager}",
            'dataProvider' => $provider,
            'columns' => [
                [
                    'attribute' => 'public',
                    'header' => Yii::t('app', 'Status'),
                    'format' => 'raw',
                    'value' => function ($data) {
                        if($data->public == TaskType::PRIVATE_TASK_TYPE){
                            return '<span class="status_icon private"><i class="fa fa-times-circle-o" aria-hidden="true"></i> '.Yii::t('tasktype', 'Private').'</span>';
                        } else if($data->public == TaskType::PUBLIC_TASK_TYPE){
                            return '<span class="status_icon public"><i class="fa fa-check-circle-o" aria-hidden="true"></i> '.Yii::t('tasktype', 'Public').'</span>';
                        }
                    },
                ],
                'name',
                'description',
                [
                    'headerOptions' => [
                        'class' => 'action-column',
                    ],
                    'class' => ActionColumn::className(),
                    'template' => '{view} {update} {developers} {delete}',
                    'visibleButtons' => [
                        'update' => function ($data) {
                            if($data->public == TaskType::PUBLIC_TASK_TYPE){
                                return false;
                            } else {
                                if($data->creator_id == Yii::$app->user->id || $data->isDeveloper()){
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        },
                        'developers' => function ($data) {
                            if($data->public == TaskType::PUBLIC_TASK_TYPE){
                                return false;
                            } else {
                                if($data->creator_id == Yii::$app->user->id){
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        },
                        'delete' => function ($data) {
                            if($data->public == TaskType::PUBLIC_TASK_TYPE){
                                return false;
                            } else {
                                if($data->creator_id == Yii::$app->user->id){
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        },
                    ],
                    'buttons'=>[
                        'view' => function ($url, $model) {
                            $url = Url::to(['/tasktype/details', 'id' => $model->id]);
                            return Html::a('<span class="actionColumn_btn"><i class="fa fa-eye"></i></span>', $url, [
                                'title' => Yii::t('common', 'view'),
                            ]);
                        },
                        'update' => function ($url, $model) {
                            $url = Url::to(['/tasktype/update', 'id' => $model->id]);
                            return Html::a('<span class="actionColumn_btn"><i class="fa fa-pencil"></i></span>', $url, [
                                'title' => Yii::t('common', 'edit'),
                            ]);
                        },
                        'developers' => function ($url, $model) {
                            $url = Url::to(['/tasktype/developers', 'id' => $model->id]);
                            return Html::a('<span class="actionColumn_btn"><i class="fa fa-user"></i></span>', $url, [
                                'title' => Yii::t('common', 'manage developers'),
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            $url = Url::to(['/tasktype/delete', 'id' => $model->id]);
                            return Html::a('<span class="actionColumn_btn delete_btn"><i class="fa fa-trash"></i></span>', $url, [
                                'title' => Yii::t('common', 'delete'),
                            ]);
                        }
                    ]
                ]
            ]
        ]); ?>
    </div>
</div>
