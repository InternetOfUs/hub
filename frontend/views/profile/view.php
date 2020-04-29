<?php
    use yii\helpers\Url;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Profile');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Profile');
?>

<a href="<?= Url::to(['/profile/update']); ?>" class="btn btn-primary pull-right" style="margin: -10px 0 20px 0;">
    <i class="fa fa-pencil" aria-hidden="true"></i>
    <?php echo Yii::t('common', 'edit'); ?>
</a>

dati dell'utente in bellissima view
