<?php
    use yii\helpers\Url;
    use frontend\models\TaskType;

    $this->title = Yii::$app->name . ' | ' . $taskType->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer')];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps logic'), 'url' => ['tasktype/index']];
    $this->params['breadcrumbs'][] = $taskType->name;
?>

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
        <h1 style="width:100%;">
            <?php if($taskType->public !== TaskType::PUBLIC_TASK_TYPE && ($taskType->creator_id == Yii::$app->user->id || $taskType->isDeveloper())){ ?>
                <a href="<?= Url::to(['/tasktype/update', 'id' => $taskType->id]); ?>" class="btn btn-primary pull-right" style="margin-top:10px;">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                    <?php echo Yii::t('common', 'edit app logic'); ?>
                </a>
            <?php } ?>
            <?php echo $taskType->name; ?>
        </h1>

        <div class="box_container" style="margin-top:30px;">
            <h3><?php echo Yii::t('tasktype', 'Task manager ID'); ?></h3>
            <!-- TODO add real explanation -->
            <!-- <p><?php // echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p> -->
            <pre><?php echo $taskType->task_manager_id; ?></pre>
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Attributes'); ?></h3>
            <!-- TODO add real explanation -->
            <!-- <p><?php // echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p> -->
            <pre><code id=attributes></code></pre>
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Transactions'); ?></h3>
            <!-- TODO add real explanation -->
            <!-- <p><?php // echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p> -->
            <pre><code id=transactions></code></pre>
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Callbacks'); ?></h3>
            <!-- TODO add real explanation -->
            <!-- <p><?php // echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p> -->
            <pre><code id=callbacks></code></pre>
        </div>

        <div class="box_container">
            <h3><?php echo Yii::t('tasktype', 'Norms'); ?></h3>
            <!-- TODO add real explanation -->
            <!-- <p><?php // echo Yii::t('tasktype', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p> -->
            <pre><code id=norms></code></pre>
        </div>

	</div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <?php echo Yii::$app->controller->renderPartial('_side_data', ['taskType' => $taskType, 'tasktypeDevelopers' => $tasktypeDevelopers]); ?>
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
