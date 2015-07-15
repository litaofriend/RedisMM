<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>Redis监控管理系统</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<base href="<?php echo base_url().'application/views/static/'; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="./bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
<script src="./bootstrap/js/bootstrap-switch.js"></script>
<link href="./bootstrap/css/bootstrap-switch.css" rel="stylesheet"/>
<link href="./bootstrap/css/jquery-ui-1.10.0.custom.css" rel="stylesheet" />
<link href="./bootstrap/css/font-awesome.min.css"  rel="stylesheet">
<link href="./bootstrap/css/prettify.css"  rel="stylesheet">
<link href="./bootstrap/css/flat-ui.css" rel="stylesheet" media="screen">
<script src="./bootstrap/js/jquery-1.9.0.min.js"></script>
<link href="./js/DataTables-1.10.7/media/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./js/DataTables-1.10.7/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="./js/colResizable-1.5.min.js"></script>
            
<link rel="stylesheet" href="css/style.css" />
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #FAFAFA;
        font-family:"微软雅黑";
      }
      .nav li a{ font-size:16px; }
      .page-header{ border-color: #575757; border-width:2px;}
      .page-header h4 a{ font-size:16px;}
      .btn{font-family:"微软雅黑";}
    </style>
</head>

<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <!--button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button-->
          <a class="brand" href="<?php echo site_url('index/index') ?>">RedisMonitor</a>
          <div class="nav-collapse collapse">
            <?php  if($this->session->userdata('logged_in')!=1) {?>
             <p class="navbar-text pull-right">
             <a href='<?php echo site_url('user/login') ?>' class="btn-success  btn">登录</a>
             </p>
            <?php } else{ ?>
             <p class="navbar-text pull-right">
              <small class="text-right" > 欢迎您,<?php echo $this->session->userdata('username')?>  </small>
              <a href="<?php echo site_url('login/logout')?>" class="btn-success btn">退出</a>
             </p>
            <?php }?>

             <ul class="nav">
                <li <?php if($cur_nav=='monitor_server_status') echo "class=active"; ?> ><a href="<?php echo site_url('monitor/redis_server_status') ?>">进程状态</a></li>
                <li <?php if($cur_nav=='monitor_run_status') echo "class=active"; ?> ><a href="<?php echo site_url('monitor/redis_run_status') ?>">性能监控</a></li>
                <li <?php if($cur_nav=='monitor_resource_status') echo "class=active"; ?> ><a href="<?php echo site_url('monitor/redis_resource_status') ?>">资源监控</a></li>
                <li <?php if($cur_nav=='monitor_keyspace') echo "class=active"; ?> ><a href="<?php echo site_url('monitor/redis_keyspace') ?>">键状态</a></li>
                <li <?php if($cur_nav=='monitor_persistence_status') echo "class=active"; ?> ><a href="<?php echo site_url('monitor/redis_persistence_status') ?>">备份监控</a></li>
                <li <?php if($cur_nav=='monitor_replication') echo "class=active"; ?> ><a href="<?php echo site_url('monitor/redis_replication') ?>">主从监控</a></li>
                <li <?php if($cur_nav=='dbcoldback_add'||$cur_nav=='dbcoldback_index'||$cur_nav=='dbcoldback_history') echo "class=active"; ?> ><a href="<?php echo site_url('dbcoldback/index') ?>">冷备管理</a>
                  <ul>
                      <li <?php if($cur_nav=='dbcoldback_add') echo "class=active"; ?> ><a href="<?php echo site_url('dbcoldback/add') ?>">添加冷备</a></li>
                      <li <?php if($cur_nav=='dbcoldback_index') echo "class=active"; ?> ><a href="<?php echo site_url('dbcoldback/index') ?>">配置列表</a></li>
                      <li <?php if($cur_nav=='dbcoldback_history') echo "class=active"; ?> ><a href="<?php echo site_url('dbcoldback/history') ?>">冷备历史</a></li>
                  </ul>
                </li>
                <li <?php if($cur_nav=='alarm_index') echo "class=active"; ?> ><a href="<?php echo site_url('alarm/index') ?>">告警事件</a></li>
               <?php  if($this->session->userdata('logged_in')==1) { ?>
                <li <?php if($cur_nav=='config') echo "class=active"; ?> ><a href="<?php echo site_url('application/index') ?>">管理中心</a>
                        <ul>
                        	<?php  if($this->session->userdata('username')=='admin') {?>
                              <li <?php if($cur_nav=='config') echo "class=active"; ?> ><a href="<?php echo site_url('option/index') ?>">全局配置</a></li>
                            <?php }?>
                              <li <?php if($cur_nav=='application') echo "class=active"; ?> ><a href="<?php echo site_url('application/index') ?>">管理业务</a></li>
                              <li <?php if($cur_nav=='servers') echo "class=active"; ?> ><a href="<?php echo site_url('servers/index') ?>">管理主机</a></li>
                            <?php  if($this->session->userdata('username')=='admin') {?>
                              <li <?php if($cur_nav=='user_index') echo "class=active"; ?> ><a href="<?php echo site_url('user/index') ?>">管理用户</a></li>
                            <?php }?>
                              <li <?php if($cur_nav=='user_password') echo "class=active"; ?> ><a href="<?php echo site_url('user/password') ?>">更改密码</a></li>
                       </ul> 
                </li>
                <?php }?>
              </ul>
 
          </div>
        </div>
      </div>
    </div>
    
<div style="height: 50px;"></div>

<div class="container">
   <?php echo $content_for_layout ; ?>
</div>

<div class="container-fluid">
    <hr>

        <p>&copy; RedisMM V1.0 </p>

</div>

<script src="./bootstrap/js/bootstrap.min.js"></script>
<script src="./bootstrap/js/jquery-ui-1.10.0.custom.min.js"></script>

<div style="display:none;" class="back-to" id="toolBackTop">
<a title="返回顶部" onclick="window.scrollTo(0,0);return false;" href="#top" class="back-top">
返回顶部</a>
</div>

<style>
.back-to {bottom: 35px;overflow:hidden;position:fixed;right:10px;width:50px;z-index:999;}
.back-to .back-top {background: url("img/back-top.png") no-repeat scroll 0 0 transparent;display: block;float: right;height:50px;margin-left: 10px;outline: 0 none;text-indent: -9999em;width: 50px;}
<!--.back-to .back-top:hover {background-position: -50px 0;}-->
</style>

<script type="text/javascript">
  $(document).ready(function() { 
    $('#status_table').DataTable({
      "pagingType":   "full_numbers",
      "lengthMenu": [ 10, 20, 50, 75, 100 ],
      "pageLength": 10,
      "sLoadingRecords": "正在加载数据...",
      "sZeroRecords": "暂无数据",
      //"order": [[ 0, "desc" ]],
      stateSave: true,
      "searching": true,
      "ordering": true,
      "autoWidth": true,
      //"scrollY": "600px",//dt高度
      //"dom": 'rt<"bottom"iflp<"clear">>',
      "language": {
           "processing": "玩命加载中...",
            "lengthMenu": "显示 _MENU_ 项结果",
            "zeroRecords": "没有匹配结果",
            "info": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            "infoEmpty": "显示第 0 至 0 项结果，共 0 项",
            "infoFiltered": "(由 _MAX_ 项结果过滤)",
            "sSearch": "模糊查询：",
            "infoPostFix": "",
            "url": "",
            "paginate": {
                    "first":    "首页",
                    "previous": "上一页",
                    "next":     "下一页",
                    "last":     "末页"
            }
       }
    });
    //$(".table").colResizable();
  });

$(document).ready(function () {
        var bt = $('#toolBackTop');
        var sw = $(document.body)[0].clientWidth;

        var limitsw = (sw - 1200) / 2 - 40;
        if (limitsw > 0){
                limitsw = parseInt(limitsw);
                bt.css("right",limitsw);
        }

        $(window).scroll(function() {
                var st = $(window).scrollTop();
                if(st > 30){
                        bt.show();
                }else{
                        bt.hide();
                }
        });
})
</script>

<script type="text/javascript">
function get_host_by_application(){
    var application_id=$("#application_id").val();
    if(application_id==''){
       //alert("未选择业务！");
       return false;
    }
    else{
    	$.ajax({   
		  type:"GET",   
			  url:"<?php echo site_url('monitor/get_server_by_application') ?>",
			  data:{
				  application_id: application_id
				  },   
			  success:function(data){
				//alert(data);
				$("select[name='server_id'] option:gt(0)").remove();
	            $("select[name='server_id']").append(data);
				return false;
			}               
         });   
		return false;

    }
}

</script> 
 
</body>
</html>
