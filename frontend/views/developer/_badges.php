<?php
    use yii\grid\GridView;
    use yii\grid\ActionColumn;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use frontend\models\WenetApp;
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <a href="<?= Url::to(['/badge/create', 'appId' => $app->id]); ?>" class="btn btn-primary pull-right" style="margin: 20px 0;">
            <i class="fa fa-plus" aria-hidden="true"></i>
            <?php echo Yii::t('badge', 'Create a badge'); ?>
        </a>

        <?php
            echo GridView::widget([
                'id' => 'badge_apps_grid',
                'layout' => "{items}\n{summary}\n{pager}",
                'dataProvider' => $appBadges,
                'columns' => [
                    [
                        'attribute' => 'image',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return '<div class="badge_image" style="background-image:url('.$data->image.')";></div>';
                        },
                    ],
                    [
                        'attribute' => 'name',
                        'header' => Yii::t('badge', 'Name (Incentive Server ID)'),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return '<span style="display:block;margin:0 0 5px 0;">'.$data->name.'</span><pre>'.$data->incentive_server_id.'</pre>';
                        },
                    ],
                    'description',
                    [
                        'attribute' => 'label',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return '<pre>'.$data->label.'</pre>';
                        },
                    ],
                    [
                        'attribute' => 'threshold',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return '<span style="display:block;text-align:center;">'.$data->threshold.'</span>';
                        },
                    ],
                    [
                        'headerOptions' => [
                            'class' => 'action-column',
                        ],
                        'class' => ActionColumn::className(),
                        'visible' => $app->status != WenetApp::STATUS_ACTIVE,
                        'template' => '{update} {delete}',
                        'buttons'=>[
                            'update' => function ($url, $model) {
                                $url = Url::to(['/developer/update', 'id' => $model->id]);
                                return Html::a('<span class="actionColumn_btn"><i class="fa fa-pencil"></i></span>', $url, [
                                    'title' => Yii::t('common', 'edit'),
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                $url = Url::to(['/developer/delete', 'id' => $model->id]);
                                return Html::a('<span class="actionColumn_btn delete_btn open_modal"><i class="fa fa-trash"></i></span>', $url, [
                                    'title' => Yii::t('common', 'delete'),
                                ]);
                            }
                        ]
                    ]
                ]
            ]);
        ?>
        <?php
            if($app->status != WenetApp::STATUS_ACTIVE){
                echo Yii::$app->controller->renderPartial('../_delete_modal', ['title' => Yii::t('badge', 'Delete badge')]);
            }
        ?>
    </div>
</div>
