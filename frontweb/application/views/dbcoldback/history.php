<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>
<form name="form" class="form-inline" method="get" action="<?php site_url('dbcoldback/history') ?>" >
 <h5>冷备历史>>
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
  <a href="<?php echo site_url('dbcoldback/history') ?>" class="btn btn-warning">重置</a>
  <small><a  href="<?php echo site_url('dbcoldback/index') ?>">&nbsp;&nbsp;&nbsp;点此查看配置列表</a></small>
 </h5>
</form>
</div>
<br>


<table id="status_table" class="table table-hover table-striped  table-bordered table-condensed dataTable" style="font-size: 13px;">
    <?php if(!empty($datalist)) {?>
    <thead>
    <?php } ?>
    <tr>
	<th colspan="3"><center>DB信息</center></th>
	<th colspan="2"><center>备份文件</center></th>
	<th colspan="4"><center>备份结果</center></th>
    </tr>
    <tr>
	<th>业务</th>
	<th>主机</th>
	<th>类型</th>

	<th>文件名</th>
	<!--th>check文件</th>
	<th>文件md5</th-->
	<th>文件大小(M)</th>

	<th>是否成功</th>
	<th>是否删除</th>
	<th>备份日期</th>
	<th>修改时间</th>
    </tr>
    <?php if(!empty($datalist)) {?>
    </thead>
    <?php } ?>
	
 <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
    <tr style="font-size: 13px;">
	<td><strong><?php echo $item['display_name'] ?><strong></td>
        <td><strong><?php echo $item['host'] ?>:<?php echo $item['port'] ?><strong></td>
        <td><?php echo $item['db_name'] ?></td>
        <td><?php echo $item['file_name'] ?></td>
        <!--td><?php echo $item['check_file_name'] ?></td>
        <td><?php echo $item['db_md5sum'] ?></td-->
        <td><?php echo round($item['db_size']/1024/1024,2) ?></td>
        <td><?php echo check_status($item['suc_flag']) ?></td>
        <td><?php echo check_status($item['del_flag']) ?></td>
        <td><?php echo $item['date'] ?></td>
        <td><?php echo $item['modify_time'] ?></td>
    </tr>
 <?php endforeach;?>
  <!--tr>
  <td colspan="13">
  共查询到<font color="red" size="+1"><?php echo $datacount ?></font>条记录.
  </td>
  </tr-->
<?php }else{  ?>
<tr>
<td colspan="9">
<font color="red">对不起，没有查询到相关数据！</font>
</td>
</tr>
<?php } ?>
	 
</table>


