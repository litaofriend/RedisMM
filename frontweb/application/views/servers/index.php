<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="page-header">
  <h4>管理主机>>&nbsp;<a href="<?php echo site_url('servers/add') ?>">&nbsp;&nbsp;新增&nbsp;</a>/<a href="<?php echo site_url('servers/index') ?>">&nbsp;&nbsp;列表&nbsp;</a>/<a  href="<?php echo site_url('servers/trash') ?>">&nbsp;&nbsp;回收站&nbsp;</a></h4>
</div>
  
<hr/>

<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php site_url('server/index') ?>" >
 <select name="application_id" class="input-medium" style="">
  <option value="">选择业务</option>
  <?php foreach ($application  as $item):?>
  <option value="<?php echo $item['id'];?>" <?php if($setval['application_id']==$item['id']) echo "selected"; ?> ><?php echo $item['display_name'] ?>(<?php echo $item['name'] ?>)</option>
   <?php endforeach;?>
  </select>
  主机IP <input class="" style="width:150px;" type="text" name="host" id="host" value="<?php echo $setval['host']; ?>" placeholder="IP地址" />
  
  <button type="submit" class="btn btn-success">检索</button>
  <a href="<?php echo site_url('servers/index') ?>" class="btn btn-warning">重置</a>


</form>
                    
</div>

<table class="table table-hover table-striped  table-bordered table-condensed">
    <tr>
	<th colspan="3"><center>服务器</center></th>
        <th colspan="3"><center>监控开关</center></th>
	<th colspan="3"><center>告警项目</center></th>
	<th colspan="3"><center>告警阀值</center></th>
        <th rowspan="2"><center>管理</center></th>
    </tr>
    <tr>
        <th>主机ID</th>
        <th>主机</th>
        <th>业务</th>

	<th>监控</th>
	<th>告警</th>
        <th>复制状态</th>

        <th>总连接数</th>
        <th>内存使用</th>
        <th>复制延迟</th>
        <!--th>慢日志</th>
        <th>错误日志</th-->

        <th>连接数使用率</th>
        <th>内存使用率</th>
        <th>复制延迟</th>
        <!--th>慢日志数</th>
        <th>错误日志数</th-->
    </tr>
	

 <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
    <tr style="font-size: 13px;">
        <td><strong><?php echo $item['id'] ?></strong></td>
	<td><strong><?php echo $item['host'] ?>:<?php echo $item['port'] ?></strong></td>
        <td><?php echo $item['display_name'] ?>(<?php echo $item['name'] ?>)</td>

        <td><?php echo check_on_off($item['status']) ?></td>
        <td><?php echo check_on_off($item['send_mail']) ?></td>
        <td><?php echo check_on_off($item['alarm_repl_status']) ?></td>

        <td><?php echo check_on_off($item['alarm_connections']) ?></td>
        <td><?php echo check_on_off($item['alarm_used_memory']) ?></td>
        <td><?php echo check_on_off($item['alarm_repl_delay']) ?></td>
        <!--td><?php echo check_on_off($item['alarm_slow_querys']) ?></td>
        <td><?php echo check_on_off($item['alarm_error_querys']) ?></td-->

        <td><span class='badge badge-warning'><?php echo $item['threshold_connections'] ?>%</span></td>
        <td><span class='badge badge-warning'><?php echo $item['threshold_used_memory'] ?>%</span></td>
        <td><span class='badge badge-warning'><?php echo $item['threshold_repl_delay'] ?></span></td>
        <!--td><span class='badge badge-warning'><?php echo $item['threshold_slow_querys'] ?></span></td>
        <td><span class='badge badge-warning'><?php echo $item['threshold_error_querys'] ?></span></td-->
		
		<?php
			$user=$this->session->userdata('username');
			if($user!='admin' and strstr($item['owner'],$user)==false) 
			{
		?>
				<td style="color:green">无权限</td>
		<?php
			}
			else 
			{
		?>
				<td><a href="<?php echo site_url('servers/edit/'.$item['id']) ?>" target="_blank" title="编辑"><i class="icon-pencil"></i></a>&nbsp;<a href="<?php echo site_url('servers/delete/'.$item['id']) ?>" class="confirm_delete" title="放入回收站" ><i class="icon-trash"></i></a>
        </td>
        <?php
			}
		?>
		
	</tr>
 <?php endforeach;?>
   <tr>
  <td colspan="13">
  共查询到<font color="red" size="+1"><?php echo $datacount ?></font>条记录.
  </td>
  </tr>
<?php }else{  ?>
<tr>
<td colspan="12">
<font color="red">对不起，没有查询到相关数据！</font>
</td>
</tr>
<?php } ?>
	 
</table>

<script src="./bootstrap/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript">
	$(' .confirm_delete').click(function(){
		return confirm('确定要放入回收站？');	
	});
</script>
