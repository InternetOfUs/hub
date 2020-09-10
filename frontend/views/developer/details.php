<?php
    use yii\helpers\Url;
    use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . $app->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer'), 'url' => ['developer/index']];
    $this->params['breadcrumbs'][] = $app->name;

    $showSocialLogin = false;
    if($app->hasSocialLogin()){
        $showSocialLogin = true;
        $socialLogin = $app->getSocialLogin();
    }
?>

<?php if(!$showSocialLogin){ ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="alert alert-warning" role="alert" style="margin-top:-15px;">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo Yii::t('app', 'WARNING - Applications require OAuth2 to go live!'); ?>
            </div>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php if(!$showSocialLogin){ ?>
            <a href="<?= Url::to(['/oauth/create-oauth', 'id' => $app->id]); ?>" class="btn btn-warning pull-right" style="margin:0 0 0 0;">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <?php echo Yii::t('app', 'configure OAuth2'); ?>
            </a>
        <?php } ?>
        <a href="<?= Url::to(['/developer/update', 'id' => $app->id]); ?>" class="btn btn-primary pull-right" style="margin: 0 5px 0 0;">
            <i class="fa fa-pencil" aria-hidden="true"></i>
            <?php echo Yii::t('common', 'edit app'); ?>
        </a>
        <a href="<?= Url::to(['/developer/developers', 'id' => $app->id]); ?>" class="btn btn-secondary pull-right" style="margin: 0 5px 0 0; font-size:12px;">
            <i class="fa fa-user" aria-hidden="true"></i>
            <?php echo Yii::t('common', 'manage developers'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="app_icon big_icon">
            <span><?php echo strtoupper($app->name[0]); ?></span>
        </div>
        <h1>
            <?php echo $app->name; ?>
            <span> | </span>
            <?php
                if($app->status == WenetApp::STATUS_NOT_ACTIVE){
                    echo '<span class="status_icon not_active"><i class="fa fa-pause-circle-o" aria-hidden="true"></i> '.Yii::t('app', 'In development').'</span>';
                } else if($app->status == WenetApp::STATUS_ACTIVE){
                    echo '<span class="status_icon active"><i class="fa fa-check-circle-o" aria-hidden="true"></i> '.Yii::t('app', 'Live').'</span>';
                }
            ?>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <p style="margin:20px 0 0 0;"><?php echo $app->description; ?></p>
        <?php
            if($app->getActiveSourceLinksForApp()){
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
</div>
<div class="row" style="margin-top:20px;">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="box_container">
            <h3><?php echo Yii::t('app', 'App Details'); ?></h3>
            <p><?php echo Yii::t('app', 'To authenticate requests, you will need to include the following parameters in the header of each call:'); ?></p>
            <table class="attribute_container">
                <tr>
                    <td><span>id:</span></td>
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
    </div>
    <?php if($showSocialLogin){ ?>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="box_container">
                <h3><?php echo Yii::t('app', 'OAuth2 Settings'); ?></h3>
                <p>
                    <strong><?php echo Yii::t('app', 'Callback Url'); ?>:</strong>
                    <pre><?php echo $socialLogin->callback_url;?></pre>
                </p>
                <hr>
                <a href="<?= Url::to(['/oauth/delete-oauth', 'id' => $socialLogin->id]); ?>" class="btn delete_btn pull-right" title="<?php echo Yii::t('app', 'Detele OAuth2'); ?>">
                    <i class="fa fa-trash"></i> <?php echo Yii::t('common', 'delete'); ?>
                </a>
                <a href="<?= Url::to(['/oauth/update-oauth', 'id' => $socialLogin->id]); ?>" style="margin-right:10px;" class="btn btn-primary pull-right" title="<?php echo Yii::t('common', 'edit'); ?>">
                    <i class="fa fa-pencil"></i> <?php echo Yii::t('common', 'edit'); ?>
                </a>
            </div>
        </div>
    <?php } ?>
</div>

<?php if($showSocialLogin){ ?>
    <!-- connectors -->
    <?php echo Yii::$app->controller->renderPartial('_connectors', ['app' => $app]); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <hr>
        </div>
    </div>

    <!-- OAuth - guiding steps -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="box_container">
                <h3 class="oauth_info_title">
                    <?php echo Yii::t('app', 'Configuration of the WeNet OAuth2 - guiding steps'); ?>
                    <i class="fa fa-chevron-down" aria-hidden="true" style="float:right;"></i>
                </h3>
                <div class="oauth_info_content">
                    <p><?php echo Yii::t('app', 'In order to complete the integration of WeNet OAuth2 in your application you need to:'); ?></p>
                    <ol>
                        <li>
                            <?php echo Yii::t('app', 'Redirect your users to'); ?>
                            <pre><?php echo Url::home(true) . 'oauth/login?client_id=' . $app->id; ?></pre>
                            <?php echo Yii::t('app', 'You can optionally include two additional parameters: {external_id} allows to specify the 3-rd party user id and {scope} allows to define the subset of permissions that should be requested to the user (among the ones activated in this application OAuth2 settings).', [
                                'external_id' => '<strong>external_id</strong>',
                                'scope' => '<strong>scope</strong>',
                            ]); ?>
                        </li>
                        <li>
                            <?php echo Yii::t('app', 'The {OAuth2_code} will be made available to the defined callback a query param (named {code})', [
                                'OAuth2_code' => '<strong>OAuth2 code</strong>',
                                'code' => '<strong>code</strong>',
                            ]); ?>
                            <pre><?php echo $socialLogin->callback_url . '?code=ouath2_code' ; ?></pre>
                            <?php echo Yii::t('app', 'If the parameter {external_id} has been specified in step 1. it will be made available in this callback.', [
                                'external_id' => '<strong>external_id</strong>',
                            ]); ?>
                        </li>
                        <li>
                            <?php echo Yii::t('app', 'Use the {code} for requesting the user {token} and {refresh_token} (you can complete this step by taking advantage of your preferred OAuth2 client) to link to', [
                                'code' => '<strong>code</strong>',
                                'token' => '<strong>token</strong>',
                                'refresh_token' => '<strong>refresh_token</strong>'
                            ]); ?>
                            <pre><?php echo Yii::$app->params['authorisation.api.base.url'] . '/oauth2/token'; ?></pre>
                            <span><?php echo Yii::t('app', 'Example with your application data'); ?>:</span>
                            <pre>curl -X POST \
      --url "<?php echo Yii::$app->params['authorisation.api.base.url'] . '/oauth2/token'; ?>" \
      --data "grant_type=authorization_code" \
      --data "client_id=<?php echo $app->id; ?>" \
      --data "client_secret=<?php echo $app->token; ?>" \
      --data "code=<strong>ouath2_code</strong>"</pre>
                        </li>
                        <li>
                            <?php echo Yii::t('app', 'Upon successful authentication, you can optionally redirect your user to the WeNet OAuth2 complete page'); ?>
                            <pre><?php echo Url::home(true) . 'oauth/complete'; ?></pre>
                        </li>
                        <li>
                            <?php echo Yii::t('app', 'Include the token in your requests to the platform in the header:'); ?>
                            <pre>--header "Authorization: bearer <strong>&lt;access token&gt;</strong>"</pre>
                        </li>
                        <li>
                            <?php echo Yii::t('app', 'Upon token expiration, take advantage of the refresh token or redirect the user through the social login flow again to get a new valid token'); ?>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">

    $('.oauth_info_title').click(function() {
        if($('.oauth_info_content').hasClass('show')){
            $('.oauth_info_content').removeClass('show');
        } else {
            $('.oauth_info_content').addClass('show');
        }
    });

</script>

<style media="screen">

    div.box_container h3.oauth_info_title{
        margin-bottom: 0px !important;
        cursor: pointer;
    }

    .oauth_info_content{
        overflow: hidden;
        height: 0px;
    }

    .oauth_info_content.show{
        padding-top: 10px;
        height: auto;
    }
</style>
