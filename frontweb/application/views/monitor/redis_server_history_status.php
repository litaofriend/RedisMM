<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
  
<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php site_url('monitor/redis_server_history_status') ?>" >
 <h5>REDIS历史进程状态>>
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
  <a href="<?php echo site_url('monitor/redis_server_history_status') ?>" class="btn btn-warning">重置</a>
  <small>&nbsp; &nbsp; &nbsp; &nbsp;展示项：连接失败记录+连接成功的记录采样</small>
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
        <th colspan="5"><center>进程状态</center></th>
        <th colspan="3"><center>运行参数</center></th>
        <th colspan="1"><center>信息</center></th>
    </tr>
    <tr>
        <th title="application">业务</th>
        <th title="host:port">主机</th>

        <th title="connect">连接状态</th> 
        <th title="redis_version">版本</th>
        <th title="uptime">运行时长</th> 
        <th title="redis_mode">运行模式</th> 
	<th title="multiplexing_api">事件机制</th>

	<th title="dir">路径</th>
        <th title="config_file">配置文件</th>
	<th title="process_id">进程ID</th>
        <!--th title="run_id:随机标识符,用于sentinel和集群">运行ID</th-->

        <th title="check time">检测时间</th>
    </tr>
    <?php if(!empty($datalist)) {?>
    </thead>
    <?php } ?>

 <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
    <tr style="font-size: 12px;">
	<td><?php echo $item['application'] ?></td>
        <td><?php echo $item['host'] ?>:<?php echo $item['port'] ?></td>
        <td><?php echo check_value($item['connect'])?></td>
        <td><?php echo $item['redis_version']?></td>
        <td><?php echo check_uptime($item['uptime']) ?></td>
        <td><?php echo $item['redis_mode']?></td>
        <td><?php echo $item['multiplexing_api']?></td>
        <td><?php echo $item['dir']?></td>
        <td><?php echo $item['config_file']?></td>
        <td><?php echo $item['process_id']?></td>
        <!--td><?php echo $item['run_id']?></td-->
        <td><?php echo $item['create_time']?></td>
	</tr>
 <?php endforeach;?>

<?php }else{  ?>
<tr>
<td colspan="11">
<font color="red">对不起,没有查询到相关数据！ 可能因为没有连接失败记录或监控时间过短，而没有采样到对应数据。</font>
</td>
</tr>
<?php } ?>	 
</table>
<br>

