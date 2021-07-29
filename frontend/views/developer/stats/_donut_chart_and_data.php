<?php
    use yii\helpers\Json;

    /**
     * Display a box that includes:
     *   - a piechat
     *   - the list of data keys with the associated number and percentage
     *
     *
     * @var array $data An associative having segments as keys and values as counters e.g. ["segment" => 12]
     * @var string $target The target div for the box rendering
     * @var array $colors A list of colors to be used
     * @var bool $hideZeroValues If any keys having value set to 0 should be hidden, default ot false
     * @var bool $forceValueOrdering Make sure that displayed label are ordered by value
     */

    if (!isset($hideZeroValues)) {
        $hideZeroValues = false;
    }

    if (!isset($forceValueOrdering)) {
        $forceValueOrdering = false;
    }

    $pieData = [];
    $labelData = [];
    $number = 0;
    $tot = array_sum($data);

    foreach ($data as $i => $element ) {
        if($number >= count($colors)){ $number = 0; }
        $pieData[] = ['y' => $element, 'color' => $colors[$number]];

        $labelData[] = [
            'number' => $element,
            'perc' => $tot > 0 ? round($element * 100 / $tot, 0) : 0,
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

            if ($forceValueOrdering) {
                usort($labelData, function ($item1, $item2) {
                    return $item2['number'] <=> $item1['number'];
                });
            }

            foreach ($labelData as $element) {
                if (( $element['number'] == 0 && !$hideZeroValues) || $element['number'] > 0) {
                    $content = '<span class="number">';
                        $content .= '<span class="dot" style="background-color:'.$element['color'].';"></span>';
                        $content .= $element['perc'] . '% - ' . $element['number'] . ' <span class="unit"></span>';
                    $content .= '</span>';
                    $content .= '<span>'. $element['label'] .'</span>';
                    $content .= '<br>';
                    echo $content;
                }
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
