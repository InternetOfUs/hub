<?php
    use yii\helpers\Url;

    $showSocialLogin = false;
    if($app->hasSocialLogin()){
        $showSocialLogin = true;
        $socialLogin = $app->getSocialLogin();
    }
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box_container" style="margin-top:30px;">
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
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php if($showSocialLogin){ ?>
            <?php echo Yii::$app->controller->renderPartial('settings/_oauth2_steps', ['app' => $app, 'socialLogin' => $socialLogin]); ?>
        <?php } ?>
    </div>
</div>

<?php if($showSocialLogin){ ?>
    <?php echo Yii::$app->controller->renderPartial('settings/_connectors', ['app' => $app]); ?>
<?php } ?>
