
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="page-header">
  <h4>性能状态统计图表>>&nbsp;
<a href="<?php echo site_url('chart/run_status/'.$cur_server_id.'/60/hour') ?>">&nbsp;&nbsp;1小时&nbsp;</a>
/
<a href="<?php echo site_url('chart/run_status/'.$cur_server_id.'/360/hour') ?>">&nbsp;&nbsp;6小时&nbsp;</a>
/
<a  href="<?php echo site_url('chart/run_status/'.$cur_server_id.'/720/hour') ?>">&nbsp;&nbsp;12小时&nbsp;</a>
/
<a  href="<?php echo site_url('chart/run_status/'.$cur_server_id.'/1440/day') ?>">&nbsp;&nbsp;1天&nbsp;</a>
/
<a  href="<?php echo site_url('chart/run_status/'.$cur_server_id.'/4320/day') ?>">&nbsp;&nbsp;3天&nbsp;</a>
/
<a  href="<?php echo site_url('chart/run_status/'.$cur_server_id.'/10080/day') ?>">&nbsp;&nbsp;1周&nbsp;</a>
  </h4>
</div>
            
<hr/>
<div id="connected" style="margin-top:5px; margin-left:0px; width:1200px; height:320px;"></div>
<br>
<div id="total_commands_processed" style="margin-top:5px; margin-left:0px; width:1200px; height:320px;"></div>
<br>
<div id="total_net_bytes" style="margin-top:5px; margin-left:0px; width:1200px; height:320px;"></div>
<br>
<div id="expired_evicted_keys" style="margin-top:5px; margin-left:0px; width:1200px; height:320px;"></div>
<br>
<div id="keyspace_hits_misses" style="margin-top:5px; margin-left:0px; width:1200px; height:320px;"></div>
<br>
<div id="keyspace_hit_rate" style="margin-top:5px; margin-left:0px; width:1200px; height:320px;"></div>

<script src="./bootstrap/js/jquery-1.9.0.min.js"></script>
<script src="./js/Highcharts-4.1.7/js/highcharts.js"></script>
<script src="./js/Highcharts-4.1.7/js/modules/exporting.js"></script>

<script>
$(document).ready(function(){
  var data1=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['connected_clients']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data2=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['blocked_clients']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data3=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['total_connections_received']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data4=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['rejected_connections']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
    Highcharts.setOptions({
        global: {
            useUTC: false //关闭UTC
        }
    });/*
  Highcharts.dateFormat("%H:%M", 10, false);
  */
  $('#connected').highcharts({
        chart: {
            type: 'spline',
            zoomType:'x',
            backgroundColor:'transparent',
            borderWidth:1,
            borderRadius:10,
            plotBackgroundColor:'#EAFDE7',
            plotBorderWidth:1
        },
        title: {
            text: '连接状态  <?php echo $cur_server; ?>'
        },
        /*subtitle: {
            text: 'subtitle-none'
        },*/
        xAxis: {
            type: 'datetime',
            /*   maxPadding : 0.05,
                minPadding : 0.05,
              tickPixelInterval : 150,
                tickWidth:1,//刻度的宽度
                lineColor : '#990000',//自定义刻度颜色
                lineWidth :1,//自定义x轴宽度
            */
            gridLineWidth :1,//默认是0，即在图上没有纵轴间隔线
            
            dateTimeLabelFormats: { // don't display the dummy year
                    second: '%H:%M:%S',
                    minute: '%e. %b %H:%M',
                    hour: '%b/%e %H:%M',
                    day: '%e日/%b',
                    week: '%e. %b',
                    month: '%b %y',
                    year: '%Y'
            }/*,
            //tickInterval: 300 * 1000,
            title: {
                text: 'Time'
            }*/
        },
        yAxis: {
            /*title: {
                text: 'y-none'
            },*/
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            //pointFormat: '{point.x:%H:%M}: {point.y:.2f}'
            pointFormat: '{point.x:%H:%M}: {point.y:.0f}'
        },

        /*plotOptions: {
            line: {
                marker: {
                    enabled: true
                }
            }
        },*/

        series: [{
            name: "connected_clients",
            //pointInterval:  300 * 1000,
            data: data1 
        }, {
            name: "blocked_clients",
            data: data2
        }, {
            name: "total_connections_received",
            data: data3
        }, {
            name: "rejected_connections",
            data: data4
        }]
    });

});

