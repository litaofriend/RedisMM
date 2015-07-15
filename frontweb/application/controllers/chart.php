<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Chart extends Front_Controller {

    function __construct(){
		parent::__construct();
	$this->load->model("monitor_model","monitor");
        $this->load->model('application_model','app');
        $this->load->model('servers_model','server');
        $this->load->model('chart_model','chart');
        
	}

    
    public function index(){
    }
   
    public function run_status(){
        $server_id = $this->uri->segment(3);
        $server_id=!empty($server_id) ? $server_id : "0";
        $begin_time = $this->uri->segment(4);
        $begin_time=!empty($begin_time) ? $begin_time : "60";
        $time_span = $this->uri->segment(5);
        $time_span=!empty($time_span) ? $time_span : "hour";

        $timestamp=time()-60*$begin_time;
        $time= date('YmdHi',$timestamp);

        $data["chart_result"]=$this->chart->get_redis_run_status($server_id,$time);

        $chart_option=array();
        if($time_span=='hour'){
            $chart_option['formatString']='%H:%M';
        }
        else if($time_span=='day'){
            $chart_option['formatString']='%m/%d %H:%M';
        }

        $data['chart_option']=$chart_option;

        $data['begin_time']=$begin_time;
        $data['cur_nav']='chart_index';
        //$data["server"]=$servers=$this->server->get_total_record_slave();
        $data['cur_server_id']=$server_id;
        $data["cur_server"] = $this->server->get_servers($server_id);
        $this->layout->view('chart/run_status',$data);

    }


    public function resource_status(){
        $server_id = $this->uri->segment(3);
        $server_id=!empty($server_id) ? $server_id : "0";
        $begin_time = $this->uri->segment(4);
        $begin_time=!empty($begin_time) ? $begin_time : "60";
        $time_span = $this->uri->segment(5);
        $time_span=!empty($time_span) ? $time_span : "hour";

        $timestamp=time()-60*$begin_time;
        $time= date('YmdHi',$timestamp);

        $data["chart_result"]=$this->chart->get_redis_resource_status($server_id,$time);

        $chart_option=array();
        if($time_span=='hour'){
            $chart_option['formatString']='%H:%M';
        }
        else if($time_span=='day'){
            $chart_option['formatString']='%m/%d %H:%M';
        }

        $data['chart_option']=$chart_option;

        $data['begin_time']=$begin_time;
        $data['cur_nav']='chart_index';
        //$data["server"]=$servers=$this->server->get_total_record_slave();
        $data['cur_server_id']=$server_id;
        $data["cur_server"] = $this->server->get_servers($server_id);
        $this->layout->view('chart/resource_status',$data);
    }

    public function replication_status(){
        $server_id = $this->uri->segment(3);
        $server_id=!empty($server_id) ? $server_id : "0";
        $begin_time = $this->uri->segment(4);
        $begin_time=!empty($begin_time) ? $begin_time : "60";
        $time_span = $this->uri->segment(5);
        $time_span=!empty($time_span) ? $time_span : "hour";

        $timestamp=time()-60*$begin_time;
        $time= date('YmdHi',$timestamp);

        $data["chart_result"]=$this->chart->get_replication_delay($server_id,$time);

        $chart_option=array();
        if($time_span=='hour'){
            $chart_option['formatString']='%H:%M';
        }
        else if($time_span=='day'){
            $chart_option['formatString']='%m/%d %H:%M';
        }

        $data['chart_option']=$chart_option;

        $data['begin_time']=$begin_time;
        $data['cur_nav']='chart_index';
        //$data["server"]=$servers=$this->server->get_total_record_slave();
        $data['cur_server_id']=$server_id;
        $data["cur_server"] = $this->server->get_servers($server_id);
        $this->layout->view('chart/replication_status',$data);
    }

    public function persistence_status(){
        $server_id = $this->uri->segment(3);
        $server_id=!empty($server_id) ? $server_id : "0";
        $begin_time = $this->uri->segment(4);
        $begin_time=!empty($begin_time) ? $begin_time : "60";
        $time_span = $this->uri->segment(5);
        $time_span=!empty($time_span) ? $time_span : "hour";

        $timestamp=time()-60*$begin_time;
        $time= date('YmdHi',$timestamp);

        $data["chart_result"]=$this->chart->get_redis_persistence_status($server_id,$time);

        $chart_option=array();
        if($time_span=='hour'){
            $chart_option['formatString']='%H:%M';
        }
        else if($time_span=='day'){
            $chart_option['formatString']='%m/%d %H:%M';
        }

        $data['chart_option']=$chart_option;

        $data['begin_time']=$begin_time;
        $data['cur_nav']='chart_index';
        //$data["server"]=$servers=$this->server->get_total_record_slave();
        $data['cur_server_id']=$server_id;
        $data["cur_server"] = $this->server->get_servers($server_id);
        $this->layout->view('chart/persistence_status',$data);
    }

    public function keyspace_status(){
        $server_id = $this->uri->segment(3);
        $server_id=!empty($server_id) ? $server_id : "0";
        $begin_time = $this->uri->segment(4);
        $begin_time=!empty($begin_time) ? $begin_time : "60";
        $time_span = $this->uri->segment(5);
        $time_span=!empty($time_span) ? $time_span : "hour";

        $timestamp=time()-60*$begin_time;
        $time= date('YmdHi',$timestamp);

        $data["chart_result"]=$this->chart->get_redis_keyspace_status($server_id,$time);

        $chart_option=array();
        if($time_span=='hour'){
            $chart_option['formatString']='%H:%M';
        }
        else if($time_span=='day'){
            $chart_option['formatString']='%m/%d %H:%M';
        }

        $data['chart_option']=$chart_option;

        $data['begin_time']=$begin_time;
        $data['cur_nav']='chart_index';
        //$data["server"]=$servers=$this->server->get_total_record_slave();
        $data['cur_server_id']=$server_id;
        $data["cur_server"] = $this->server->get_servers($server_id);
        $this->layout->view('chart/keyspace_status',$data);
    }
}

/* End of file */
