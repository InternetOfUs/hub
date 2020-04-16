<?php
    $this->title = Yii::$app->name . ' | ' . $app->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $app->name;
?>

<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <h1><?php echo $app->name; ?></h1>
        <p style="margin:20px 0 0 0;"><?php echo $app->description; ?></p>
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
            <script async src="https://telegram.org/js/telegram-widget.js?8" data-telegram-login="uh_test_bot" data-size="large" data-onauth="onTelegramAuth(user)" data-request-access="write"></script>
            <script type="text/javascript">
            function onTelegramAuth(user) {
                alert('Logged in as ' + user.first_name + ' ' + user.last_name + ' (' + user.id + (user.username ? ', @' + user.username : '') + ')');
            }
            </script>
        </div>
    </div>
</div>
