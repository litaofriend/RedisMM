<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<head>
	<script src="./textext/js/jquery.min.js"></script>
	<script src="./textext/js/textext.plugin.autocomplete.js"></script>
	<script src="./textext/js/jquery.textext.js"></script>
	<script src="./textext/js/gbin.js"></script>
        <script src="./js/DatePicker/WdatePicker.js"></script>
</head>

<div class="page-header">
  <h4>添加冷备>>
      <!--div class="btn-group">
        <a class="btn btn-link active" href="<?php echo site_url('dbcoldback/add') ?>"><i class="fui-new-16"></i>&nbsp;新增</a>
        <a class="btn btn-link " href="<?php echo site_url('dbcoldback/index') ?>"><i class="fui-menu-16"></i>&nbsp;列表</a>
      </div--> 
  </h4>
</div>
  
<hr/>                

<?php if ($error_code!==0) { ?>
<div class="ui-widget">
<div class="ui-state-error   ui-corner-all">
<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
<?php echo validation_errors(); ?></p>
</div>
</div>

<?php } ?>

<form name="form" class="form-horizontal" method="post" action="<?php echo site_url('dbcoldback/add') ?>" >
<input type="hidden" name="submit" value="add"/> 
  
   <div class="control-group">
    <label class="control-label" for="">业务</label>
    <div class="controls">
        <select name="application_id" id="application_id" onchange="get_host_by_application()">
        <option value=""  >选择业务</option>
        <?php if(!empty($application)) {?>
        <?php foreach ($application  as $item):?>
         <option value="<?php echo $item['id']?>"  ><?php echo $item['name']?>(<?php echo $item['display_name']?>)</option>
        <?php endforeach;?>
        <?php } ?>
        </select>
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="">主机</label>
    <div class="controls">
      <select name="server_id" id="server_id" style="" >
      <option value="">选择主机</option>
      <?php foreach ($server as $item):?>
      <option value="<?php echo $item['id'];?>" <?php if($setval['server_id']==$item['id']) echo "selected"; ?> ><?php echo $item['host'];?>:<?php echo $item['port'];?></option>
       <?php endforeach;?>
      </select>
      端口&nbsp;<input type="text" id="ssh_port"  name="ssh_port" value="22" class="input-small" placeholder="ssh端口" title="ssh端口"> 
      用户名&nbsp;<input type="text" id="ssh_user"  name="ssh_user" value="root" class="input-small" placeholder="ssh用户名" title="ssh用户名"> 
      密码&nbsp;<input type="text" id="ssh_passwd"  name="ssh_passwd" value="" class="input-small" placeholder="ssh密码" title="ssh密码">
    </div>
   </div>

    <div class="control-group">
    <label class="control-label" for="">冷备机IP</label>
    <div class="controls">
      <input type="text" id="back_IP"  name="back_IP" value="*.*.*.*" >
      &nbsp;&nbsp;&nbsp;&nbsp;端口&nbsp;<input type="text" id="back_ssh_port"  name="back_ssh_port" value="22" class="input-small" placeholder="ssh端口" title="ssh端口">
      用户名&nbsp;<input type="text" id="back_ssh_user"  name="back_ssh_user" value="root" class="input-small" placeholder="ssh用户名" title="ssh用户名">
      密码&nbsp;<input type="text" id="back_ssh_passwd"  name="back_ssh_passwd" value="" class="input-small" placeholder="ssh密码" title="ssh密码">
      <!--span class="help-inline">可为空，系统从PWD自动获取</span-->
    </div>
   </div>

    <div class="control-group">
    <label class="control-label" for="">冷备机路径</label>
    <div class="controls">
      <input type="text" id="back_path"  name="back_path" value="/data/redisback/" class="input-middle">
      <span class="help-inline"></span>
    </div>
   </div>

    <div class="control-group">
    <label class="control-label" for="">备份状态</label>
    <div class="controls">
        <select name="back_flag" id="back_flag" class="input-small">
         <option value="y"  >开启</option>
         <option value="n"  >关闭</option>
        </select>
    </div>
   </div>

   <div class="control-group">
    <label class="control-label" for="">备份类型</label>
    <div class="controls">
        <select name="db_name" id="db_name" class="input-middle">
         <option value="rdb;aof" selected>RDB+AOF</option>
         <option value="rdb" >RDB</option>
         <option value="aof" >AOF</option>
        </select>
    </div>
   </div>

    <div class="control-group">
    <label class="control-label" for="">备份周期</label>
    <div class="controls">
      <input type="text" id="back_cycle"  name="back_cycle" value="1" >
      <span class="help-inline">天/次</span>
    </div>
   </div>

    <div class="control-group">
    <label class="control-label" for="">备份时间</label>
    <div class="controls">
      <input class="Wdate"  type="text" id="back_time"  name="back_time" value="07:00" onFocus="WdatePicker({doubleCalendar:false,isShowClear:false,readOnly:false,dateFmt:'HH:mm'})"/>
      <span class="help-inline"></span>
    </div>
   </div>

    <div class="control-group">
    <label class="control-label" for="">保存备份数</label>
    <div class="controls">
      <input type="text" id="save_number"  name="save_number" value="3" >
      <span class="help-inline">个,磁盘空间有限,建议不要超过5个</span>
    </div>
   </div>
   
    <div class="control-group">
    <label class="control-label" for="">发送告警</label>
    <div class="controls">
        <select name="alarm_flag" id="alarm_flag" class="input-small">
         <option value="y"  >开启</option>
         <option value="n"  >关闭</option>
        </select>
    </div>
   </div>
 
    <div class="control-group" style="display:none">
    <label class="control-label" for="">大小波动</label>
    <div class="controls">
      <input type="text" id=""  name="change_max" value="10" >
      <span class="help-inline">%,同昨天对比,超过此阈值告警</span>
    </div>
   </div>
      
   <div class="control-group">
    <label class="control-label" for="">告警接收人</label>
    <div class="controls">
      <input type="text" id="charge_person"  name="charge_person" value="admin" class="input-xxlarge">
      <span class="help-inline">多人请用 ;分割</span>
    </div>
   </div>

  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn btn-success">提 交</button> &nbsp;我想放弃提交，<a href='<?php echo site_url('dbcoldback/index')?>'>点此返回</a>    
    </div>
  </div>
                                    
</form>

