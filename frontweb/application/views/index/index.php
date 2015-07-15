<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php site_url('index/index') ?>" >
 <h5>Redis总览>>
 <select name="application_id" class="input-medium" style="">
  <option value="">选择业务</option>
  <?php if(!empty($application)) {?>
  <?php foreach ($application  as $item):?>
   <option value="<?php echo $item['id']?>" <?php if($setval['application_id']==$item['id']) echo "selected"; ?> ><?php echo $item['name']?>(<?php echo $item['display_name']?>)</option>
   <?php endforeach;?>
   <?php } ?>
  </select> 
  <button type="submit" class="btn btn-success">检索</button>
  </h5>
</form>                    
</div>

<br>

<table class="table  table-striped  table-bordered table-condensed"  >
	<tr class="info">
        <th><center>版本分布</center></th>
        <th><center>连接状态</center></th>
        <th><center>复制状态</center></th>
	</tr>
    <tr style="font-size: 13px;" class="">
       <td><div id="redis_version" style="margin-top:5px; margin-left:0px; width:420px; height:380px;"></div></td>
       <td><div id="redis_status" style="margin-top:5px; margin-left:0px; width:420px; height:380px;"></div></td>
       <td><div id="redis_replication" style="margin-top:5px; margin-left:0px; width:420px; height:380px;"></div></td>
	</tr>
   
</table>

<div class="row">
  <div class="span6" style="width:40%">
     <table class="table table-bordered table-striped">
             
              <thead>
                <tr class="error">
                  <th ><div class="text-center">名称</div></th>
                  <th colspan="5"><div class="text-center">检测信息</div></th>
                </tr>
              </thead>
              <tbody>
                <tr class="info" style="font-size: 12px;">
                  <td rowspan="2"><p class="text-center">服务器</p></td>
                  <td>服务器数</td>
                  <td>总实例数</td>
                  <td>异常实例</td>
                  <td>主库实例</td>
                  <td>备库实例</td>
                </tr>
                <tr class="warning" style="font-size: 12px;">
                  <td><?php echo $redis_status['all_redis_server']; ?></a></td>
                  <td><?php echo $redis_status['all_redis_instance']; ?></td>
                  <td><?php echo $redis_status['exception_redis_instance']; ?></td>
                  <td><?php echo $redis_status['master_redis_instance']; ?></td>
                  <td><?php echo $redis_status['slave_redis_instance']; ?></td>
                </tr>
                
                <tr class="info" style="font-size: 12px;">
                  <td rowspan="2"><p class="text-center">总连接数</p></td>
                  <td>>100</td>
                  <td>>200</td>
                  <td>>500</td>
                  <td>>1000</td>
                  <td>>5000</td>
                </tr>
                <tr class="warning" style="font-size: 12px;">
                  <td><?php echo $redis_status['redis_connections_100']; ?></td>
                  <td><?php echo $redis_status['redis_connections_200']; ?></td>
                  <td><?php echo $redis_status['redis_connections_500']; ?></td>
                  <td><?php echo $redis_status['redis_connections_1000']; ?></td>
                  <td><?php echo check_data_gt_value($redis_status['redis_connections_5000'],0); ?></td>
                </tr>
                
                <tr class="info" style="font-size: 12px;">
                  <td rowspan="2"><p class="text-center">内存使用率</p></td>
                  <td>>10%</td>
                  <td>>20%</td>
                  <td>>50%</td>
                  <td>>70%</td>
                  <td>>90%</td>
                </tr>
                <tr class="warning" style="font-size: 12px;">
                  <td><?php echo $redis_status['redis_mem_use_ratio_10']; ?></td>
                  <td><?php echo $redis_status['redis_mem_use_ratio_20']; ?></td>
                  <td><?php echo $redis_status['redis_mem_use_ratio_50']; ?></td>
                  <td><?php echo $redis_status['redis_mem_use_ratio_70']; ?></td>
                  <td><?php echo check_data_gt_value($redis_status['redis_mem_use_ratio_90'],0); ?></td>
                </tr>
                
                <tr class="info" style="font-size: 12px;">
                  <td rowspan="2"><p class="text-center">查找成功率</p></td>
                  <td><90%</td>
                  <td><70%</td>
                  <td><50%</td>
                  <td><20%</td>
                  <td><10%</td>
                </tr>
                <tr class="warning" style="font-size: 12px;">
                  <td><?php echo $redis_status['redis_keyspace_hit_rate_90']; ?></td>
                  <td><?php echo $redis_status['redis_keyspace_hit_rate_70']; ?></td>
                  <td><?php echo $redis_status['redis_keyspace_hit_rate_50']; ?></td>
                  <td><?php echo check_data_gt_value($redis_status['redis_keyspace_hit_rate_20'],0); ?></td>
                  <td><?php echo check_data_gt_value($redis_status['redis_keyspace_hit_rate_10'],0); ?></td>
                </tr>
                
                <tr class="info" style="font-size: 12px;">
                  <td rowspan="2"><p class="text-center">命令执行/分</p></td>
                  <td>>100</td>
                  <td>>1000</td>
                  <td>>10000</td>
                  <td>>100000</td>
                  <td>>1000000</td>
                </tr>
                <tr class="warning" style="font-size: 12px;">
                  <td><?php echo $redis_status['redis_total_commands_processed_100']; ?></td>
                  <td><?php echo $redis_status['redis_total_commands_processed_1000']; ?></td>
                  <td><?php echo $redis_status['redis_total_commands_processed_10000']; ?></td>
                  <td><?php echo $redis_status['redis_total_commands_processed_100000']; ?></td>
                  <td><?php echo check_data_gt_value($redis_status['redis_total_commands_processed_1000000'],0); ?></td>
                </tr>
                
              </tbody>
            </table>
  </div>
  <div class="span6" style="width:55%">
   <table class="table table-bordered table-striped">
             
              <thead>
                <tr class="info">
                  <th colspan="6"><div class="text-center" >最新告警信息</div></th>
                </tr>
              </thead>
              <tbody>
                <tr class="info">
   
				  <td>业务</td>
                  <td>主机</td>
                  <td>级别</td>
                  <td>告警内容</td>
                  <td>告警时间</td>
                  <td>告警人</td>
                  <!--td>告警通知</td-->
                  <!--td>发送成功</td-->
                </tr>
