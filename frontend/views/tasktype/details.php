<?php
    use yii\helpers\Url;
    use frontend\models\TaskType;
    use common\models\User;

    $this->title = Yii::$app->name . ' | ' . $taskType->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps logic'), 'url' => ['tasktype/index']];
    $this->params['breadcrumbs'][] = $taskType->name;
?>

<?php if($taskType->public == TaskType::PUBLIC_TASK_TYPE){ ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="alert alert-info" role="alert" style="margin-top:-15px;">
                <?php echo Yii::t('tasktype', 'INFO - This app logic is no more editable or deletable because is public.'); ?>
            </div>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
        <h1><?php echo $taskType->name; ?></h1>

        <div class="box_container" style="margin-top:30px;">
            <h3><?php echo Yii::t('tasktype', 'Attributes'); ?></h3>
            <p><?php echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
<pre class="black_theme">
<?php echo json_encode($taskType->attributes, JSON_PRETTY_PRINT); ?>
</pre>
            <!-- JSON visualiser -->
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Transactions'); ?></h3>
            <p><?php echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
<pre class="black_theme">
<?php echo json_encode($taskType->transactions, JSON_PRETTY_PRINT); ?>
</pre>
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Callbacks'); ?></h3>
            <p><?php echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
<pre class="black_theme">
<?php echo json_encode($taskType->callbacks, JSON_PRETTY_PRINT); ?>
</pre>
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Norms'); ?></h3>
            <p><?php echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
<pre class="black_theme">
<?php echo json_encode($taskType->attributes, JSON_PRETTY_PRINT); ?>
</pre>
        </div>

	</div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="dx_sidemenu">
            <div class="dx_sidemenu_section">
                <?php if($taskType->public == TaskType::PUBLIC_TASK_TYPE){ ?>
                    <span class="status_icon public"><i class="fa fa-check-circle-o" aria-hidden="true"></i> <?php echo Yii::t('tasktype', 'Public'); ?></span>
                <?php } else if($taskType->public == TaskType::PRIVATE_TASK_TYPE){ ?>
                    <a href="<?= Url::to(['/tasktype/update', 'id' => $taskType->id]); ?>" class="btn btn-primary pull-right" style="margin-top:-5px;">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        <?php echo Yii::t('common', 'edit app logic'); ?>
                    </a>
                    <span class="status_icon private"><i class="fa fa-times-circle-o" aria-hidden="true"></i> <?php echo Yii::t('tasktype', 'Private'); ?></span>
                <?php } ?>
            </div>
            <div class="dx_sidemenu_section">
                <!-- TODO add task type description -->
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </div>
            <div class="dx_sidemenu_section">
                <!-- TODO add task type tags only if there are any -->
                <ul class="tags_list">
                    <li>tag1</li>
                    <li>tag2</li>
                    <li>tag3</li>
                </ul>
            </div>
            <?php if($taskType->public == TaskType::PRIVATE_TASK_TYPE){ ?>
                <div class="dx_sidemenu_section">
                    <a href="<?= Url::to(['/tasktype/developers', 'id' => $taskType->id]); ?>" class="btn btn-secondary pull-right" style="margin-top:-5px;">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <?php echo Yii::t('common', 'manage'); ?>
                    </a>
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
                <div class="dx_sidemenu_section">
                    <a href="<?= Url::to(['/tasktype/developers', 'id' => $taskType->id]); ?>" class="btn delete_btn pull-right" style="margin-top:-5px;">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                        <?php echo Yii::t('common', 'delete'); ?>
                    </a>
                    <h3><?php echo Yii::t('common', 'Delete app logic'); ?></h3>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
