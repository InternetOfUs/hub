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

        <?php echo GridView::widget([
            'id' => 'developer_apps_grid',
            'layout' => "{items}\n{summary}\n{pager}",
            'dataProvider' => $appBadges,
            'columns' => [
                'image',
                'name',
                'description',
                'label',
                'threshold',
                // [
                //     'attribute' => 'status',
                //     'format' => 'raw',
                //     'value' => function ($data) {
                //         if($data->status == WenetApp::STATUS_NOT_ACTIVE){
                //             return '<span class="status_icon not_active"><i class="fa fa-pause-circle-o" aria-hidden="true"></i></span>';
                //         } else if($data->status == WenetApp::STATUS_ACTIVE){
                //             return '<span class="status_icon active"><i class="fa fa-check-circle-o" aria-hidden="true"></i></span>';
                //         }
                //     },
                // ],
                // [
                //     'attribute' => 'name',
                //     'format' => 'raw',
                //     'value' => function ($data) {
                //         if($data->image_url != null){
                //             return '<div class="app_icon_image small_icon" style="background-image: url('.$data->image_url.')"></div>' .
                //             '<span>' . $data->name .'</span>';
                //         } else {
                //             return '<div class="app_icon small_icon"><span>' . strtoupper($data->name[0]) .'</span></div>' .
                //             '<span>' . $data->name .'</span>';
                //         }
                //     },
                // ],
                // [
                //     'label' => Yii::t('app', 'Links'),
                //     'format' => 'raw',
                //     'value' => function ($data) {
                //         if($data->hasActiveSourceLinksForApp()){
                //             return '<ul class="source_links_list table_view">' . implode(array_map(function($sl){
                //                 return '<li><img src="'.Url::base().'/images/platforms/'.$sl.'.png" alt="'.Yii::t('app', 'Source link image').'"></li>';
                //             }, $data->getActiveSourceLinksForApp()), '') . '</ul>';
                //         } else {
                //             return '<span class="not_set">'.Yii::t('app', 'to be configured').'</span>';
                //         }
                //     },
                // ],
                // [
                //     'attribute' => 'categories',
                //     'format' => 'raw',
                //     'value' => function ($data) {
                //         return '<ul class="tags_list">' . implode(array_map(function($category){
                //             return '<li>' . $category . '</li>';
                //         }, $data->associatedCategories), '') . '</ul>';
                //     },
                // ],
                // [
                //     'label' => Yii::t('app', 'OAuth2'),
                //     'format' => 'raw',
                //     'value' => function ($data) {
                //         if($data->hasSocialLogin()){
                //             return '<i class="fa fa-check" aria-hidden="true"></i>';
                //         } else {
                //             return '<span class="not_set">'.Yii::t('app', 'to be configured').'</span>';
                //         }
                //     },
                // ],
                [
                    'headerOptions' => [
                        'class' => 'action-column',
                    ],
                    'class' => ActionColumn::className(),
                    'template' => '{update} {delete}',
                    'visibleButtons' => [
                        'update' => function () {
                            if($app->status == WenetApp::STATUS_ACTIVE){
                                return false;
                            } else {
                                return true;
                            }
                        },
                        'delete' => function () {
                            if($app->status == WenetApp::STATUS_ACTIVE){
                                return false;
                            } else {
                                return true;
                            }
                        },
                    ],
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
        ]); ?>
        <?php echo Yii::$app->controller->renderPartial('../_delete_modal', ['title' => Yii::t('badge', 'Delete badge')]); ?>
    </div>
</div>
