<?php
    use yii\helpers\Json;

    $pieData = [];
    $labelData = [];
    $number = 0;
    foreach ($data['distribution'] as $i => $element ) {
        if($number >= count($colors)){ $number = 0; }
        $pieData[] = ['y' => $element, 'color' => $colors[$number]];

        $labelData[] = [
            'number' => $element,
            'perc' => $data['new'] > 0 ? round($element * 100 / $data['new'], 0) : 0,
            'label' => $i,
            'color' => $colors[$number]
        ];
        $number += 1;
    }

    $containerId = 'pie_' . $target. '_container';
?>

<div class="donut_data_container">
    <div class="chart" id="<?php echo $containerId; ?>"></div>

    <div class="data_container">
        <?php
            foreach ($labelData as $element) {
                $content = '<span class="number">';
                    $content .= '<span class="dot" style="background-color:'.$element['color'].';"></span>';
                    $content .= $element['perc'] . '% - ' . $element['number'] . ' <span class="unit"></span>';
                $content .= '</span>';
                $content .= '<span>'. $element['label'] .'</span>';
                $content .= '<br>';
                echo $content;
            }
        ?>
    </div>
</div>

<script type="text/javascript">
    drawSimplePie(
        <?php echo $containerId; ?>,
        <?php echo Json::encode($pieData); ?>
    );
</script>
