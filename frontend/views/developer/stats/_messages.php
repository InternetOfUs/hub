<h2 class="section_title"><?php echo Yii::t('app', 'Messages'); ?></h2>

<div class="stats_boxes messages_boxes">
    <div class="box_container big_number_container three_boxes">
        <h3><?php echo Yii::t('app', 'Requests'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'The number of request messages generated. The ones written by the users of the application.'); ?></p>
        <span class="big_number">
            <?php echo $statsData['requests']['period']; ?>
        </span>
        <?php if($statsData['requests']['total']){ ?>
            <p class="totals"><?php echo Yii::t('app', 'out of') . ' '.$statsData['platform']['total'].' ' . Yii::t('app', 'in total'); ?></p>
        <?php } ?>
    </div>
    <div class="box_container big_number_container three_boxes">
        <h3><?php echo Yii::t('app', 'Responses'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'The number of response messages generated. The ones created as response to users\' requests.'); ?></p>
        <span class="big_number">
            <?php echo $statsData['responses']['period']; ?>
        </span>
        <?php if($statsData['responses']['total']){ ?>
            <p class="totals"><?php echo Yii::t('app', 'out of') . ' '.$statsData['app']['total'].' ' . Yii::t('app', 'in total'); ?></p>
        <?php } ?>
    </div>
    <div class="box_container big_number_container three_boxes">
        <h3><?php echo Yii::t('app', 'Notifications'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'The number of notification messages. The ones created for contacting the users without the need for their explicit input.'); ?></p>
        <span class="big_number">
            <?php echo $statsData['notifications']['period']; ?>
        </span>
        <?php if($statsData['notifications']['total']){ ?>
            <p class="totals"><?php echo Yii::t('app', 'out of') . ' '.$statsData['users']['total'].' ' . Yii::t('app', 'in total'); ?></p>
        <?php } ?>
    </div>
    <div class="box_container graph_container two_boxes">
        <h3><?php echo Yii::t('app', 'Distribution'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'The distribution describing the messages generated.'); ?></p>
        <?php echo Yii::$app->controller->renderPartial('stats/_donut_chart_and_data', [
            'data' => $statsData['segmentation'],
            'target' => 'message_distribution',
            'colors' => ['#bada55', '#ffa500', '#7fe5f0', '#f7347a', '#ffd700', '#008080', '#e6e6fa', '#00ced1', '#ac25e2', '#4ca3dd'],
            'hideZeroValues' => false
        ]); ?>
    </div>
</div>
