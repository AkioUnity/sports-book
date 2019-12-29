<?php
$placeholderClass = isset($placeholderClass) ? $placeholderClass : null;
$chartsData = isset($chartsData) ? $chartsData : array();
$chartsCount = count($chartsData);
?>

<?php foreach($chartsData AS $chartIndex => $chartData): ?>
    <div class="chart-data-<?php echo $chartData['settings']['id']; ?>">
        <?php if(isset($showTitle) AND $showTitle == false): ?>
        <span style="position: absolute;" class="chart-title-<?php echo $chartData['settings']['id']; ?>"><?php echo $chartData['settings']['title'] ?></span>
        <?php endif; ?>
        <div class="<?php echo $placeholderClass; ?>">
            <div id="chart<?php echo $chartData['settings']['id']; ?>" class="chart"></div>
        </div>
    </div>
<?php endforeach; ?>

<script type="text/javascript">
    $(function () {
        <?php foreach($chartsData AS $chartIndex => $chartData): ?>
        <?php $chartIndex = $chartData['settings']['id']; ?>
        <?php $i = 0; ?>
            var chartData<?php echo $chartIndex; ?> = [];
            <?php foreach($chartData['data'] AS $index => $chartStats): ?>
                chartData<?php echo $chartIndex; ?>[<?php echo $i++; ?>] = {
                    color   : <?php if(isset($chartStats['color'])):?>"<?php echo $chartStats['color']; ?>"<?php else: ?>null<?php endif; ?>,
                    label   : "<?php echo $chartStats['label']; ?>",
                    data    : <?php if(!isset($chartStats['count']) OR $chartStats['count'] == null): ?>0<?php else: ?><?php echo $chartStats['count']; ?><?php endif; ?>
                };
            <?php endforeach; ?>

        $.plot($("#chart<?php echo $chartIndex; ?>"), chartData<?php echo $chartIndex; ?>,
            {
                series: {
                    pie: {
                        show: true,
                        radius: <?php if(isset($chartData['settings']['radius'])):?>"<?php echo $chartData['settings']['radius']; ?>"<?php else: ?>1<?php endif; ?>,
                        label: {
                            show: true,
                            radius: <?php if(isset($chartData['settings']['label_radius'])):?>"<?php echo $chartData['settings']['label_radius']; ?>"<?php else: ?>1<?php endif; ?>,
                            formatter: function(label, series){
                                return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;font-weight: bold;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
                            },
                            background: { opacity: 0.8 }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        <?php endforeach; ?>
    });
</script>
<style type="text/css">
    .chart-title { font-weight: bold; }
</style>