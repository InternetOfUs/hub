<h2 class="section_title"><?php echo Yii::t('app', 'Users'); ?></h2>

<div class="stats_boxes user_boxes">
    <div class="box_container big_number_container">
        <h3><?php echo Yii::t('app', 'New'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <span class="big_number">
            <?php echo $statsData['new']; ?>
        </span>
        <?php if($statsData['total']){ ?>
            <p class="totals"><?php echo Yii::t('app', 'out of').' '.$statsData['total'].' '.Yii::t('app', 'in total'); ?></p>
        <?php } ?>
    </div>
    <div class="box_container big_number">
        <h3><?php echo Yii::t('app', 'Active'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <span class="big_number">
            <?php echo $statsData['active']; ?>
        </span>
    </div>
    <div class="box_container big_number">
        <h3><?php echo Yii::t('app', 'Engaged'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <span class="big_number">
            <?php echo $statsData['engaged']; ?>
        </span>
    </div>
    <!-- <div class="box_container graph_container">
        <h3><?php //echo Yii::t('app', 'Gender distribution'); ?></h3>
        <p class="helper_text hide"><?php //echo Yii::t('app', 'helper'); ?></p>
    </div>
    <div class="box_container graph_container">
        <h3><?php //echo Yii::t('app', 'Age distribution'); ?></h3>
        <p class="helper_text hide"><?php //echo Yii::t('app', 'helper'); ?></p>
    </div> -->
</div>
