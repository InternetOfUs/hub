<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    $message = '<p style="text-align:center; margin-top:10px; font-size:18px;">'. Yii::t('authorisation', 'Congratulations! You successfully activated the {app_name} application.', ['app_name' => '<strong>'.$app->name.'</strong>']).'</p>';
    $title = Yii::t('common', 'Process succefully completed');
    if(isset($error_message)){
        $title = Yii::t('common', 'Error');
        if($error_message == ""){
            $message = '<p style="text-align:center; margin-top:10px; font-size:18px;">'. Yii::t('authorisation', 'Ooops! Something went wrong while activating the {app_name} application.', ['app_name' => '<strong>'.$app->name.'</strong>']).'</p>';
        } else {
            $message = '<p style="text-align:center; margin-top:10px; font-size:18px;">'.$error_message.'</p>';
        }
    }

    $this->title = Yii::$app->name . ' | ' . $title;
?>

<div class="site-login">
    <?php if($app->image_url != null){ ?>
        <div class="app_icon_image big_icon centered_icon" style="background-image: url(<?php echo $app->image_url; ?>)"></div>
    <?php } else { ?>
        <div class="app_icon big_icon centered_icon">
            <span><?php echo strtoupper($app->name[0]); ?></span>
        </div>
    <?php } ?>
    
    <?php echo $message; ?>
    <?php
        if($redirect_url != ""){
            echo '<a href="'.$redirect_url.'" class="btn btn-primary" style="margin:20px auto; display:block; width:100px;">'.Yii::t('common', 'close').'</a>';
        }
    ?>
</div>
