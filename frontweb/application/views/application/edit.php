<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<head>
	<script src="./textext/js/jquery.min.js"></script>
	<script src="./textext/js/textext.plugin.autocomplete.js"></script>
	<script src="./textext/js/jquery.textext.js"></script>
	<script src="./textext/js/gbin.js"></script>
</head>

<div class="page-header">
  <h4>编辑业务>>&nbsp;<a href="<?php echo site_url('application/add') ?>">&nbsp;&nbsp;新增&nbsp;</a>/<a href="<?php echo site_url('application/index') ?>">&nbsp;&nbsp;列表&nbsp;</a>/<a  href="<?php echo site_url('application/trash') ?>">&nbsp;&nbsp;回收站&nbsp;</a></h4>
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

<form name="form" class="form-horizontal" method="post" action="<?php echo site_url('application/edit') ?>" >
<input type="hidden" name="submit" value="edit"/> 
<input type='hidden'  name='id' value=<?php echo $record['id'] ?> />
   <div class="control-group">
    <label class="control-label" for="">*业务名称</label>
    <div class="controls">
      <input type="text" id=""  name="name" value="<?php echo $record['name']; ?>" >
      <span class="help-inline">由字母、数字、下划线组成</span>
    </div>
   </div>
    <div class="control-group">
    <label class="control-label" for="">*显示名称</label>
    <div class="controls">
      <input type="text" id=""  name="display_name" value="<?php echo $record['display_name']; ?>" >
      <span class="help-inline">由中文或任意字符组成</span>
    </div>
   </div>
   <div class="control-group">
    <label class="control-label" for="">*负责人</label>
    <div class="controls">
      <input type="text" id="owner"  name="owner" value="<?php echo $record['owner']; ?>" >
      <span class="help-inline">负责人才有权限管理改业务下的主机，多人请用 ;分割</span>
    </div>
   </div>
    <div class="control-group">
    <label class="control-label" for="">*是否启用</label>
    <div class="controls">
        <select name="status" id="status" class="input-small">
         <option value="1" <?php echo set_selected(1,$record['status']) ?> >是</option>
         <option value="0" <?php echo set_selected(0,$record['status']) ?> >否</option>
        </select>
    </div>
   </div>

  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn btn-success">提 交</button> &nbsp;我想放弃提交，<a href='<?php echo site_url('application/index')?>'>点此返回</a>
    </div>
  </div>
                                    
</form>

