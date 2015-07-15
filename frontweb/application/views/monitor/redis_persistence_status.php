<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
  
<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php site_url('monitor/status') ?>" >
 <h5>REDIS备份监控>>
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
  <a href="<?php echo site_url('monitor/redis_persistence_status') ?>" class="btn btn-warning">重置</a>
  <small>&nbsp;&nbsp;&nbsp;&nbsp;最新检测时间：<?php if(!empty($datalist)){ echo $datalist[0]['create_time'];} else {echo "监控进程未启动或异常";} ?></small>
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
        <th colspan="6"><center>RDB最近一次备份</center></th>
        <th colspan="5"><center>AOF最近一次备份</center></th>
        <th colspan="2"><center>信息</center></th>
    </tr>
    <tr>
        <th title="application">业务</th>
        <th title="host:port">主机</th>

        <th title="rdb_enabled:RDB是否处于打开状态">开启</th> 
        <th title="rdb_dbfilename">文件名</th>
        <th title="rdb_changes_since_last_save:离最近一次成功创建持久化文件之后的变化值">新变化值</th>
        <th title="rdb_last_save_time:最近一次成功创建RDB文件的时间">时间</th>
        <th title="rdb_last_bgsave_status:最近一次创建 RDB文件的结果是成功还是失败">结果</th>
        <th title="rdb_last_bgsave_time_sec:最近一次创建RDB文件耗费的秒数">时长(s)</th>

        <th title="aof_enabled:AOF是否处于打开状态">开启</th>
        <th title="aof_current_size:AOF文件目前的大小">大小(M)</th>
        <th title="aof_last_bgrewrite_status:最近一次重写AOF文件的结果是成功还是失败">重写结果</th>
        <th title="aof_last_write_status:最近一次创建AOF文件的结果是成功还是失败">新建结果</th>
        <th title="aof_last_rewrite_time_sec:最近一次创建AOF文件耗费的时长">时长</th>

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
        <td><?php echo check_status($item['rdb_enabled']) ?></td>
        <td><?php echo $item['rdb_dbfilename'] ?></td>
        <td><?php echo $item['rdb_changes_since_last_save'] ?></td>
        <td><?php echo check_timestamp($item['rdb_last_save_time']) ?></td>
        <td><?php echo check_value($item['rdb_last_bgsave_status']) ?></td>
        <td><?php echo $item['rdb_last_bgsave_time_sec'] ?></td>
        <td><?php echo check_status($item['aof_enabled']) ?></td>
        <td><?php if($item['aof_enabled']!=0) echo round($item['aof_current_size']/1024/1024,2);else echo "---" ?></td>
        <td><?php if($item['aof_enabled']!=0) echo check_value($item['aof_last_bgrewrite_status']);else echo "---" ?></td>
        <td><?php if($item['aof_enabled']!=0) echo check_value($item['aof_last_write_status']);else echo "---" ?></td>
        <td><?php if($item['aof_enabled']!=0) echo $item['aof_last_rewrite_time_sec'];else echo "---" ?></td>
        <td><?php echo $item['create_time'] ?></td>
        <td><a href="<?php echo site_url('chart/persistence_status/'.$item['server_id']) ?>" target="_blank"><img src="./img/chart.gif"/></a></a></td>
	</tr>
 <?php endforeach;?>

<?php }else{  ?>
<tr>
<td colspan="15">
<font color="red">对不起,没有查询到相关数据！ 1.请确认是否添加主机信息; 2.请确认是否启动监控进程或执行检测程序。</font>
</td>
</tr>
<?php } ?>	 
</table>


