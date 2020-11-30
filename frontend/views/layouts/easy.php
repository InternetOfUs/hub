<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use frontend\assets\AppAsset;
    use common\widgets\Alert;
    AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap&subset=cyrillic,cyrillic-ext,latin-ext,vietnamese" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?php echo Url::base().'/images/favicon.ico'; ?>" />

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="wrap easy_layout">
        <div class="container">
            <div class="site-index">
                <div class="image_container">
                    <?php echo Html::img('@web/images/WeNet_logo.png', ['alt'=>Yii::$app->name]); ?>
                </div>
                <?= Alert::widget() ?>
                <?= $content ?>
                <p style="text-align:center; font-size:11px; padding-bottom:30px;">
                    <?php echo '&copy; ' . Yii::t('common', 'All Rights reserved') . ' | 2019 - ' . date('Y') . ' | v. '. Yii::$app->params['hub.version']; ?>
                </p>
            </div>
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
