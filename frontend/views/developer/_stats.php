<?php
    use yii\helpers\Url;
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="pull-right" style="margin-top:25px;">
            <!-- <span class="btn btn-secondary helpers_btn" style="margin:-20px 10px 0 0;">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <?php //echo Yii::t('app', 'show helpers'); ?>
            </span> -->
            <div class="time_filter">
                <a href="<?php echo Url::to(['/developer/details', 'id' => $app->id, 'filter' => '1d', 'tab' => 'stats']); ?>" id="filter_1" class="<?php echo $filter == '1d' ? 'active' : ''; ?>"><?php echo Yii::t('app', 'yesterday'); ?></a>
                <a href="<?php echo Url::to(['/developer/details', 'id' => $app->id, 'filter' => '7d', 'tab' => 'stats']); ?>" id="filter_7" class="<?php echo $filter == '7d' ? 'active' : ''; ?>"><?php echo Yii::t('app', 'last 7 days'); ?></a>
                <a href="<?php echo Url::to(['/developer/details', 'id' => $app->id, 'filter' => '30d', 'tab' => 'stats']); ?>" id="filter_30" class="<?php echo $filter == '30d' ? 'active' : ''; ?>"><?php echo Yii::t('app', 'last 30 days'); ?></a>
            </div>
        </div>

        <?php // echo Yii::$app->controller->renderPartial('stats/_users', ['statsData' => $statsData['users']]); ?>
        <?php // echo Yii::$app->controller->renderPartial('stats/_messages', ['statsData' => $statsData['messages']]); ?>
        <?php // echo Yii::$app->controller->renderPartial('stats/_tasks', ['statsData' => $statsData['tasks']]); ?>
        <?php // echo Yii::$app->controller->renderPartial('stats/_transactions', ['statsData' => $statsData['transactions']]); ?>

    </div>
</div>

<script type="text/javascript">

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
