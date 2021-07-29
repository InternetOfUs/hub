<h2 class="section_title"><?php echo Yii::t('app', 'Tasks'); ?></h2>

<div class="stats_boxes tasks_boxes">
    <div class="box_container big_number_container three_boxes">
        <h3><?php echo Yii::t('app', 'New'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <span class="big_number">
            <?php echo $statsData['new']; ?>
        </span>
        <?php if($statsData['total']){ ?>
            <p class="totals"><?php echo Yii::t('app', 'out of').' '.$statsData['total'].' '.Yii::t('app', 'in total'); ?></p>
        <?php } ?>
    </div>
    <div class="box_container big_number three_boxes">
        <h3><?php echo Yii::t('app', 'Active'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <span class="big_number">
            <?php echo $statsData['active']; ?>
        </span>
    </div>
    <div class="box_container big_number three_boxes">
        <h3><?php echo Yii::t('app', 'Closed'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <span class="big_number">
            <?php echo $statsData['closed']; ?>
        </span>
    </div>
</div>