<?php if(!empty($last_alarm)) {?>
<?php foreach ($last_alarm  as $item):?>
                <tr class="warning" style="font-size: 12px;">
                  <td><?php echo $item['application'] ?></td>
                  <td><?php echo $item['host'].":".$item['port'] ?></td>
                  <td><?php echo check_alarm_level($item['level']) ?></td>
                  <td><?php echo $item['message']." </br>当前值:".$item['alarm_value']  ?></td>
                  <td><?php echo $item['send_mail_time'] ?></td>
                  <td><?php echo $item['alarm_person'] ?></td>
                  <!--td><?php echo check_status($item['send_mail']) ?></td-->
                  <!-- td><?php echo check_status($item['send_mail_status']) ?></td -->
                </tr>
 <?php endforeach;?>
<?php }else{  ?>
<tr>
<td colspan="6">
<font color="red">当前没有任何报警信息！</font>
</td>
</tr>
<?php } ?>                
              </tbody>
            </table>
  </div>

</div>

  <script src="./bootstrap/js/jquery-1.9.0.min.js"></script>
  <script type="text/javascript" src="./js/jqplot/jquery.jqplot.min.js"></script>
  <script type="text/javascript" src="./js/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
  <script type="text/javascript" src="./js/jqplot/plugins/jqplot.donutRenderer.min.js"></script>
  <link href="./js/jqplot/jquery.jqplot.min.css"  rel="stylesheet">
  

<script>

$(document).ready(function(){
  var data = [
  <?php if(!empty($redis_versions)) { foreach($redis_versions as $item){ ?>
    ["<?php echo $item['versions']?>(<?php echo $item['num']?>)", <?php echo $item['num']?> ],
  <?php }} ?>
  ];
  var plot1 = jQuery.jqplot ('redis_version', [data], 
    { 
      seriesDefaults: {
        // Make this a pie chart.
        renderer: jQuery.jqplot.PieRenderer, 
        rendererOptions: {
          // Put data labels on the pie slices.
          // By default, labels show the percentage of the slice.
          showDataLabels: true
        }
      }, 
      legend: { show:true, location: 'e' }
    }
  );
});

$(document).ready(function(){
  var data = [
    ["连接成功主机(<?php echo $redis_status['normal_redis_instance']; ?>)", <?php echo $redis_status['normal_redis_instance']; ?>],["连接失败主机(<?php echo $redis_status['exception_redis_instance']; ?>)", <?php echo $redis_status['exception_redis_instance'];?> ]
  ];
  var plot1 = jQuery.jqplot ('redis_status', [data], 
    { 
      seriesDefaults: {
        // Make this a pie chart.
        renderer: jQuery.jqplot.PieRenderer, 
        rendererOptions: {
          // Put data labels on the pie slices.
          // By default, labels show the percentage of the slice.
          showDataLabels: true
        }
      }, 
      legend: { show:true, location: 'e' }
    }
  );
});

$(document).ready(function(){
  var data = [
    ["复制正常主机(<?php echo $redis_status['normal_redis_replication']; ?>)", <?php echo $redis_status['normal_redis_replication']; ?> ],["复制异常主机(<?php echo $redis_status['exception_redis_replication']; ?>)", <?php echo $redis_status['exception_redis_replication']; ?> ]
  ];
  var plot1 = jQuery.jqplot ('redis_replication', [data], 
    { 
      seriesDefaults: {
        // Make this a pie chart.
        renderer: jQuery.jqplot.PieRenderer, 
        rendererOptions: {
          // Put data labels on the pie slices.
          // By default, labels show the percentage of the slice.
          showDataLabels: true
        }
      }, 
      legend: { show:true, location: 'e' }
    }
  );
});



	
</script>
	

