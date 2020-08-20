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
