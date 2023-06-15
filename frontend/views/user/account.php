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
                    <?php if($errorGettingUserProfile){
                        echo '<div class="alert-warning alert">'.Yii::t('profile', 'Error upgrading your profile to a developer, try later.').'</div>';
                    } ?>
                    <a class="btn btn-primary" href="<?= Url::to(['/user/account', 'becomeDev' => '1']); ?>">
                        <?php echo Yii::t('app', 'Become a developer'); ?>
                    </a>
                </div>
            <?php } ?>

            <div class="box_container">
                <h3><?php echo Yii::t('profile', 'Do you want to delete your account?'); ?></h3>
                <p><?php echo Yii::t('profile', 'Contact the service desk reporting the following data'); ?>:</p>
                <ul>
                    <li>
                        <?php echo Yii::t('profile', 'User ID'); ?>,
                    </li>
                    <li>
                        <?php echo Yii::t('profile', 'Username'); ?>,
                    </li>
                    <li>
                        <?php echo Yii::t('profile', 'Email'); ?>,
                    </li>
                </ul>
                <p><?php echo Yii::t('profile', 'delete_account_subject'); ?></p>
                <hr>
                <p><?php echo Yii::t('profile', 'delete_account'); ?></p>
                <a class="btn btn-primary" href="mailto:support@internetofus.eu"><?php echo Yii::t('profile', 'Send an email to our service desk'); ?></a>
            </div>

        </div>
	</div>
</div>
