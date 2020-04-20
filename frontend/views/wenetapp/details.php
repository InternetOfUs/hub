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
            <!-- TODO -->
            <?php
                $telegramPlatform = $app->getPlatformTelegram();
                if( $telegramPlatform ){
                    if( $app->telegramUserAlreadyExists() ){
                        echo 'sei già registrato!';
                    } else {
                        echo '<script async src="https://telegram.org/js/telegram-widget.js?8" data-telegram-login="'.$telegramPlatform->bot_username.'" data-size="large" data-onauth="onTelegramAuth(user)" data-request-access="write"></script>';
                    }
                } else {
                    echo 'no telegram!';
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
            // TODO remove button, ...
        }).fail(function(response) {
            // console.log( "error: " + response.message );
        });
    }

</script>
