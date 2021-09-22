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

        <?php if($app->community_id != null){ ?>
            <div class="box_container">
                <h3><?php echo Yii::t('app', 'Community'); ?></h3>
                <p><?php echo Yii::t('app', '...'); ?></p>
                <table class="attribute_container">
                    <tr>
                        <td style="width: 110px;"><span>Community ID:</span></td>
                        <td><pre><?php echo $app->community_id; ?></pre></td>
                    </tr>
                </table>
                <hr>
                <a href="<?= Url::to(['/community/update', 'id' => $app->community_id, 'appId' => $app->id]); ?>" style="margin-right:10px;" class="btn btn-primary pull-right" title="<?php echo Yii::t('common', 'edit'); ?>">
                    <i class="fa fa-pencil"></i> <?php echo Yii::t('common', 'edit'); ?>
                </a>
            </div>
        <?php } ?>

        <div class="box_container">
            <h3><?php echo Yii::t('app', 'OAuth2 Settings'); ?></h3>
            <?php if($showSocialLogin){ ?>
                <p>
                    <strong><?php echo Yii::t('app', 'Callback Url'); ?>:</strong>
                    <pre><?php echo $socialLogin->callback_url;?></pre>
                </p>
                <hr>
                <a href="<?= Url::to(['/oauth/delete-oauth', 'id' => $socialLogin->id]); ?>" class="btn delete_btn pull-right" title="<?php echo Yii::t('app', 'Delete OAuth2'); ?>">
                    <i class="fa fa-trash"></i> <?php echo Yii::t('common', 'delete'); ?>
                </a>
                <a href="<?= Url::to(['/oauth/update-oauth', 'id' => $socialLogin->id]); ?>" style="margin-right:10px;" class="btn btn-primary pull-right" title="<?php echo Yii::t('common', 'edit'); ?>">
                    <i class="fa fa-pencil"></i> <?php echo Yii::t('common', 'edit'); ?>
                </a>
            <?php } else { ?>
                <div class="alert alert-warning" role="alert" style="margin-top:15px;">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo Yii::t('app', 'WARNING - Applications require OAuth2 to go live!'); ?>
                </div>
                <hr>
                <a href="<?= Url::to(['/oauth/create-oauth', 'id' => $app->id]); ?>" class="btn btn-warning pull-right" style="margin:0 0 0 0;">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                    <?php echo Yii::t('app', 'configure OAuth2'); ?>
                </a>
            <?php } ?>
        </div>

        <?php if($showSocialLogin){ ?>
            <?php echo Yii::$app->controller->renderPartial('settings/_connectors', ['app' => $app]); ?>
        <?php } ?>

    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <?php echo Yii::$app->controller->renderPartial('settings/_side_data', ['app' => $app, 'appDevelopers' => $appDevelopers]); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php if($showSocialLogin){ ?>
            <?php echo Yii::$app->controller->renderPartial('settings/_oauth2_steps', ['app' => $app, 'socialLogin' => $socialLogin]); ?>
        <?php } ?>
    </div>
</div>
