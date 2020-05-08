<?php
    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Home');;
?>

<div class="site-index">
    <div class="jumbotron">
        <h1>WeNet hub</h1>
        <h2><?php echo Yii::t('index', 'About WeNet Hub'); ?></h2>
        <p><?php echo Yii::t('index', 'hub text'); ?></p>
        <br>
        <h2><?php echo Yii::t('index', 'About WeNet'); ?></h2>
        <p><?php echo Yii::t('index', 'about text'); ?></p>
        <p><?php echo Yii::t('index', 'about text 2'); ?></p>
        <a href="https://www.internetofus.eu/" target="_blank" class="btn"><?php echo Yii::t('index', 'Discover more'); ?></a>
    </div>
</div>
