<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
  
<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php site_url('monitor/status') ?>" >
 <h5>Redis资源监控>>
 <select name="application_id" class="input-medium" style="" id="application_id" onchange="get_host_by_application()">
  <option value="">选择业务</option>
  <?php foreach ($application  as $item):?>
  <option value="<?php echo $item['id'];?>" <?php if($setval['application_id']==$item['id']) echo "selected"; ?> ><?php echo $item['display_name'] ?>(<?php echo $item['name'] ?>)</option>
   <?php endforeach;?>
  </select>
  <select name="server_id" class="input-medium" style="" >
  <option value="">选择主机</option>
  <?php foreach ($server as $item):?>
  <option value="<?php echo $item['id'];?>" <?php if($setval['server_id']==$item['id']) echo "selected"; ?> ><?php echo $item['host'];?>:<?php echo $item['port'];?></option>
   <?php endforeach;?>
  </select>

  <button type="submit" class="btn btn-success">检索</button>
  <a href="<?php echo site_url('monitor/redis_resource_status') ?>" class="btn btn-warning">重置</a>
  <small>&nbsp;&nbsp;&nbsp;&nbsp;最新检测时间：<?php if(!empty($datalist)){ echo $datalist[0]['create_time'];} else {echo "监控进程未启动或异常";} ?> </small>
 </h5>
</form>
</div>
<br>

<table id="status_table" class="table table-hover table-striped  table-bordered table-condensed dataTable"  >
    <?php if(!empty($datalist)) {?>
    <thead>
    <?php } ?>
    <tr>
        <th colspan="2"><center>服务器</center></th>
        <th colspan="5"><center>内存(M)</center></th>
        <th colspan="4"><center>占用CPU时长(s)</center></th>
        <th colspan="2"><center>信息</center></th>
    </tr>
    <tr>
        <th title="application">业务</th>
        <th title="host:port">主机</th>
 
        <th title="max_memory:Redis最大内存">最大</th>
        <th title="used_memory:由Redis分配器分配的内存总量">已用</th>
        <th title="used_memory_rss:从操作系统的角度，返回Redis已分配的内存总量">系统占用</th>
        <th title="used_memory_peak:Redis的内存消耗峰值">峰值</th>
        <th title="mem_fragmentation_ratio:used_memory_rss/used_memory,较大表示存在内存碎片,较小表示 Redis的部分内存被操作系统换出到交换空间了,操作可能会产生明显的延迟">碎片比率</th>

        <th title="used_cpu_sys:Redis主进程在核心态所占用的CPU时间求和累计">核心态</th>
        <th title="used_cpu_user:Redis主进程在用户态所占用的CPU时间求和累计">用户态</th>
        <th title="used_cpu_sys_children:后台进程在核心态所占用的CPU时间求和累计">子进程核心态</th>
        <th title="used_cpu_user_children:后台进程在用户态所占用的CPU时间求和累计">子进程用户态</th>

        <th title="check time">检测时间</th>
        <th title="trend graph">图表</th>
    </tr>
    <?php if(!empty($datalist)) {?>
    </thead>
    <?php } ?>
	
 <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
    <tr style="font-size: 12px;">
	<td><?php echo $item['application'] ?></td>
        <td><?php echo $item['host'] ?>:<?php echo $item['port'] ?></td>
        <td><?php echo round($item['max_memory']/1024/1024,2) ?></td>
        <td><?php if($item['max_memory']=="0") echo round($item['used_memory']/1024/1024,2);else echo check_data_gt_value(round($item['used_memory']/1024/1024,2),round($item['max_memory']/1024/1024,2)*0.9) ?></td>
        <td><?php echo round($item['used_memory_rss']/1024/1024,2) ?></td>
        <td><?php echo round($item['used_memory_peak']/1024/1024,2) ?></td>
        <td><?php echo $item['mem_fragmentation_ratio'] ?></td>
        <td><?php echo $item['used_cpu_sys'] ?></td>
        <td><?php echo $item['used_cpu_user'] ?></td>
        <td><?php echo $item['used_cpu_sys_children'] ?></td>
        <td><?php echo $item['used_cpu_user_children'] ?></td>
        <td><?php echo $item['create_time'] ?></td>
        <td><a href="<?php echo site_url('chart/resource_status/'.$item['server_id']) ?>" target="_blank"><img src="./img/chart.gif"/></a></a></td>
	</tr>
 <?php endforeach;?>

<?php }else{  ?>
<tr>
<td colspan="12">
<font color="red">对不起,没有查询到相关数据！ 1.请确认是否添加主机信息; 2.请确认是否启动监控进程或执行检测程序。</font>
</td>
</tr>
<?php } ?>	 
</table>


