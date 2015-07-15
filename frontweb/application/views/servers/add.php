<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<head>
	<script src="./textext/js/jquery.min.js"></script>
	<script src="./textext/js/textext.plugin.autocomplete.js"></script>
	<script src="./textext/js/jquery.textext.js"></script>
	<script src="./textext/js/gbin.js"></script>
</head>

<div class="page-header">
  <h4>添加主机>>&nbsp;<a href="<?php echo site_url('servers/add') ?>">&nbsp;&nbsp;新增&nbsp;</a>/<a href="<?php echo site_url('servers/index') ?>">&nbsp;&nbsp;列表&nbsp;</a>/<a  href="<?php echo site_url('servers/trash') ?>">&nbsp;&nbsp;回收站&nbsp;</a></h4>
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

<form name="form" class="form-horizontal" method="post" action="<?php echo site_url('servers/add') ?>" >
<input type="hidden" name="submit" value="add"/> 
   
    <div class="control-group">
    <label class="control-label" for="">*主机</label>
    <div class="controls">
      <input type="text" id="host"  name="host" value="<?php echo set_value('host'); ?>" >
      <span class="help-inline"></span>
    </div>
   </div>
   <div class="control-group">
    <label class="control-label" for="">*端口</label>
    <div class="controls">
      <input type="text" id="port"  name="port" value="<?php echo set_value('port'); ?>" >
      <span class="help-inline"></span>
    </div>
   </div>
   <div class="control-group">
    <label class="control-label" for="">密码</label>
    <div class="controls">
      <input type="text" id="passwd"  name="passwd" value="<?php echo set_value('passwd'); ?>" >
      <span class="help-inline"></span>
    </div>
   </div>
   <div class="control-group">
    <label class="control-label" for="">选择业务</label>
    <div class="controls">
        <select name="application_id" id="application_id">
        <option value=""  >选择业务</option>
        <?php if(!empty($application)) {?>
        <?php foreach ($application  as $item):?>
         <option value="<?php echo $item['id']?>"  ><?php echo $item['name']?>(<?php echo $item['display_name']?>)</option>
        <?php endforeach;?>
        <?php } ?>
        </select>
        <span class="help-inline">请按业务对主机进行分类</span>
    </div>
   </div> 
    <div class="control-group">
    <label class="control-label" for="">监控状态</label>
    <div class="controls">
        <select name="status" id="status" class="input-small">
         <option value="1"  >开启</option>
         <option value="0"  >关闭</option>
        </select>
        <span class="help-inline">总开关</span>
    </div>
   </div>
   
    <div class="control-group">
    <label class="control-label" for="">发送告警</label>
    <div class="controls">
        <select name="send_mail" id="send_mail" class="input-small">
         <option value="1"  >开启</option>
         <option value="0"  >关闭</option>
        </select>
       告警间隔&nbsp;
		<select name="alarm_interval" id="alarm_interval" class="input-small">
         <option value="30" >30s</option>
         <option value="60" >60s</option>
         <option value="120" >120s</option>
         <option value="180" >180s</option>
         <option value="300" selected>300s</option>
         <option value="600" >600s</option>
         <option value="1200" >1200s</option>
        </select>
       告警方式&nbsp;
        <select name="alarm_type" id="alarm_type" class="input-small">
         <option value="0" >邮件</option>
         <option value="1" selected>邮件+短信</option>
         <option value="2" >邮件+微信</option>
        </select>
        收敛方式&nbsp;
        <select name="converge_type" id="converge_type" class="input-small">
         <option value="0" >不收敛</option>
         <option value="1" selected>递增收敛</option>
         <option value="2" >倍增*2收敛</option>
        </select>
    </div>
   </div>
   
    <div class="control-group">
    <label class="control-label" for="">总连接数告警</label>
    <div class="controls">
        <select name="alarm_connections" id="alarm_connections" class="input-small">
         <option value="1"  >开启</option>
         <option value="0"  >关闭</option>
        </select>
        告警阀值&nbsp;<input type="text" id="threshold_connections" class="input-small" placeholder="告警阀值" name="threshold_connections" value="90" >
        <span class="help-inline">% 总连接数使用率,取值范围0~100,建议值90%</span>
    </div>
   </div>
   
    <div class="control-group">
    <label class="control-label" for="">内存告警</label>
    <div class="controls">
        <select name="alarm_used_memory" id="alarm_used_memory" class="input-small">
         <option value="1" selected>开启</option>
         <option value="0" >关闭</option>
        </select>
        告警阀值&nbsp;<input type="text" id="threshold_used_memory" class="input-small" placeholder="告警阀值" name="threshold_used_memory" value="90" >
        <span class="help-inline">% 内存使用率,取值范围0~100,建议值90%</span>
    </div>
   </div>
   
    <div class="control-group">
    <label class="control-label" for="">复制状态告警</label>
    <div class="controls">
        <select name="alarm_repl_status" id="alarm_repl_status" class="input-small">
         <option value="1" selected>开启</option>
         <option value="0" >关闭</option>
        </select>
    </div>
   </div>
    <div class="control-group">
    <label class="control-label" for="">复制延迟告警</label>
    <div class="controls">
        <select name="alarm_repl_delay" id="alarm_repl_delay" class="input-small">
         <option value="1" >开启</option>
         <option value="0" selected>关闭</option>
        </select>
        告警阀值&nbsp;<input type="text" id="threshold_repl_delay" class="input-small" placeholder="告警阀值" name="threshold_repl_delay" value="20480" >
        <span class="help-inline">主备同步偏移差值,建议取值范围</span>
    </div>
   </div>
      
   <div class="control-group">
    <label class="control-label" for="">告警接收人</label>
    <div class="controls">
      <input type="text" id="alarm_person"  name="alarm_person" value="<?php echo set_value('alarm_person'); ?>" class="input-xxlarge">
      <span class="help-inline">多人请用 ;分割</span>
    </div>
   </div>

  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn btn-success">提 交</button> &nbsp;我想放弃提交，<a href='<?php echo site_url('servers/index')?>'>点此返回</a>    
    </div>
  </div>
                                    
</form>

