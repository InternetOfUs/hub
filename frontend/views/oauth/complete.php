<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;

    $title = Yii::t('common', 'Process succefully completed');
    if($error_message != false){
        $title = Yii::t('common', 'Error');
    }

    $this->title = Yii::$app->name . ' | ' . $title;
?>

<div class="site-login">
    <div class="app_icon big_icon centered_icon">
        <span><?php echo strtoupper($app->name[0]); ?></span>
    </div>
   <p style="text-align:center; margin-top:10px; font-size:16px;">
       <?php echo $app->name; ?>
   </p>
    <h1 style="text-align:center; display:block;"><?php echo $title; ?></h1>
    <?php
        if($error_message != false){
            echo '<p style="text-align:center;">'.$error_message.'</p>';
        }
    ?>
    <?php
        if($redirect_url != ""){
            echo '<a href="'.$redirect_url.'" class="btn btn-primary" style="margin:20px auto; display:block; width:100px;">'.Yii::t('common', 'close').'</a>';
        }
    ?>
</div>
