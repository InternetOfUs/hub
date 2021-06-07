<?php
    use yii\widgets\Menu;
?>

<div class="menu">
    <?php
        echo Menu::widget([
            'items' => [
                ['label' => Yii::t('common', 'Developer') . ':', 'options'=> ['class'=>'title']],
                ['label' => Yii::t('common', 'My apps'), 'url' => ['/developer/index']],
                ['label' => Yii::t('common', 'Apps logic'), 'url' => ['/tasktype/index']],
            ],
        ]);
    ?>
</div>
