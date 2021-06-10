<?php
    use yii\helpers\Url;
    use frontend\models\TaskType;
    use common\models\User;
?>

<div class="dx_sidemenu">
    <div class="dx_sidemenu_section">
        <?php if($taskType->public == TaskType::PUBLIC_TASK_TYPE){ ?>
            <span class="status_icon public"><i class="fa fa-check-circle-o" aria-hidden="true"></i> <?php echo Yii::t('tasktype', 'Public'); ?></span>
            <!-- <div class="alert alert-info" role="alert" style="margin-top:15px;">
                <?php // echo Yii::t('tasktype', 'INFO - This app logic is no more editable or deletable because is public.'); ?>
            </div> -->
        <?php } else if($taskType->public == TaskType::PRIVATE_TASK_TYPE){ ?>
            <!-- <a href="<?//= Url::to(['/tasktype/public', 'id' => $taskType->id]); ?>" class="btn btn-warning pull-right" style="margin-top:-5px;">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <?php //echo Yii::t('tasktype', 'make public'); ?>
            </a> -->
            <span class="status_icon private"><i class="fa fa-times-circle-o" aria-hidden="true"></i> <?php echo Yii::t('tasktype', 'Private'); ?></span>
            <!-- <div class="alert alert-info" role="alert" style="margin-top:15px;">
                <?php //echo Yii::t('tasktype', 'INFO - Once you decide to set the app logic as a public one, a copy of the current app logic will be automatically created and you will be no more able to edit or delete it.'); ?>
            </div> -->
        <?php } ?>
    </div>
    <div class="dx_sidemenu_section">
        <h3><?php echo Yii::t('common', 'Details'); ?></h3>
        <p><?php echo $taskType->description; ?></p>
        <ul>
            <li>
                <?php
                    $createdAtDate = new DateTime();
                    $createdAtDate->setTimestamp($taskType->created_at);
                    $createDate = $createdAtDate->format('Y-m-d H:i:s');
                    echo Yii::t('common', 'Create date') . ': ' . $createDate;
                ?>
            </li>
            <li>
                <?php
                    $updatedAtDate = new DateTime();
                    $updatedAtDate->setTimestamp($taskType->updated_at);
                    $updatedDate = $updatedAtDate->format('Y-m-d H:i:s');
                    echo Yii::t('common', 'Update date') . ': ' . $updatedDate;
                ?>
            </li>
        </ul>
        <?php
            if(count($taskType->keywords) > 0){
                $tags = '<ul class="tags_list">';
                foreach ($taskType->keywords as $tag) {
                    $tags .= '<li>'.$tag.'</li>';
                }
                $tags .= '</ul>';
                echo $tags;
            }
        ?>
    </div>
    <?php if($taskType->public == TaskType::PRIVATE_TASK_TYPE){ ?>
        <div class="dx_sidemenu_section">
            <?php if($taskType->creator_id == Yii::$app->user->id){ ?>
                <a href="<?= Url::to(['/tasktype/developers', 'id' => $taskType->id]); ?>" class="btn btn-secondary pull-right" style="margin-top:-5px;">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <?php echo Yii::t('common', 'manage'); ?>
                </a>
            <?php } ?>
            <h3><?php echo Yii::t('common', 'Developers'); ?></h3>
            <?php
                if(count($tasktypeDevelopers) > 0){
                    $developers = '<ul class="developer_list">';
                    foreach ($tasktypeDevelopers as $tasktypeDeveloper) {
                        $dev = User::findIdentity($tasktypeDeveloper->user_id);
                        $developers .= '<li>'.$dev->username.'</li>';
                    }
                    $developers .= '</ul>';
                    echo $developers;
                }
            ?>
        </div>
        <?php if($taskType->creator_id == Yii::$app->user->id){ ?>
            <div class="dx_sidemenu_section">
                <a href="<?= Url::to(['/tasktype/delete', 'id' => $taskType->id]); ?>" class="btn delete_btn pull-right" style="margin-top:-5px;">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                    <?php echo Yii::t('common', 'delete'); ?>
                </a>
                <h3><?php echo Yii::t('common', 'Delete app logic'); ?></h3>
            </div>
        <?php } ?>
    <?php } ?>
</div>
