<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="pull-right" style="margin-top:25px;">
            <span class="btn btn-secondary helpers_btn" style="margin:-20px 10px 0 0;">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <?php echo Yii::t('app', 'show helpers'); ?>
            </span>
            <div class="time_filter">
                <span id="filter_1"><?php echo Yii::t('app', 'yesterday'); ?></span>
                <span id="filter_7"><?php echo Yii::t('app', 'last 7 days'); ?></span>
                <span id="filter_30" class="active"><?php echo Yii::t('app', 'last 30 days'); ?></span>
            </div>
        </div>

        <?php echo Yii::$app->controller->renderPartial('stats/_users', ['statsData' => $statsData['users']]); ?>

        <?php echo Yii::$app->controller->renderPartial('stats/_messages', ['statsData' => $statsData['messages']]); ?>

        <?php echo Yii::$app->controller->renderPartial('stats/_tasks', ['statsData' => $statsData['tasks']]); ?>

        <?php echo Yii::$app->controller->renderPartial('stats/_transactions', ['statsData' => $statsData['transactions']]); ?>

    </div>
</div>

<script type="text/javascript">

    // time filters
    $('.time_filter span').on('click', function(event) {
        $('.time_filter span').removeClass('active');
        $('#' + event.target.id).addClass('active');
    });

    // show/hide helpers text
    $('.helpers_btn').on('click', function() {
        if($('p.helper_text').hasClass('hide')){
            $('p.helper_text').removeClass('hide');
            $('.helpers_btn').html('<i class="fa fa-eye-slash" aria-hidden="true"></i> <?php echo Yii::t('app', 'hide helpers'); ?>');
        } else {
            $('p.helper_text').addClass('hide');
            $('.helpers_btn').html('<i class="fa fa-eye" aria-hidden="true"></i> <?php echo Yii::t('app', 'show helpers'); ?>');
        }
    });

</script>
