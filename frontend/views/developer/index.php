<?php
    use yii\grid\GridView;
    use yii\grid\ActionColumn;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Developer');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Developer');
?>

<a href="<?= Url::to(['/developer/create']); ?>" class="btn btn-primary pull-right" style="margin: -10px 0 20px 0;">
    <i class="fa fa-plus" aria-hidden="true"></i>
    <?php echo Yii::t('app', 'Create a new app'); ?>
</a>

<?php echo GridView::widget([
    'id' => 'developer_apps_grid',
    'layout' => "{items}\n{summary}\n{pager}",
    'dataProvider' => $provider,
    'columns' => [
        [
            'attribute' => 'status',
            'format' => 'raw',
            'value' => function ($data) {
                if($data->status == WenetApp::STATUS_NOT_ACTIVE){
                    return '<span class="status_icon not_active"><i class="fa fa-pause-circle-o" aria-hidden="true"></i> '.Yii::t('app', 'In development').'</span>';
                } else if($data->status == WenetApp::STATUS_ACTIVE){
                    return '<span class="status_icon active"><i class="fa fa-check-circle-o" aria-hidden="true"></i> '.Yii::t('app', 'Live').'</span>';
                }
            },
        ],
        [
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function ($data) {
                if($data->image_url != null){
                    return '<div class="app_icon_image small_icon" style="background-image: url('.$data->image_url.')"></div>' .
                           '<span>' . $data->name .'</span>';
                } else {
                    return '<div class="app_icon small_icon"><span>' . strtoupper($data->name[0]) .'</span></div>' .
                           '<span>' . $data->name .'</span>';
                }
            },
        ],
        [
            'label' => Yii::t('app', 'Links'),
            'format' => 'raw',
            'value' => function ($data) {
                if($data->hasActiveSourceLinksForApp()){
                    return '<ul class="source_links_list table_view">' . implode(array_map(function($sl){
                        return '<li><img src="'.Url::base().'/images/platforms/'.$sl.'.png" alt="'.Yii::t('app', 'Source link image').'"></li>';
                    }, $data->getActiveSourceLinksForApp()), '') . '</ul>';
                } else {
                    return '<span class="not_set">'.Yii::t('app', 'to be configured').'</span>';
                }
            },
        ],
        [
            'attribute' => 'categories',
            'format' => 'raw',
            'value' => function ($data) {
                return '<ul class="tags_list">' . implode(array_map(function($category){
                    return '<li>' . $category . '</li>';
                }, $data->associatedCategories), '') . '</ul>';
            },
        ],
        [
            'label' => Yii::t('app', 'OAuth2'),
            'format' => 'raw',
            'value' => function ($data) {
                if($data->hasSocialLogin()){
                    return '<i class="fa fa-check" aria-hidden="true"></i>';
                } else {
                    return '<span class="not_set">'.Yii::t('app', 'to be configured').'</span>';
                }
            },
        ],
        [
            'headerOptions' => [
                'class' => 'action-column',
            ],
            'class' => ActionColumn::className(),
            'template' => '{view} {update} {developers} {delete}',
            'visibleButtons' => [
                'developers' => function ($data) {
                    if($data->owner_id == Yii::$app->user->id){
                        return true;
                    } else {
                        return false;
                    }
                },
                'delete' => function ($data) {
                    if($data->owner_id == Yii::$app->user->id){
                        return true;
                    } else {
                        return false;
                    }
                },
            ],
            'buttons'=>[
                'view' => function ($url, $model) {
                    $url = Url::to(['/developer/details', 'id' => $model->id]);
                    return Html::a('<span class="actionColumn_btn"><i class="fa fa-eye"></i></span>', $url, [
                        'title' => Yii::t('common', 'view'),
                    ]);
                },
                'update' => function ($url, $model) {
                    $url = Url::to(['/developer/update', 'id' => $model->id]);
                    return Html::a('<span class="actionColumn_btn"><i class="fa fa-pencil"></i></span>', $url, [
                        'title' => Yii::t('common', 'edit'),
                    ]);
                },
                'developers' => function ($url, $model) {
                    $url = Url::to(['/developer/developers', 'id' => $model->id]);
                    return Html::a('<span class="actionColumn_btn"><i class="fa fa-user"></i></span>', $url, [
                        'title' => Yii::t('common', 'manage developers'),
                    ]);
                },
                'delete' => function ($url, $model) {
                    $url = Url::to(['/developer/delete', 'id' => $model->id]);
                    return Html::a('<span class="actionColumn_btn delete_btn"><i class="fa fa-trash"></i></span>', $url, [
                        'title' => Yii::t('common', 'delete'),
                    ]);
                }
            ]
        ]
    ]
]); ?>
