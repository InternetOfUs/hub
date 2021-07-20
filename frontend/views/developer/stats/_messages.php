<h2 class="section_title"><?php echo Yii::t('app', 'Messages'); ?></h2>

<div class="stats_boxes messages_boxes">
    <div class="box_container big_number_container three_boxes">
        <h3><?php echo Yii::t('app', 'From the platform'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <span class="big_number">
            <?php echo $statsData['platform']['period']; ?>
        </span>
        <?php if($statsData['platform']['total']){ ?>
            <p class="totals"><?php echo Yii::t('app', 'out of') . ' '.$statsData['platform']['total'].' ' . Yii::t('app', 'in total'); ?></p>
        <?php } ?>
    </div>
    <div class="box_container big_number_container three_boxes">
        <h3><?php echo Yii::t('app', 'From the app'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <span class="big_number">
            <?php echo $statsData['app']['period']; ?>
        </span>
        <?php if($statsData['app']['total']){ ?>
            <p class="totals"><?php echo Yii::t('app', 'out of') . ' '.$statsData['app']['total'].' ' . Yii::t('app', 'in total'); ?></p>
        <?php } ?>
    </div>
    <div class="box_container big_number_container three_boxes">
        <h3><?php echo Yii::t('app', 'From the users'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <span class="big_number">
            <?php echo $statsData['users']['period']; ?>
        </span>
        <?php if($statsData['users']['total']){ ?>
            <p class="totals"><?php echo Yii::t('app', 'out of') . ' '.$statsData['users']['total'].' ' . Yii::t('app', 'in total'); ?></p>
        <?php } ?>
    </div>
</div>
