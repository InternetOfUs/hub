<h2 class="section_title"><?php echo Yii::t('app', 'Transactions'); ?></h2>

<div class="stats_boxes transactions_boxes">
    <div class="box_container big_number_container two_boxes">
        <h3><?php echo Yii::t('app', 'New'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <span class="big_number">
            <?php echo $statsData['new']['period']; ?>
        </span>
        <?php if($statsData['new']['total']){ ?>
            <p class="totals"><?php echo Yii::t('app', 'out of').' '.$statsData['new']['total'].' '.Yii::t('app', 'in total'); ?></p>
        <?php } ?>
    </div>
    <div class="box_container graph_container two_boxes">
        <h3><?php echo Yii::t('app', 'Distribution'); ?></h3>
        <p class="helper_text hide"><?php echo Yii::t('app', 'helper'); ?></p>
        <?php echo Yii::$app->controller->renderPartial('stats/_donut_chart_and_data', [
            'data' => $statsData['segmentation'],
            'data_new' => $statsData['new'],
            'target' => 'transaction_distribution',
            'colors' => ['#bada55', '#ffa500', '#7fe5f0', '#f7347a', '#ffd700', '#008080', '#e6e6fa', '#00ced1', '#ac25e2', '#4ca3dd']
        ]); ?>
    </div>
</div>
