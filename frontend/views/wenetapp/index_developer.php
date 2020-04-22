<?php
    use yii\grid\GridView;
    use yii\grid\ActionColumn;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Developer');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Developer');
?>

<a href="<?= Url::to('/wenetapp/create'); ?>" class="btn btn-primary pull-right" style="margin: -10px 0 20px 0;">
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
                if($data->status == WenetApp::STATUS_CREATED){
                    return '<span class="status_icon"><i class="fa fa-pause-circle-o" aria-hidden="true"></i></span>';
                } else if($data->status == WenetApp::STATUS_ACTIVE){
                    return '<span class="status_icon"><i class="fa fa-check-circle-o" aria-hidden="true"></i></span>';
                }
            },
        ],
        'name',
        'metadata', // TODO how to visualize?
        // TODO add platforms
        [
            'headerOptions' => [
                'class' => 'action-column',
            ],
            'class' => ActionColumn::className(),
            'template' => '{view} {update} {delete}',
            'buttons'=>[
                'view' => function ($url, $model) {
                    $url = Url::to(['/wenetapp/details-developer', 'id' => $model->id]);
                    return Html::a('<span class="actionColumn_btn"><i class="fa fa-eye"></i></span>', $url, [
                        'title' => Yii::t('common', 'view'),
                    ]);
                },
                'update' => function ($url, $model) {
                    $url = Url::to(['/wenetapp/update', 'id' => $model->id]);
                    return Html::a('<span class="actionColumn_btn"><i class="fa fa-pencil"></i></span>', $url, [
                        'title' => Yii::t('common', 'edit'),
                    ]);
                },
                'delete' => function ($url, $model) {
                    $url = Url::to(['/wenetapp/delete', 'id' => $model->id]);
                    return Html::a('<span class="actionColumn_btn delete_btn"><i class="fa fa-trash"></i></span>', $url, [
                        'title' => Yii::t('common', 'delete'),
                    ]);
                }
            ]
        ]
    ]
]); ?>