$(document).ready(function(){
  var data1=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['total_commands_processed']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
    Highcharts.setOptions({
        global: {
            useUTC: false //关闭UTC
        }
    });/*
  Highcharts.dateFormat("%H:%M", 10, false);
  */
  $('#total_commands_processed').highcharts({
        chart: {
            type: 'spline',
            zoomType:'x',
            backgroundColor:'transparent',
            borderWidth:1,
            borderRadius:10,
            plotBackgroundColor:'#EAFDE7',
            plotBorderWidth:1
        },
        title: {
            text: '已执行命令数/分 <?php echo $cur_server; ?>'
        },
        /*subtitle: {
            text: 'subtitle-none'
        },*/
        xAxis: {
            type: 'datetime',
            /*   maxPadding : 0.05,
                minPadding : 0.05,
              tickPixelInterval : 150,
                tickWidth:1,//刻度的宽度
                lineColor : '#990000',//自定义刻度颜色
                lineWidth :1,//自定义x轴宽度
            */
            gridLineWidth :1,//默认是0，即在图上没有纵轴间隔线
            
            dateTimeLabelFormats: { // don't display the dummy year
                    second: '%H:%M:%S',
                    minute: '%e. %b %H:%M',
                    hour: '%b/%e %H:%M',
                    day: '%e日/%b',
                    week: '%e. %b',
                    month: '%b %y',
                    year: '%Y'
            }/*,
            //tickInterval: 300 * 1000,
            title: {
                text: 'Time'
            }*/
        },
        yAxis: {
            /*title: {
                text: 'y-none'
            },*/
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            //pointFormat: '{point.x:%H:%M}: {point.y:.2f}'
            pointFormat: '{point.x:%H:%M}: {point.y:.0f}'
        },

        /*plotOptions: {
            line: {
                marker: {
                    enabled: true
                }
            }
        },*/

        series: [{
            name: "total_commands_processed",
            data: data1 
        }]
    });

});

