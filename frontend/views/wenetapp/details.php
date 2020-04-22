<?php
    use frontend\models\AppPlatformTelegram;
    use yii\helpers\Url;

    $this->title = Yii::$app->name . ' | ' . $app->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $app->name;
?>

<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <h1><?php echo $app->name; ?></h1>
        <p style="margin:20px 0;"><?php echo $app->description; ?></p>
        <p><strong><?php echo Yii::t('app', 'Creator'); ?>:</strong> <?php echo $app->owner->email; ?></p>
        <?php
            if(count($app->associatedCategories) > 0){
                $categories = '<ul class="tags_list">';
                foreach ($app->associatedCategories as $category) {
                    $categories .= '<li>'.$category.'</li>';
                }
                $categories .= '</ul>';
            }
            echo $categories;
        ?>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="connections">
            <?php
                $telegramPlatform = $app->getPlatformTelegram();

                if( $telegramPlatform ){

                    $loginIsVisible = 'none';
                    $logoutIsVisible = 'none';
                    $openChatIsVisible = 'none';
                    if( $app->telegramUserIsActive() ){
                        $logoutIsVisible = 'block';
                        $openChatIsVisible = 'inline-block';
                    } else {
                        $loginIsVisible = 'block';
                    }
            ?>
                <div id="telegram_container">
                    <h2>Telegram</h2>
                    <!-- login -->
                    <div id="login_telegram" style="display:<?php echo $loginIsVisible; ?>">
                        <script async src="https://telegram.org/js/telegram-widget.js?8"
                            data-telegram-login="<?php echo $telegramPlatform->bot_username; ?>"
                            data-size="large"
                            data-onauth="onTelegramAuth(user)"
                            data-request-access="write">
                        </script>
                    </div>
                    <!-- open chat -->
                    <a id="openChatTelegramBtn" style="display:<?php echo $openChatIsVisible; ?>"
                        href="https://t.me/<?php echo $telegramPlatform->bot_username; ?>" target="_blank">
                        <span class="icon"></span>
                        <?php echo Yii::t('app', 'Open chat'); ?>
                    </a>
                    <!-- logout -->
                    <button id="logoutTelegramBtn" style="display:<?php echo $logoutIsVisible; ?>"
                        onclick="disabletelegram()"
                        type="button">
                        <span class="icon"></span>
                        <?php echo Yii::t('app', 'Disconnect my account'); ?>
                    </button>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript">

    function onTelegramAuth(user) {
        var data = {
            "appId": "<?php echo $app->id; ?>",
            "platform": "telegram",
            "userId": <?php echo Yii::$app->user->id; ?>,
            "platformId": user.id
        };
        $.post( "<?php echo Url::base(); ?>/wenetapp/associate-user", data).done(function(response) {
            // console.log( "saved" );
            $('#login_telegram').css('display', 'none');
            $('#logoutTelegramBtn').css('display', 'block');
            $('#openChatTelegramBtn').css('display', 'inline-block');
        }).fail(function(response) {
            // console.log( "error: " + response.message );
            $('#login_telegram').css('display', 'none');
            $('#logoutTelegramBtn').css('display', 'none');
            $('#openChatTelegramBtn').css('display', 'none');
            var content = '<p>' + response.message + '<p>';
            $('#telegram_container').append(content);
        });
    }

    function disabletelegram() {
        var data = {
            "appId": "<?php echo $app->id; ?>",
            "platform": "telegram",
            "userId": <?php echo Yii::$app->user->id; ?>
        };
        $.post( "<?php echo Url::base(); ?>/wenetapp/disassociate-user", data).done(function(response) {
            // console.log( "saved" );
            $('#login_telegram').css('display', 'block');
            $('#logoutTelegramBtn').css('display', 'none');
            $('#openChatTelegramBtn').css('display', 'none');
        }).fail(function(response) {
            // console.log( "error: " + response.message );
            $('#login_telegram').css('display', 'none');
            $('#logoutTelegramBtn').css('display', 'none');
            $('#openChatTelegramBtn').css('display', 'none');
            var content = '<p>' + response.message + '<p>';
            $('#telegram_container').append(content);
        });
    }

</script>
