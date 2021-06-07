<?php
    use yii\helpers\Url;
    use frontend\models\TaskType;
    use common\models\User;

    $this->title = Yii::$app->name . ' | ' . $taskType->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps logic'), 'url' => ['tasktype/index']];
    $this->params['breadcrumbs'][] = $taskType->name;
?>

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
        <h1 style="width:100%;">
            <a href="<?= Url::to(['/tasktype/update', 'id' => $taskType->id]); ?>" class="btn btn-primary pull-right" style="margin-top:10px;">
                <i class="fa fa-pencil" aria-hidden="true"></i>
                <?php echo Yii::t('common', 'edit app logic'); ?>
            </a>
            <?php echo $taskType->name; ?>
        </h1>

        <div class="box_container" style="margin-top:30px;">
            <h3><?php echo Yii::t('tasktype', 'Id'); ?></h3>
            <!-- TODO add real explanation -->
            <p><?php echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
            <pre><?php echo $taskType->task_manager_id; ?></pre>
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Attributes'); ?></h3>
            <!-- TODO add real explanation -->
            <p><?php echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
            <pre><code id=attributes></code></pre>
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Transactions'); ?></h3>
            <!-- TODO add real explanation -->
            <p><?php echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
            <pre><code id=transactions></code></pre>
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Callbacks'); ?></h3>
            <!-- TODO add real explanation -->
            <p><?php echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
            <pre><code id=callbacks></code></pre>
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Norms'); ?></h3>
            <!-- TODO add real explanation -->
            <p><?php echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
            <pre><code id=norms></code></pre>
        </div>

	</div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="dx_sidemenu">
            <div class="dx_sidemenu_section">
                <?php if($taskType->public == TaskType::PUBLIC_TASK_TYPE){ ?>
                    <span class="status_icon public"><i class="fa fa-check-circle-o" aria-hidden="true"></i> <?php echo Yii::t('tasktype', 'Public'); ?></span>
                    <div class="alert alert-info" role="alert" style="margin-top:15px;">
                        <?php echo Yii::t('tasktype', 'INFO - This app logic is no more editable or deletable because is public.'); ?>
                    </div>
                <?php } else if($taskType->public == TaskType::PRIVATE_TASK_TYPE){ ?>
                    <a href="<?= Url::to(['/tasktype/public', 'id' => $taskType->id]); ?>" class="btn btn-warning pull-right" style="margin-top:-5px;">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                        <?php echo Yii::t('tasktype', 'make public'); ?>
                    </a>
                    <span class="status_icon private"><i class="fa fa-times-circle-o" aria-hidden="true"></i> <?php echo Yii::t('tasktype', 'Private'); ?></span>
                    <div class="alert alert-info" role="alert" style="margin-top:15px;">
                        <?php echo Yii::t('tasktype', 'INFO - Once you decide to set the app logic as a public one, a copy of the current app logic will be automatically created and you will be no more able to edit or delete it.'); ?>
                    </div>
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

<script type="text/javascript">
    if (!library)
    var library = {};

    library.json = {
    replacer: function(match, pIndent, pKey, pVal, pEnd) {
      var key = '<span class=json-key>';
      var val = '<span class=json-value>';
      var str = '<span class=json-string>';
      var r = pIndent || '';
      if (pKey)
         r = r + key + pKey.replace(/[": ]/g, '') + '</span>: ';
      if (pVal)
         r = r + (pVal[0] == '"' ? str : val) + pVal + '</span>';
      return r + (pEnd || '');
      },
    prettyPrint: function(obj) {
      var jsonLine = /^( *)("[\w]+": )?("[^"]*"|[\w.+-]*)?([,[{])?$/mg;
      return JSON.stringify(obj, null, 3)
         .replace(/&/g, '&amp;').replace(/\\"/g, '&quot;')
         .replace(/</g, '&lt;').replace(/>/g, '&gt;')
         .replace(jsonLine, library.json.replacer);
      }
    };

    $('#attributes').html(library.json.prettyPrint(<?php echo $taskType->attributes; ?>));
    $('#transactions').html(library.json.prettyPrint(<?php echo $taskType->transactions; ?>));
    $('#callbacks').html(library.json.prettyPrint(<?php echo $taskType->callbacks; ?>));
    $('#norms').html(library.json.prettyPrint(<?php echo $taskType->norms; ?>));
</script>

<style media="screen">
    pre {
        background-color: #232b3d;
        border-color: #232b3d;
        color: #fff;
    }
    .json-key {
        color: #cb81ef;
    }
    .json-value {
        color: #ffd454;
    }
    .json-string {
        color: #a1c181;
    }
</style>
