<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Monitor extends Front_Controller {

    function __construct(){
		parent::__construct();
		$this->load->model("monitor_model","monitor");
        $this->load->model('application_model','app');
        $this->load->model('servers_model','server');
        $this->load->model("option_model","option");
        
	}
    
       public function redis_server_status()
       {
           $data["datalist"]=$this->monitor->get_redis_server_status();
           $setval["application_id"]=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
           $setval["server_id"]=isset($_GET["server_id"]) ? $_GET["server_id"] : "";
           $data["setval"]=$setval;
           $data["application"]=$this->app->get_total_record_usage();
           if (isset($_GET["application_id"])){
               $data["server"]=$this->server->get_record_usage_by_application($_GET["application_id"]);
           }
           else {
               $data["server"]=$this->server->get_total_record_usage();
           }
           $data["cur_nav"]="monitor_server_status";
           $this->layout->view("monitor/redis_server_status",$data);
       }


       public function redis_server_history_status()
       {
           $limit=500;
           $offset=0;
           $data["datalist"]=$this->monitor->get_redis_server_history_status($limit,$offset);
           $setval["application_id"]=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
           $setval["server_id"]=isset($_GET["server_id"]) ? $_GET["server_id"] : "";
           $data["setval"]=$setval;
           $data["application"]=$this->app->get_total_record_usage();
           if (isset($_GET["application_id"])){
               $data["server"]=$this->server->get_record_usage_by_application($_GET["application_id"]);
           }
           else {
               $data["server"]=$this->server->get_total_record_usage();
           }
           $data["cur_nav"]="monitor_server_status";
           $this->layout->view("monitor/redis_server_history_status",$data);
       }

       public function redis_run_status()
       {
           $data["datalist"]=$this->monitor->get_redis_run_status();
           $setval["application_id"]=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
           $setval["server_id"]=isset($_GET["server_id"]) ? $_GET["server_id"] : "";
           $setval["order"]=isset($_GET["order"]) ? $_GET["order"] : "";
           $setval["order_type"]=isset($_GET["order_type"]) ? $_GET["order_type"] : "";
           $data["setval"]=$setval;
           $data["application"]=$this->app->get_total_record_usage();
           if (isset($_GET["application_id"])){
               $data["server"]=$this->server->get_record_usage_by_application($_GET["application_id"]);
           }
           else {
               $data["server"]=$this->server->get_total_record_usage();
           }
           $data["cur_nav"]="monitor_run_status";
           $this->layout->view("monitor/redis_run_status",$data);
       }

       public function redis_resource_status()
       {
           $data["datalist"]=$this->monitor->get_redis_resource_status();
           $setval["application_id"]=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
           $setval["server_id"]=isset($_GET["server_id"]) ? $_GET["server_id"] : "";
           $setval["order"]=isset($_GET["order"]) ? $_GET["order"] : "";
           $setval["order_type"]=isset($_GET["order_type"]) ? $_GET["order_type"] : "";
           $data["setval"]=$setval;
           $data["application"]=$this->app->get_total_record_usage();
           if (isset($_GET["application_id"])){
               $data["server"]=$this->server->get_record_usage_by_application($_GET["application_id"]);
           }
           else {
               $data["server"]=$this->server->get_total_record_usage();
           }
           $data["cur_nav"]="monitor_resource_status";
           $this->layout->view("monitor/redis_resource_status",$data);
       }

       public function redis_persistence_status()
       {
           $data["datalist"]=$this->monitor->get_redis_persistence_status();
           $setval["application_id"]=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
           $setval["server_id"]=isset($_GET["server_id"]) ? $_GET["server_id"] : "";
           $setval["order"]=isset($_GET["order"]) ? $_GET["order"] : "";
           $setval["order_type"]=isset($_GET["order_type"]) ? $_GET["order_type"] : "";
           $data["setval"]=$setval;
           $data["application"]=$this->app->get_total_record_usage();
           if (isset($_GET["application_id"])){
               $data["server"]=$this->server->get_record_usage_by_application($_GET["application_id"]);
           }
           else {
               $data["server"]=$this->server->get_total_record_usage();
           }
           $data["cur_nav"]="monitor_persistence_status";
           $this->layout->view("monitor/redis_persistence_status",$data);
       }

       public function redis_keyspace()
       {
           $data["datalist"]=$this->monitor->get_redis_keyspace();
           $setval["application_id"]=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
           $setval["server_id"]=isset($_GET["server_id"]) ? $_GET["server_id"] : "";
           $setval["order"]=isset($_GET["order"]) ? $_GET["order"] : "";
           $setval["order_type"]=isset($_GET["order_type"]) ? $_GET["order_type"] : "";
           $data["setval"]=$setval;
           $data["application"]=$this->app->get_total_record_usage();
           if (isset($_GET["application_id"])){
               $data["server"]=$this->server->get_record_usage_by_application($_GET["application_id"]);
           }
           else {
               $data["server"]=$this->server->get_total_record_usage();
           }
           $data["cur_nav"]="monitor_keyspace";
           $this->layout->view("monitor/redis_keyspace",$data);
       }

    public function get_server_by_application()
    {   
	$server=$this->server->get_servers_by_application($_GET["application_id"]);
	foreach ($server as $item):
	echo "<option value=\"" , $item['id'] , "\" >" , $item['host'] , ":" , $item['port'] , "</option>";
	endforeach;
    }
	
    public function redis_replication()
    {
        $setval["application_id"]=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
        $setval["server_id"]=isset($_GET["server_id"]) ? $_GET["server_id"] : "";
        $setval["role"]=isset($_GET["role"]) ? $_GET["role"] : "";
        //$setval["delay"]=isset($_GET["delay"]) ? $_GET["delay"] : "";
        //$setval["order"]=isset($_GET["order"]) ? $_GET["order"] : "";
        //$setval["order_type"]=isset($_GET["order_type"]) ? $_GET["order_type"] : "";
        
        if($setval["application_id"]==""){
                $current_url= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'?noparam=1';
                //$current_url= site_url('monitor/redis_replication?application_id='.$setval["application_id"]."&server_id=".$setval["server_id"]."&role=".$setval["role"]);
                $this->load->library('pagination');
                $config['base_url'] = $current_url;
                $config['total_rows'] = $this->monitor->get_total_rows('redis_replication');
                //$config['total_rows'] = $this->monitor->get_total_replication_rows('redis_replication',$setval["application_id"],$setval["server_id"],$setval["role"]);
                $config['per_page'] = 30;
                $config['num_links'] = 5;
                $config['page_query_string'] = TRUE;
                $config['use_page_numbers'] = TRUE;
                $this->pagination->initialize($config);
                $offset = !empty($_GET['per_page']) ? $_GET['per_page'] : 1;
                $datalist=$this->monitor->get_total_record_paging_replication($config['per_page'],($offset-1)*$config['per_page']);
        }
        else{
                $current_url= site_url('monitor/redis_replication?application_id='.$setval["application_id"]."&server_id=".$setval["server_id"]."&role=".$setval["role"]);
                $this->load->library('pagination');
                $config['base_url'] = $current_url;
                $config['total_rows'] = $this->monitor->get_total_replication_rows('redis_replication',$setval["application_id"],$setval["server_id"],$setval["role"]);
                $config['per_page'] = 300;
                $config['num_links'] = 5;
                $config['page_query_string'] = TRUE;
                $config['use_page_numbers'] = TRUE;
                $this->pagination->initialize($config);
                $offset = !empty($_GET['per_page']) ? $_GET['per_page'] : 1;
                //$datalist=$this->monitor->get_total_record_paging_replication($config['per_page'],($offset-1)*$config['per_page']);
                $datalist=$this->monitor->get_total_record_replication();
        }

	//file_put_contents('/tmp/debug.log',serialize($datalist),FILE_APPEND); 
	$tmp_server_id=isset($_GET["server_id"]) ? $_GET["server_id"] : ""; 
	$tmp_role=isset($_GET["role"]) ? $_GET["role"] : "";
	if($tmp_server_id=="" && $tmp_role!="is_slave") { 
        //if(empty($_GET["search"])){
            $datalist = get_replication_tree($datalist);
	//file_put_contents('/tmp/debug.log',serialize($datalist),FILE_APPEND);
        //}
	}
        
        $data["setval"]=$setval;
        
        //$data["server"]=$this->server->get_total_record_usage();
	if (isset($_GET["application_id"])){
        	$data["server"]=$this->server->get_record_usage_by_application($_GET["application_id"]);
        }
        else {
        	$data["server"]=$this->server->get_total_record_usage();
        }
        $data["application"]=$this->app->get_total_record_usage();
        
        $data['datalist']=$datalist;
        
        $data["cur_nav"]="monitor_replication";
        $this->layout->view("monitor/redis_replication",$data);
    }
    
}

/* End of file */