$(document).ready(function(){
  var data1=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['total_net_input_bytes']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data2=[
	    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['total_net_output_bytes']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
    Highcharts.setOptions({
        global: {
            useUTC: false //关闭UTC
        }
    });/*
  Highcharts.dateFormat("%H:%M", 10, false);
  */
  $('#total_net_bytes').highcharts({
        chart: {
            type: 'spline',
            zoomType:'x',
            backgroundColor:'transparent',
            borderWidth:1,
            borderRadius:10,
            plotBackgroundColor:'#EAFDE7',
            plotBorderWidth:1
        },
        title: {
            text: '出入流量  <?php echo $cur_server; ?>'
        },
        /*subtitle: {
            text: 'subtitle-none'
        },*/
        xAxis: {
            type: 'datetime',
            /*   maxPadding : 0.05,
                minPadding : 0.05,
              tickPixelInterval : 150,
                tickWidth:1,//刻度的宽度
                lineColor : '#990000',//自定义刻度颜色
                lineWidth :1,//自定义x轴宽度
            */
            gridLineWidth :1,//默认是0，即在图上没有纵轴间隔线
            
            dateTimeLabelFormats: { // don't display the dummy year
                    second: '%H:%M:%S',
                    minute: '%e. %b %H:%M',
                    hour: '%b/%e %H:%M',
                    day: '%e日/%b',
                    week: '%e. %b',
                    month: '%b %y',
                    year: '%Y'
            }/*,
            //tickInterval: 300 * 1000,
            title: {
                text: 'Time'
            }*/
        },
        yAxis: {
            title: {
                text: '单位：K/S'
            },
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            //pointFormat: '{point.x:%H:%M}: {point.y:.2f}'
            pointFormat: '{point.x:%H:%M}: {point.y:.0f}'
        },

        /*plotOptions: {
            line: {
                marker: {
                    enabled: true
                }
            }
        },*/

        series: [{
            name: "total_net_input_bytes",
            //pointInterval:  300 * 1000,
            data: data1 
        }, {
            name: "total_net_output_bytes",
            data: data2
        }]
    });

});

$(document).ready(function(){
  var data1=[
	    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['expired_keys']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data2=[
	    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['evicted_keys']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
    Highcharts.setOptions({
        global: {
            useUTC: false //关闭UTC
        }
    });/*
  Highcharts.dateFormat("%H:%M", 10, false);
  */
  $('#expired_evicted_keys').highcharts({
        chart: {
            type: 'spline',
            zoomType:'x',
            backgroundColor:'transparent',
            borderWidth:1,
            borderRadius:10,
            plotBackgroundColor:'#EAFDE7',
            plotBorderWidth:1
        },
        title: {
            text: '过期及被驱逐键数量/分  <?php echo $cur_server; ?>'
        },
        /*subtitle: {
            text: 'subtitle-none'
        },*/
        xAxis: {
            type: 'datetime',
            /*   maxPadding : 0.05,
                minPadding : 0.05,
              tickPixelInterval : 150,
                tickWidth:1,//刻度的宽度
                lineColor : '#990000',//自定义刻度颜色
                lineWidth :1,//自定义x轴宽度
            */
            gridLineWidth :1,//默认是0，即在图上没有纵轴间隔线
            
            dateTimeLabelFormats: { // don't display the dummy year
                    second: '%H:%M:%S',
                    minute: '%e. %b %H:%M',
                    hour: '%b/%e %H:%M',
                    day: '%e日/%b',
                    week: '%e. %b',
                    month: '%b %y',
                    year: '%Y'
            }/*,
            //tickInterval: 300 * 1000,
            title: {
                text: 'Time'
            }*/
        },
        yAxis: {
            /*title: {
                text: ''
            },*/
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            //pointFormat: '{point.x:%H:%M}: {point.y:.2f}'
            pointFormat: '{point.x:%H:%M}: {point.y:.0f}'
        },

        /*plotOptions: {
            line: {
                marker: {
                    enabled: true
                }
            }
        },*/

        series: [{
            name: "expired_keys",
            //pointInterval:  300 * 1000,
            data: data1 
        }, {
            name: "evicted_keys",
            data: data2
        }]
    });

});

$(document).ready(function(){
  var data1=[
	    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['keyspace_hits']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data2=[
	    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['keyspace_misses']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
    Highcharts.setOptions({
        global: {
            useUTC: false //关闭UTC
        }
    });/*
  Highcharts.dateFormat("%H:%M", 10, false);
  */
  $('#keyspace_hits_misses').highcharts({
        chart: {
            type: 'spline',
            zoomType:'x',
            backgroundColor:'transparent',
            borderWidth:1,
            borderRadius:10,
            plotBackgroundColor:'#EAFDE7',
            plotBorderWidth:1
        },
        title: {
            text: '查找成功失败次数/分  <?php echo $cur_server; ?>'
        },
        /*subtitle: {
            text: 'subtitle-none'
        },*/
        xAxis: {
            type: 'datetime',
            /*   maxPadding : 0.05,
                minPadding : 0.05,
              tickPixelInterval : 150,
                tickWidth:1,//刻度的宽度
                lineColor : '#990000',//自定义刻度颜色
                lineWidth :1,//自定义x轴宽度
            */
            gridLineWidth :1,//默认是0，即在图上没有纵轴间隔线
            
            dateTimeLabelFormats: { // don't display the dummy year
                    second: '%H:%M:%S',
                    minute: '%e. %b %H:%M',
                    hour: '%b/%e %H:%M',
                    day: '%e日/%b',
                    week: '%e. %b',
                    month: '%b %y',
                    year: '%Y'
            }/*,
            //tickInterval: 300 * 1000,
            title: {
                text: 'Time'
            }*/
        },
        yAxis: {
            /*title: {
                text: ''
            },*/
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            //pointFormat: '{point.x:%H:%M}: {point.y:.2f}'
            pointFormat: '{point.x:%H:%M}: {point.y:.0f}'
        },

        /*plotOptions: {
            line: {
                marker: {
                    enabled: true
                }
            }
        },*/

        series: [{
            name: "keyspace_hits",
            //pointInterval:  300 * 1000,
            data: data1 
        }, {
            name: "keyspace_misses",
            data: data2
        }]
    });
});

$(document).ready(function(){
  var data1=[
	<?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['keyspace_hit_rate']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
    Highcharts.setOptions({
        global: {
            useUTC: false //关闭UTC
        }
    });/*
  Highcharts.dateFormat("%H:%M", 10, false);
  */
  $('#keyspace_hit_rate').highcharts({
        chart: {
            type: 'spline',
            zoomType:'x',
            backgroundColor:'transparent',
            borderWidth:1,
            borderRadius:10,
            plotBackgroundColor:'#EAFDE7',
            plotBorderWidth:1
        },
        title: {
            text: '查找键成功比率%/分 <?php echo $cur_server; ?>'
        },
        /*subtitle: {
            text: 'subtitle-none'
        },*/
        xAxis: {
            type: 'datetime',
            /*   maxPadding : 0.05,
                minPadding : 0.05,
              tickPixelInterval : 150,
                tickWidth:1,//刻度的宽度
                lineColor : '#990000',//自定义刻度颜色
                lineWidth :1,//自定义x轴宽度
            */
            gridLineWidth :1,//默认是0，即在图上没有纵轴间隔线
            
            dateTimeLabelFormats: { // don't display the dummy year
                    second: '%H:%M:%S',
                    minute: '%e. %b %H:%M',
                    hour: '%b/%e %H:%M',
                    day: '%e日/%b',
                    week: '%e. %b',
                    month: '%b %y',
                    year: '%Y'
            }/*,
            //tickInterval: 300 * 1000,
            title: {
                text: 'Time'
            }*/
        },
        yAxis: {
            /*title: {
                text: ''
            },*/
            min: 0
        },
        tooltip: {
            headerFormat: '<b>{series.name}</b><br>',
            //pointFormat: '{point.x:%H:%M}: {point.y:.2f}'
            pointFormat: '{point.x:%H:%M}: {point.y:.0f}'
        },

        /*plotOptions: {
            line: {
                marker: {
                    enabled: true
                }
            }
        },*/

        series: [{
            name: "keyspace_hit_rate",
            //pointInterval:  300 * 1000,
            data: data1 
        }]
    });
});

</script>




