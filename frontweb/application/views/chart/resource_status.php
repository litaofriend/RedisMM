
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="page-header">
  <h4>资源状态统计图表>>&nbsp;
<a href="<?php echo site_url('chart/resource_status/'.$cur_server_id.'/60/hour') ?>">&nbsp;&nbsp;1小时&nbsp;</a>
/
<a href="<?php echo site_url('chart/resource_status/'.$cur_server_id.'/360/hour') ?>">&nbsp;&nbsp;6小时&nbsp;</a>
/
<a  href="<?php echo site_url('chart/resource_status/'.$cur_server_id.'/720/hour') ?>">&nbsp;&nbsp;12小时&nbsp;</a>
/
<a  href="<?php echo site_url('chart/resource_status/'.$cur_server_id.'/1440/day') ?>">&nbsp;&nbsp;1天&nbsp;</a>
/
<a  href="<?php echo site_url('chart/resource_status/'.$cur_server_id.'/4320/day') ?>">&nbsp;&nbsp;3天&nbsp;</a>
/
<a  href="<?php echo site_url('chart/resource_status/'.$cur_server_id.'/10080/day') ?>">&nbsp;&nbsp;1周&nbsp;</a>
  </h4>
</div>

<hr/>

<div id="used_memory" style="margin-top:5px; margin-left:0px; width:1200px; height:320px;"></div>
<br>
<div id="mem_fragmentation_ratio" style="margin-top:5px; margin-left:0px; width:1200px; height:320px;"></div>
<br>
<div id="used_cpu" style="margin-top:5px; margin-left:0px; width:1200px; height:320px;"></div>

<script src="./bootstrap/js/jquery-1.9.0.min.js"></script>
<script src="./js/Highcharts-4.1.7/js/highcharts.js"></script>
<script src="./js/Highcharts-4.1.7/js/modules/exporting.js"></script>

<script>
$(document).ready(function(){
  var data1=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['used_memory']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data2=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['used_memory_rss']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data3=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['used_memory_peak']?> ],
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
  $('#used_memory').highcharts({
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
            text: '内存使用状态 <?php echo $cur_server; ?>'
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
                text: '单位：M'
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
            name: "used_memory",
            //pointInterval:  300 * 1000,
            data: data1 
        }, {
            name: "used_memory_rss",
            data: data2
        }, {
            name: "used_memory_peak",
            data: data3
        }]
    });

});

$(document).ready(function(){
  var data1=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['mem_fragmentation_ratio']?> ],
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
  $('#mem_fragmentation_ratio').highcharts({
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
            text: '内存碎片率(used_memory_rss/used_memory) <?php echo $cur_server; ?>'
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
            name: "mem_fragmentation_ratio",
            //pointInterval:  300 * 1000,
            data: data1 
        }]
    });

});

$(document).ready(function(){
  var data1=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['used_cpu_sys']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data2=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['used_cpu_user']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data3=[
	    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['used_cpu_sys_children']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data4=[
	    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['used_cpu_user_children']?> ],
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
  $('#used_cpu').highcharts({
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
            text: '内存使用状态 <?php echo $cur_server; ?>'
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
                text: '单位：秒'
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
            name: "used_cpu_sys",
            //pointInterval:  300 * 1000,
            data: data1 
        }, {
            name: "used_cpu_user",
            data: data2
        }, {
            name: "used_cpu_sys_children",
            data: data3
        }, {
            name: "used_cpu_user_children",
            data: data4
        }]
    });

});

</script>



