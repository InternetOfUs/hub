<?php
    use yii\helpers\Url;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'Account');
    $this->params['breadcrumbs'][] = Yii::$app->user->identity->username;
    $this->params['breadcrumbs'][] = Yii::t('common', 'Account & Profile');
    $this->params['breadcrumbs'][] = Yii::t('common', 'Account');
?>

<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <?php echo Yii::$app->controller->renderPartial('_menu', []); ?>
	</div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="account_boxes">
            <div class="box_container">
                <h3><?php echo Yii::t('profile', 'Account info'); ?></h3>
                <p>
                    <?php echo Yii::t('profile', 'User ID'); ?>:
                    <strong><?php echo Yii::$app->user->identity->id; ?></strong>
                </p>
                <p>
                    <?php echo Yii::t('profile', 'Username'); ?>:
                    <strong><?php echo Yii::$app->user->identity->username; ?></strong>
                </p>
                <p>
                    <?php echo Yii::t('profile', 'Email'); ?>:
                    <strong><?php echo Yii::$app->user->identity->email; ?></strong>
                </p>
                <?php if(Yii::$app->user->getIdentity()->isDeveloper()) { ?>
                    <p>
                        <?php echo Yii::t('profile', 'Developer'); ?>:
                        <strong><?php echo Yii::t('common', 'Yes'); ?></strong>
                    </p>
                <?php } ?>
            </div>
            <?php if(!Yii::$app->user->getIdentity()->isDeveloper()) { ?>
                <div class="box_container developer_container">
                    <h3><?php echo Yii::t('profile', 'Become a developer'); ?></h3>
                    <p><?php echo Yii::t('profile', 'explanation'); ?></p>
                    <button class="btn btn-primary" onclick="becomeDeveloper()" type="button">
                        <?php echo Yii::t('app', 'Become a developer'); ?>
                    </button>
                </div>
            <?php } ?>
        </div>
	</div>
</div>

<script type="text/javascript">

    function becomeDeveloper() {
        $.post( "<?php echo Url::base(); ?>/user/become-developer", {}).done(function(response) {
            console.log( "saved: " + response.message );
        }).fail(function(response) {
            console.log( 'error: ' + response.message );
            var content = '<p>' + response.message + '<p>';
            $('.developer_container').append(content);
        });
    }

</script>
