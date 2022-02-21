<?php
    use yii\helpers\Url;
    use frontend\models\WenetApp;
    use common\models\User;
?>

<div class="dx_sidemenu">
    <div class="dx_sidemenu_section">
        <a href="<?= Url::to(['/developer/update', 'id' => $app->id]); ?>" class="btn btn-primary pull-right" style="margin-top:-5px;">
            <i class="fa fa-pencil" aria-hidden="true"></i>
            <?php echo Yii::t('common', 'edit app'); ?>
        </a>
        <?php
            if($app->status == WenetApp::STATUS_NOT_ACTIVE){
                echo '<span class="status_icon not_active"><i class="fa fa-pause-circle-o" aria-hidden="true"></i> '.Yii::t('app', 'In development').'</span>';
            } else if($app->status == WenetApp::STATUS_ACTIVE){
                echo '<span class="status_icon active"><i class="fa fa-check-circle-o" aria-hidden="true"></i> '.Yii::t('app', 'Live').'</span>';
            }
        ?>
    </div>
    <div class="dx_sidemenu_section">
        <h3><?php echo Yii::t('common', 'Details'); ?></h3>
        <p dir="auto"><?php echo nl2br($app->description); ?></p>
        <ul>
            <li>
                <?php
                    $createdAtDate = new DateTime();
                    $createdAtDate->setTimestamp($app->created_at);
                    $createDate = $createdAtDate->format('Y-m-d H:i:s');
                    echo Yii::t('common', 'Create date') . ': ' . $createDate;
                ?>
            </li>
            <li>
                <?php
                    $updatedAtDate = new DateTime();
                    $updatedAtDate->setTimestamp($app->updated_at);
                    $updatedDate = $updatedAtDate->format('Y-m-d H:i:s');
                    echo Yii::t('common', 'Update date') . ': ' . $updatedDate;
                ?>
            </li>
        </ul>
        <?php
            if($app->hasActiveSourceLinksForApp()){
                $sl = '<ul class="source_links_list">';
                foreach ($app->getActiveSourceLinksForApp() as $sourceLink) {
                    $sl .= '<li><img src="'.Url::base().'/images/platforms/'.$sourceLink.'.png" alt="'.Yii::t('app', 'Source link image').'"></li>';
                }
                $sl .= '</ul>';
                echo $sl;
            }

            if(count($app->associatedCategories) > 0){
                $categories = '<ul class="tags_list">';
                foreach ($app->associatedCategories as $category) {
                    $categories .= '<li>'.$category.'</li>';
                }
                $categories .= '</ul>';
                echo $categories;
            }
        ?>
    </div>
    <div class="dx_sidemenu_section">
        <a href="<?= Url::to(['/developer/developers', 'id' => $app->id]); ?>" class="btn btn-secondary pull-right" style="margin-top:-5px;">
            <i class="fa fa-user" aria-hidden="true"></i>
            <?php echo Yii::t('common', 'manage'); ?>
        </a>
        <h3><?php echo Yii::t('common', 'Developers'); ?></h3>
        <?php
            if(count($appDevelopers) > 0){
                $developers = '<ul class="developer_list">';
                foreach ($appDevelopers as $appDeveloper) {
                    $dev = User::findIdentity($appDeveloper->user_id);

                    $app = WenetApp::find()->where(["id" => $app->id])->one();
                    if($app->isOwner($appDeveloper)){
                        $developers .= '<li><strong>'.$dev->username.'</strong> ('.Yii::t('common', 'owner').')</li>';
                    } else {
                        $developers .= '<li>'.$dev->username.'</li>';
                    }
                }
                $developers .= '</ul>';
                echo $developers;
            }
        ?>
    </div>
    <div class="dx_sidemenu_section">
        <a href="<?= Url::to(['/developer/delete', 'id' => $app->id]); ?>" class="btn delete_btn pull-right" style="margin-top:-5px;">
            <span class="open_modal">
                <i class="fa fa-trash" aria-hidden="true"></i>
                <?php echo Yii::t('common', 'delete'); ?>
            </span>
        </a>
        <h3><?php echo Yii::t('common', 'Delete app'); ?></h3>
        <?php echo Yii::$app->controller->renderPartial('../_delete_modal', ['title' => Yii::t('app', 'Delete app')]); ?>
    </div>
</div>
