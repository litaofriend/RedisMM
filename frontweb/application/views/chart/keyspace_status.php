
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="page-header">
  <h4>键状态统计图表>>&nbsp;
<a href="<?php echo site_url('chart/keyspace_status/'.$cur_server_id.'/60/hour') ?>">&nbsp;&nbsp;1小时&nbsp;</a>
/
<a href="<?php echo site_url('chart/keyspace_status/'.$cur_server_id.'/360/hour') ?>">&nbsp;&nbsp;6小时&nbsp;</a>
/
<a  href="<?php echo site_url('chart/keyspace_status/'.$cur_server_id.'/720/hour') ?>">&nbsp;&nbsp;12小时&nbsp;</a>
/
<a  href="<?php echo site_url('chart/keyspace_status/'.$cur_server_id.'/1440/day') ?>">&nbsp;&nbsp;1天&nbsp;</a>
/
<a  href="<?php echo site_url('chart/keyspace_status/'.$cur_server_id.'/4320/day') ?>">&nbsp;&nbsp;3天&nbsp;</a>
/
<a  href="<?php echo site_url('chart/keyspace_status/'.$cur_server_id.'/10080/day') ?>">&nbsp;&nbsp;1周&nbsp;</a>
  </h4>
</div>
            
<hr/>

<div id="db_keys" style="margin-top:10px; margin-left:0px;margin-right:10px; width:1200px; height:320px;"></div>

<script src="./bootstrap/js/jquery-1.9.0.min.js"></script>
<script src="./js/Highcharts-4.1.7/js/highcharts.js"></script>
<!--script src="./js/Highcharts-4.1.7/js/themes/dark-unica.js"></script-->
<script src="./js/Highcharts-4.1.7/js/modules/exporting.js"></script>

<?php //print_r($chart_result);?>

<script>

$(document).ready(function(){
  var data1=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['db_keys']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data2=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['expires']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data3=[
    <?php if(!empty($chart_result)) { foreach($chart_result as $item){ ?>
    [<?php echo $item['time']?>, <?php echo $item['persists']?> ],
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
  $('#db_keys').highcharts({
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
            text: '键状态  <?php echo $cur_server; ?>'
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
            name: "db_keys",
            //pointInterval:  300 * 1000,
            data: data1 
        }, {
            name: "expires",
            data: data2
        }, {
            name: "persists",
            data: data3
        }]
    });

});
</script>

