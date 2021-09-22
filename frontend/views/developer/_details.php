<?php
    use yii\helpers\Url;

    $showSocialLogin = false;
    if($app->hasSocialLogin()){
        $showSocialLogin = true;
        $socialLogin = $app->getSocialLogin();
    }
?>

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
        <div class="box_container" style="margin-top:30px;">
            <h3><?php echo Yii::t('app', 'App Details'); ?></h3>
            <p><?php echo Yii::t('app', 'To authenticate requests, you will need to include the following parameters in the header of each call:'); ?></p>
            <table class="attribute_container">
                <tr>
                    <td><span>ID:</span></td>
                    <td><pre><?php echo $app->id; ?></pre></td>
                </tr>
            </table>
            <table class="attribute_container">
                <tr>
                    <td><span>secret:</span></td>
                    <td><pre><?php echo $app->token; ?></pre></td>
                </tr>
            </table>
        </div>

        <?php if($community->id != null){ ?>
            <div class="box_container">
                <h3><?php echo Yii::t('app', 'Community'); ?></h3>
                <p><?php echo Yii::t('app', '...'); ?></p>

                <p><?php echo Yii::t('app', 'Community ID'); ?>:</p>
                <pre><?php echo $community->id; ?></pre>
                <br>
                <p><?php echo Yii::t('app', 'Norms'); ?>:</p>
                <pre><code id=norms><?php echo $community->norms; ?></code></pre>
                <hr>
                <a href="<?= Url::to(['/community/update', 'id' => $app->community_id, 'appId' => $app->id]); ?>" style="margin-right:10px;" class="btn btn-primary pull-right" title="<?php echo Yii::t('common', 'edit'); ?>">
                    <i class="fa fa-pencil"></i> <?php echo Yii::t('common', 'edit'); ?>
                </a>
            </div>
        <?php } ?>

    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <?php echo Yii::$app->controller->renderPartial('settings/_side_data', ['app' => $app, 'appDevelopers' => $appDevelopers]); ?>
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

    $('#norms').html(library.json.prettyPrint(<?php echo $community->norms; ?>));
</script>
