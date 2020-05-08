<?php
    /* @var $this \yii\web\View */
    /* @var $content string */

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\widgets\Breadcrumbs;
    use frontend\assets\AppAsset;
    use common\widgets\Alert;
    use common\models\User;

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
    <div class="wrap">
        <?php
            $brandUrl = Yii::$app->homeUrl;
            if(!Yii::$app->user->isGuest){
                if(Yii::$app->user->getIdentity()->isDeveloper()){
                    $brandUrl = Url::base().'/developer/index';
                } else {
                    $brandUrl = Url::base().'/wenetapp/index';
                }
            }

            NavBar::begin([
                'brandLabel' => Html::img('@web/images/WeNet_logo.png', ['alt'=>Yii::$app->name]),
                'brandOptions' => ['class' => 'logo'],
                'brandUrl' => $brandUrl,
                'options' => [
                    'class' => 'navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => Yii::t('common', 'Apps'),  'url' => ['/wenetapp/index'], 'visible' => !Yii::$app->user->isGuest],
                ['label' => Yii::t('common', 'Developer'),  'url' => ['/developer/index'], 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->getIdentity()->isDeveloper()]
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => Yii::t('common', 'Log in'),  'url' => ['/user/login']];
                $menuItems[] = ['label' => Yii::t('common', 'Sign up'),  'url' => ['/user/signup'], 'options' => ['class'=> 'menu_btn']];
            } else {
                $menuItems[] = [
                    'label' => Yii::$app->user->identity->username,
                    'items' => [
                        ['label' => Yii::t('common', 'Account & Profile'), 'url' => ['/user/account']],
                        ['label' => Yii::t('common', 'My Apps'), 'url' => ['/user/user-apps']],
                         '<li>' . Html::beginForm(['/user/logout'], 'post') . Html::submitButton(
                             Yii::t('common', 'Logout'), ['class' => 'btn btn-link logout']
                         )
                         . Html::endForm()
                         . '</li>'
                    ],
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?php
                echo Breadcrumbs::widget([
                    'homeLink' => false,
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]);
            ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
