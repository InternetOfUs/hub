<?php
    use yii\widgets\Menu;
?>

<div class="menu">
    <?php
        echo Menu::widget([
            'items' => [
                ['label' => Yii::t('common', 'Account & Profile') . ':', 'options'=> ['class'=>'title']],
                ['label' => Yii::t('common', 'Account'), 'url' => ['/user/account']],
                ['label' => Yii::t('common', 'Profile'), 'url' => ['/user/profile']],
                ['label' => Yii::t('common', 'Change password'), 'url' => ['/user/change-password']],
            ],
        ]);
    ?>
</div>
