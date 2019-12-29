<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('List %s', $this->Admin->getSingularName()))))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => array()));?>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="widget">
                                    <div class="widget-title">
                                        <h4><i class="icon-reorder"></i><?php echo __('Active Users'); ?></h4>
                                    </div>
                                    <div class="widget-body">
                                        chart goes here
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <!-- BEGIN SITE VISITS PORTLET-->
                                <div class="widget">
                                    <div class="widget-title">
                                        <h4><i class="icon-bar-chart"></i><?php echo __('Registration chart'); ?></h4>
                                    </div>
                                    <div class="widget-body">
                                        <div id="site_statistics_loading">
                                            <img src="/img/admin/loading.gif" alt="loading" />
                                        </div>
                                        <div id="site_statistics_content" class="hide">
                                            <div id="site_statistics" class="chart"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END SITE VISITS PORTLET-->
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                        <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <?php echo $this->element('list', array('title'));?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
<script type="text/javascript">

var handleDashboardCharts = function () {

    // used by plot functions
    var data = [];
    var totalPoints = 200;

    // random data generator for plot charts
    function getRandomData() {
        if (data.length > 0) data = data.slice(1);
        // do a random walk
        while (data.length < totalPoints) {
            var prev = data.length > 0 ? data[data.length - 1] : 50;
            var y = prev + Math.random() * 10 - 5;
            if (y < 0) y = 0;
            if (y > 100) y = 100;
            data.push(y);
        }
        // zip the generated y values with the x values
        var res = [];
        for (var i = 0; i < data.length; ++i) res.push([i, data[i]])
        return res;
    }

    if (!jQuery.plot) {
        return;
    }

    function randValue() {
        return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
    }

    var pageviews = [
        [1, randValue()],
        [2, randValue()],
        [3, 2 + randValue()],
        [4, 3 + randValue()],
        [5, 5 + randValue()],
        [6, 10 + randValue()],
        [7, 15 + randValue()],
        [8, 20 + randValue()],
        [9, 25 + randValue()],
        [10, 30 + randValue()],
        [11, 35 + randValue()],
        [12, 25 + randValue()],
        [13, 15 + randValue()],
        [14, 20 + randValue()],
        [15, 45 + randValue()],
        [16, 50 + randValue()],
        [17, 65 + randValue()],
        [18, 70 + randValue()],
        [19, 85 + randValue()],
        [20, 80 + randValue()],
        [21, 75 + randValue()],
        [22, 80 + randValue()],
        [23, 75 + randValue()],
        [24, 70 + randValue()],
        [25, 65 + randValue()],
        [26, 75 + randValue()],
        [27, 80 + randValue()],
        [28, 85 + randValue()],
        [29, 90 + randValue()],
        [30, 95 + randValue()]
    ];
    var visitors = [
        [1, randValue() - 5],
        [2, randValue() - 5],
        [3, randValue() - 5],
        [4, 6 + randValue()],
        [5, 5 + randValue()],
        [6, 20 + randValue()],
        [7, 25 + randValue()],
        [8, 36 + randValue()],
        [9, 26 + randValue()],
        [10, 38 + randValue()],
        [11, 39 + randValue()],
        [12, 50 + randValue()],
        [13, 51 + randValue()],
        [14, 12 + randValue()],
        [15, 13 + randValue()],
        [16, 14 + randValue()],
        [17, 15 + randValue()],
        [18, 15 + randValue()],
        [19, 16 + randValue()],
        [20, 17 + randValue()],
        [21, 18 + randValue()],
        [22, 19 + randValue()],
        [23, 20 + randValue()],
        [24, 21 + randValue()],
        [25, 14 + randValue()],
        [26, 24 + randValue()],
        [27, 25 + randValue()],
        [28, 26 + randValue()],
        [29, 27 + randValue()],
        [30, 31 + randValue()]
    ];

    $('#site_statistics_loading').hide();
    $('#site_statistics_content').show();

    var plot = $.plot($("#site_statistics"), [{
        data: pageviews,
        label: "Unique Visits"
    }, {
        data: visitors,
        label: "Page Views"
    }], {
        series: {
            lines: {
                show: true,
                lineWidth: 2,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.05
                    }, {
                        opacity: 0.01
                    }]
                }
            },
            points: {
                show: true
            },
            shadowSize: 2
        },
        grid: {
            hoverable: true,
            clickable: true,
            tickColor: "#eee",
            borderWidth: 0
        },
        colors: ["#A5D16C", "#FCB322", "#32C2CD"],
        xaxis: {
            ticks: 11,
            tickDecimals: 0
        },
        yaxis: {
            ticks: 11,
            tickDecimals: 0
        }
    });


    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css({
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 15,
            border: '1px solid #333',
            padding: '4px',
            color: '#fff',
            'border-radius': '3px',
            'background-color': '#333',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#site_statistics").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);

                showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });

    //server load
    var options = {
        series: {
            shadowSize: 1
        },
        lines: {
            show: true,
            lineWidth: 0.5,
            fill: true,
            fillColor: {
                colors: [{
                    opacity: 0.1
                }, {
                    opacity: 1
                }]
            }
        },
        yaxis: {
            min: 0,
            max: 100,
            tickFormatter: function (v) {
                return v + "%";
            }
        },
        xaxis: {
            show: false
        },
        colors: ["#A5D16C"],
        grid: {
            tickColor: "#eaeaea",
            borderWidth: 0
        }
    };

    $('#load_statistics_loading').hide();
    $('#load_statistics_content').show();

    var updateInterval = 30;
    var plot = $.plot($("#load_statistics"), [getRandomData()], options);

    function update() {
        plot.setData([getRandomData()]);
        plot.draw();
        setTimeout(update, updateInterval);
    }
    update();
}

    $(document).ready(function(){
        handleDashboardCharts();
    });
</script>