<?php
    use frontend\models\AppPlatformTelegram;

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
                    echo '<div id="telegram_container">';
                    if( $app->telegramUserAlreadyExists() ){
                        // TODO
                        echo '<button onclick="" class="logoutTelegramBtn" type="button"><span class="icon"></span>'.Yii::t('app', 'Disconnect my account').'</button>';
                    } else {
                        echo '<div id="telegram_widget_container"><script async src="https://telegram.org/js/telegram-widget.js?8" data-telegram-login="'.$telegramPlatform->bot_username.'" data-size="large" data-onauth="onTelegramAuth(user)" data-request-access="write"></script></div>';
                    }
                    echo '</div>';
                }
            ?>
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
        $.post( "/wenetapp/associate-user", data).done(function(response) {
            // console.log( "saved" );
            $('#telegram_widget_container').css('display', 'none');
            $('#telegram_container').append('<button class="logoutTelegramBtn"><span class="icon"></span><?php echo Yii::t('app', 'Disconnect my account'); ?></button>');
        }).fail(function(response) {
            // console.log( "error: " + response.message );
            $('#telegram_widget_container').css('display', 'none');
            $('#telegram_container').append('<p><?php echo Yii::t('app', 'There is a problem with the Telegram login. Please retry later.'); ?></p>');
        });
    }

</script>
