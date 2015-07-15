<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>
<form name="form" class="form-inline" method="get" action="<?php site_url('dbcoldback/index') ?>" >
 <h5>配置列表>>
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
  <a href="<?php echo site_url('dbcoldback/index') ?>" class="btn btn-warning">重置</a>
  <small><a  href="<?php echo site_url('dbcoldback/add') ?>"  target="_blank">&nbsp;&nbsp;&nbsp;点此添加冷备</a></small>
 </h5>
</form>
</div>
<br>

<table class="table table-hover table-striped  table-bordered table-condensed">
    <tr>
	<th colspan="2"><center>备份机</center></th>
        <th colspan="2"><center>冷备机</center></th>
	<th colspan="5"><center>备份配置</center></th>
	<th colspan="2"><center>告警项</center></th>
        <th colspan="2"><center>信息</center></th>
	<!--th rowspan="2"><center>修改时间</center></th>
        <th rowspan="2"><center>管理</center></th-->
    </tr>
    <tr>
        <th>业务</th>
        <th>实例</th>

	<th>IP</th>
	<th>备份路径</th>

        <th>是否备份</th>
        <th>备份类型</th>
        <th>备份时间</th>
        <th>备份周期(天/次)</th>
        <th>保存备份数</th>

        <th>是否告警</th>
        <!--th>大小波动阈值</th-->
        <th>负责人</th>

        <th>修改时间</th>
        <th>管理</th>
    </tr>

 <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
    <tr style="font-size: 13px;">
        <td><strong><?php echo $item['display_name'] ?></strong></td>
	<td><strong><?php echo $item['host'] ?>:<?php echo $item['port'] ?></strong></td>

        <td><?php echo $item['back_IP'] ?></td>
        <td><?php echo $item['back_path'] ?></td>

        <td><?php echo check_on_off($item['back_flag']) ?></td>
        <td><?php echo $item['db_name'] ?></td>
        <td><?php echo $item['back_time'] ?></td>
        <td><span class='badge badge-warning'><?php echo $item['back_cycle'] ?></span></td>
        <td><span class='badge badge-warning'><?php echo $item['save_number'] ?></span></td>

        <td><?php echo check_on_off($item['alarm_flag']) ?></td>
        <!--td><span class='badge badge-warning'><?php echo $item['change_max'] ?></span></td-->
        <td><?php echo $item['charge_person'] ?></td>
        <td><?php echo $item['modify_time'] ?></td>
		
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
				<td><a href="<?php echo site_url('dbcoldback/edit/'.$item['server_id']) ?>" target="_blank" title="编辑"><i class="icon-pencil"></i></a>&nbsp;<a href="<?php echo site_url('dbcoldback/delete/'.$item['application_id'].'_'.$item['server_id']) ?>" class="confirm_delete" title="删除" ><i class="icon-trash"></i></a>
        </td>
        <?php
			}
		?>
		
	</tr>
 <?php endforeach;?>
   <!--tr>
  <td colspan="13">
  共查询到<font color="red" size="+1"><?php echo $datacount ?></font>条记录.
  </td>
  </tr-->
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
		return confirm('确定要删除？');	
	});
</script>
