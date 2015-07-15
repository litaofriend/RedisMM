<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span class="ui-icon ui-icon-search" style="float: left; margin-right: .3em;"></span>
                    
<form name="form" class="form-inline" method="get" action="<?php site_url('monitor/replication') ?>" >
  <input type="hidden" name="search" value="submit" />
  <h5>Redis主从监控>>
  <select name="application_id" id="application_id" class="input-medium" style="" onchange="get_host_by_application()">
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
  <select name="role" class="input-small" >
  <option value="">角色</option>
  <option value="is_master" <?php if($setval['role']=='is_master') echo "selected"; ?> >主库</option>
  <option value="is_slave" <?php if($setval['role']=='is_slave') echo "selected"; ?> >备库</option>
  </select>
  <button type="submit" class="btn btn-success">检索</button>
  <a href="<?php echo site_url('monitor/redis_replication') ?>" class="btn btn-warning">重置</a>
  <small>&nbsp;&nbsp;&nbsp;&nbsp;最新检测时间：<?php if(!empty($datalist)){ echo $datalist[0]['create_time'];} else {echo "监控进程未启动或异常";} ?> </small>
  </h5>
</form>
</div>
<br>

<div class="table-responsive">
<table class="table table-hover table-striped  table-bordered table-condensed"  >
    <tr>
	<th colspan="5"><center>服务器</center></th>
	<th colspan="2"><center>复制线程</center></th>
        <th colspan="4"><center>主库信息</center></th>
	<th colspan="2"><center>偏移量</center></th>
    </tr>
    <tr>
        <th title="host:port">主机</th>
        <th title="role">角色</th>
        <th title="slave_read_only">只读</th>
        <th title="application">业务</th>
        <th title="slave_priority">slave优先级</th>

	<th title="master_link_status">连接状态</th>
        <th title="delay:master_repl_offset-slave_repl_offset 主备同步延迟">延迟</th>

        <th title="master_host:master_port">主机:端口</th>
        <th title="connected_slaves">slave数量</th>
        <th title="master_last_io_seconds_ago:距离最近一次和master交互的时长">距近一次交互时长</th>
        <th title="master_link_down_since_seconds">master连接失败时长</th>

	<th title="master_repl_offset">master偏移</th>
        <th title="slave_repl_offset">slave偏移</th>
	</tr>
	
 <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
    <tr style="font-size: 12px;">
        <td><?php  echo $item['host'].':'. $item['port'] ?></td>
        <td><?php echo check_role($item['is_master'],$item['is_slave']) ?></td>
        <td><?php echo check_status($item['slave_read_only']) ?></td>
        <td><?php echo $item['application'] ?></td>
        <td><?php echo $item['slave_priority'] ?></td>
        <td><?php echo check_value($item['master_link_status']) ?></td>
	<!--td><?php if($item['is_slave']=='1'){?><a href="<?php echo site_url('chart/replication_status/'.$item['server_id']) ?>" target="_blank"><img src="./img/chart.gif"/></a><?php } ?>&nbsp;<?php if($item['is_slave']!='1') echo "---";else if($item['delay']>0) echo check_data_gt_value($item['delay'],0);else echo 0 ?>  </td-->
	<td>&nbsp;<?php if($item['is_slave']!='1') echo "---";else if($item['delay']>0) echo check_data_gt_value($item['delay'],0);else echo 0 ?>  </td>
        <td><?php echo check_value($item['master_host']) ?>:<?php echo check_value($item['master_port']) ?></td>
        <td><?php echo $item['connected_slaves'] ?></td>
        <td><?php echo $item['master_last_io_seconds_ago'] ?></td>
        <td><?php echo check_data_gt_value($item['master_link_down_since_seconds'],0) ?></td>
        <td><?php echo $item['master_repl_offset'] ?></td>
        <td><?php echo $item['slave_repl_offset'] ?></td>
        
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
</div>

<br>
<small>注：只有完整的master/slave结构才会显示出来</small>

<div class="pagination">
  <ul>
        <?php echo $this->pagination->create_links(); ?>
  </ul>
</div>

