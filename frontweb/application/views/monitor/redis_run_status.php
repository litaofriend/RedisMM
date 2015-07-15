<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php site_url('monitor/status') ?>" >
 <h5>REDIS性能监控>>
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
  <a href="<?php echo site_url('monitor/redis_run_status') ?>" class="btn btn-warning">重置</a>
  <small>&nbsp; &nbsp; &nbsp; &nbsp;最新检测时间：<?php if(!empty($datalist)){ echo $datalist[0]['create_time'];} else {echo "监控进程未启动或异常";} ?> </small>
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
        <th colspan="5"><center>连接状态</center></th>
        <th colspan="1"><center>命令</center></th>
        <th colspan="2"><center>流量</center></th>
        <th colspan="5"><center>键状态</center></th>
        <th colspan="2"><center>信息</center></th>
    </tr>
    <tr>
        <th title="application">业务</th>
        <th title="host:port">主机</th>
 
        <th title="max_clients:最大客户端的数量">最大</th>
        <th title="connected_clients:已连接客户端的数量">当前</th>
        <th title="blocked_clients:正在等待阻塞命令（BLPOP、BRPOP、BRPOPLPUSH）的客户端的数量">被阻塞</th>
        <th title="total_connections_received:服务器已接受的连接请求数量,计算差值:次/分">已接受/分</th>
        <th title="rejected_connections:因为最大客户端数量限制而被拒绝的连接请求数量,计算差值:次/分">被拒绝/分</th>

        <th title="total_commands_processed:服务器已执行的命令数量,计算差值:次/分">已执行/分</th>

        <th title="total_net_input_bytes:计算差值:K/S">入流量(K/S)</th>
        <th title="total_net_output_bytes:计算差值:K/S">出流量(K/S)</th>

        <th title="expired_keys:因为过期而被自动删除的数据库键数量,计算差值:次/分">删除/分</th>
        <th title="evicted_keys:因为最大内存容量限制而被驱逐（evict）的键数量,计算差值:次/分">驱逐/分</th>
        <th title="keyspace_hits:查找数据库键成功的次数,计算差值:次/分">查找成功/分</th>
        <th title="keyspace_misses:查找数据库键失败的次数,计算差值:次/分">查找失败/分</th>
        <th title="查找数据库键成功的比率,%/分">查找成功率%/分</th>

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
        <td><?php echo $item['max_clients'] ?></td>
        <td><?php echo check_data_gt_value($item['connected_clients'],$item['max_clients']*0.9) ?></td>
        <td><?php echo check_data_gt_value($item['blocked_clients'],0) ?></td>
        <td><?php echo $item['total_connections_received'] ?></td>
        <td><?php echo check_data_gt_value($item['rejected_connections'],0) ?></td>
        <td><?php echo $item['total_commands_processed'] ?></td>
        <td><?php echo $item['total_net_input_bytes'] ?></td>
        <td><?php echo $item['total_net_output_bytes'] ?></td>
        <td><?php echo $item['expired_keys'] ?></td>
        <td><?php echo check_data_gt_value($item['evicted_keys'],0) ?></td>
        <td><?php echo $item['keyspace_hits'] ?></td>
        <td><?php echo $item['keyspace_misses'] ?></td>
        <td><?php echo check_data_lt_value($item['keyspace_hit_rate'],50) ?></td>
        <td><?php echo $item['create_time'] ?></td>
        <td><a href="<?php echo site_url('chart/run_status/'.$item['server_id']) ?>" target="_blank"><img src="./img/chart.gif"/></a></a></td>
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


