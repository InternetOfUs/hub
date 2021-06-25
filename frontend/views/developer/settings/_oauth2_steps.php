<?php
    use yii\helpers\Url;
?>

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
                <pre><?php echo $socialLogin->callback_url . '?code=oauth2_code' ; ?></pre>
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
--data "code=<strong>oauth2_code</strong>"</pre>
            </li>
            <li>
                <?php echo Yii::t('app', 'Upon successful authentication, you can optionally redirect your user to the WeNet OAuth2 complete page'); ?>
                <pre><?php echo Url::home(true) . 'oauth/complete?app_id='. $app->id; ?></pre>
            </li>
            <li>
                <?php echo Yii::t('app', 'Include the token in your requests to the platform in the header:'); ?>
                <pre>--header "Authorization: bearer <strong>&lt;access token&gt;</strong>"</pre>
            </li>
            <li>
                <?php echo Yii::t('app', 'It is possible to request the token details by performing a GET request to:'); ?>
                <pre><?php echo Yii::$app->params['authorisation.api.base.url'] . '/service/token'; ?></pre>
            </li>
            <li>
                <?php echo Yii::t('app', 'The user profile can now be easily requested performing a get request to:'); ?>
                <pre><?php echo Yii::$app->params['authorisation.api.base.url'] . '/service/user/profile'; ?></pre>
            </li>
            <li>
                <?php echo Yii::t('app', 'Upon token expiration, take advantage of the refresh token or redirect the user through the social login flow again to get a new valid token'); ?>
            </li>
        </ol>
    </div>
</div>

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
