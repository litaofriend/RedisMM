<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

  
<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php site_url('monitor/redis_keyspace') ?>" >
 <h5>Redis键状态>>
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
  <a href="<?php echo site_url('monitor/redis_keyspace') ?>" class="btn btn-warning">重置</a>
  <small> &nbsp;&nbsp;&nbsp;&nbsp;最新检测时间：<?php if(!empty($datalist)){ echo $datalist[0]['create_time'];} else {echo "监控进程未启动或异常";} ?> </small>
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
        <th colspan="5"><center>键状态</center></th>
        <th colspan="2"><center>信息</center></th>
    </tr>
    <tr>
        <th title="application">业务</th>
        <th title="host:port">主机</th> 

        <th title="db_name">库</th>
        <th title="db_keys:数据库的键数量">键数量</th>
        <th title="expires:数据库已经被删除的过期键数量">已删除</th>
        <th title="persists:数据库在用键数量">在用</th>
        <th title="avg_ttl:平均生存时间值">生存时长</th>

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
        <td><?php echo $item['db_name'] ?></td>
        <td><?php echo $item['db_keys'] ?></td>
        <td><?php echo $item['expires'] ?></td>
        <td><?php echo $item['persists'] ?></td>
        <td><?php echo check_uptime($item['avg_ttl']/1000) ?></td>
        <td><?php echo $item['create_time'] ?></td>
        <td><a href="<?php echo site_url('chart/keyspace_status/'.$item['server_id']) ?>" target="_blank"><img src="./img/chart.gif"/></a></a></td>
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


