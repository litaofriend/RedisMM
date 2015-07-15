<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class DbColdback extends Front_Controller {
    function __construct(){
		parent::__construct();
        
        /*if($this->session->userdata('username')!='admin') {
            redirect(site_url());  
        }*/
    	if($this->session->userdata('username')=='') {
            redirect(site_url());  
        }
        $this->load->model('servers_model','servers');
        $this->load->model('application_model','app');
        $this->load->model('dbcoldback_model','dbcoldback');
	$this->load->library('form_validation');
	
	}
    
    /**
     * 首页
     */
    public function index(){
        $ext_where='';
        $application_id=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
        $server_id=isset($_GET["server_id"]) ? $_GET["server_id"] : "";
        if(!empty($application_id)){
            $ext_where=" and b.application_id=$application_id ";
        }
        if(!empty($server_id)){
            $ext_where=" and b.id=$server_id ";
        } 

	$sql="select a.display_name,a.owner,b.application_id,b.host,b.port,b.id,c.* from application a,servers b,redis_coldback_config c where a.id=b.application_id and b.id=c.server_id $ext_where order by a.display_name,b.host,b.port ;";
        #$sql="select servers.*,application.display_name,application.name,application.owner from servers  join application on servers.application_id=application.id and servers.is_delete=0 $ext_where order by application.id,servers.host,servers.port";
        $result=$this->servers->get_total_record_sql($sql);
        $data["datalist"]=$result['datalist'];
        $data["datacount"]=$result['datacount'];
        $data["application"]=$this->app->get_total_record_usage();
        if (isset($_GET["application_id"])){
            $data["server"]=$this->servers->get_record_usage_by_application($_GET["application_id"]);
        }
        else {
            $data["server"]=$this->servers->get_total_record_usage();
        }

        $setval["application_id"]=$application_id;
        $setval["server_id"]=$server_id;
        $data["setval"]=$setval;
        $data["cur_nav"]="dbcoldback_index";
        $this->layout->view("dbcoldback/index",$data);
    }
   
    public function history(){
	$ext_where='';
	$application_id=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
        $server_id=isset($_GET["server_id"]) ? $_GET["server_id"] : "";
	$host=isset($_GET["host"]) ? $_GET["host"] : "";
	$date=isset($_GET["date"]) ? $_GET["date"] : "";
        if(!empty($application_id)){
            $ext_where=" and b.application_id=$application_id ";
        }
        if(!empty($server_id)){
            $ext_where=" and b.id=$server_id ";
        }
        if(!empty($host)){
            $ext_where=$ext_where."  and b.host like '%$host%' ";
        }
        if(!empty($date)){
            $ext_where=$ext_where."  and c.date like '%$date%' ";
        }

        $sql="select a.display_name,b.id,b.host,b.port,c.* from application a,servers b,redis_coldback_info c where a.id=b.application_id and b.id=c.server_id $ext_where order by c.modify_time desc limit 150;";
        $result=$this->servers->get_total_record_sql($sql);
        $data["datalist"]=$result['datalist'];
        $data["datacount"]=$result['datacount'];
        $data["application"]=$this->app->get_total_record_usage();
        if (isset($_GET["application_id"])){
            $data["server"]=$this->servers->get_record_usage_by_application($_GET["application_id"]);
        }
        else {
            $data["server"]=$this->servers->get_total_record_usage();
        }
        $setval["application_id"]=$application_id;
        $setval["server_id"]=$server_id;
        $setval["host"]=$host;
        $setval["date"]=$date;
        $data["setval"]=$setval;
        $data["cur_nav"]="dbcoldback_history";
        $this->layout->view("dbcoldback/history",$data);
    } 
    /**
     * 添加
     */
    public function add(){
        /*
		 * 提交添加后处理
	 */
	$data['error_code']=0;
	if(isset($_POST['submit']) && $_POST['submit']=='add')
        {
    	    //$this->form_validation->set_rules('host',  'lang:host', 'trim|required|min_length[6]|max_length[30]');
            //$this->form_validation->set_rules('port',  'lang:port', 'trim|required|min_length[4]|max_length[6]|integer');
            $this->form_validation->set_rules('application_id',  'lang:application_id', 'trim|required');
	    $this->form_validation->set_rules('server_id',  'lang:server_id', 'trim|required');
	    $this->form_validation->set_rules('back_IP',  'lang:back_IP', 'trim|required|min_length[6]|max_length[30]');
	    $this->form_validation->set_rules('back_path',  'lang:back_path', 'trim|required|min_length[6]');
	    $this->form_validation->set_rules('db_name',  'lang:db_name', 'trim|required|min_length[1]');
	    $this->form_validation->set_rules('back_cycle',  'lang:back_cycle', 'trim|required|integer');
	    $this->form_validation->set_rules('save_number',  'lang:save_number', 'trim|required|integer');
	    $this->form_validation->set_rules('change_max',  'lang:change_max', 'trim|required|integer');
            if ($this->form_validation->run() == FALSE)
            {
          	$data['error_code']='validation_error';
            }
            else
            {
                $server_id=$this->input->post('server_id');
                $sql="select host,port from servers where id='$server_id'";
                $result=$this->servers->get_record_sql($sql);
		$redis_IP=$result['host'];
		$redis_port=$result['port'];
		$sql="select count(1) as num from redis_coldback_config where server_id='$server_id';";
		$result=$this->servers->get_record_sql($sql);
		$num=$result['num'];
		if($num>=1)
		{
		    echo "<script>alert('$redis_IP:$redis_port exists!');</script>";
		}
		else
		{
    	      	   $data['error_code']=0;
    	      	   $data = array(
    	      	   'server_id'=>$server_id,
    	      	   'IP'=>$redis_IP,
	      	   'ssh_port'=>$this->input->post('ssh_port'),
	      	   'ssh_user'=>$this->input->post('ssh_user'),
	      	   'ssh_passwd'=>$this->input->post('ssh_passwd'),
    	      	   'back_IP'=>$this->input->post('back_IP'),
                   'back_ssh_port'=>$this->input->post('back_ssh_port'),
                   'back_ssh_user'=>$this->input->post('back_ssh_user'),
                   'back_ssh_passwd'=>$this->input->post('back_ssh_passwd'),
              	   'back_path'=>$this->input->post('back_path'),
              	   'db_name'=>$this->input->post('db_name'),
	      	   'back_cycle'=>$this->input->post('back_cycle'),
	      	   'back_time'=>$this->input->post('back_time'),
	      	   'save_days'=>'1',
              	   'save_number'=>$this->input->post('save_number'),
              	   'change_max'=>$this->input->post('change_max'),
              	   'back_flag'=>$this->input->post('back_flag'),
              	   'alarm_flag'=>$this->input->post('alarm_flag'),
              	   'charge_person'=>$this->input->post('charge_person'),
	      	   'modify_time'=>date('Y-m-d H:i:s',time()),
    	      	   	);
    	      	   $this->dbcoldback->insert($data);
                   $application_id=$this->input->post('application_id');
              	   redirect(site_url('dbcoldback/index?application_id='.$application_id.'&server_id='.$server_id));
		}
            }
        }
        //$data["application"]=$this->app->get_total_record();   
        $setval["application_id"]=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
        $setval["server_id"]=isset($_GET["server_id"]) ? $_GET["server_id"] : "";
	$data["setval"]=$setval;

        $data["application"]=$this->app->get_total_record_usage();
        if (isset($_GET["application_id"])){
                $data["server"]=$this->servers->get_record_usage_by_application($_GET["application_id"]);
        }
        else {
                $data["server"]=$this->servers->get_total_record_usage();
        }
        $data["cur_nav"]="dbcoldback_add";
        $this->layout->view("dbcoldback/add",$data);
    }
    
    /**
     * 编辑
     */
    public function edit($id=0){
        $id  = !empty($id) ? $id : $_POST['server_id'];
        /*
		 * 提交编辑后处理
		 */
        $data['error_code']=0;
	if(isset($_POST['submit']) && $_POST['submit']=='edit')
        {
            //$this->form_validation->set_rules('application_id',  'lang:application_id', 'trim|required');
            //$this->form_validation->set_rules('server_id',  'lang:server_id', 'trim|required');
            $this->form_validation->set_rules('back_IP',  'lang:back_IP', 'trim|required|min_length[6]|max_length[30]');
            $this->form_validation->set_rules('back_path',  'lang:back_path', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('db_name',  'lang:db_name', 'trim|required|min_length[1]');
            $this->form_validation->set_rules('back_cycle',  'lang:back_cycle', 'trim|required|integer');
            $this->form_validation->set_rules('save_number',  'lang:save_number', 'trim|required|integer');
            $this->form_validation->set_rules('change_max',  'lang:change_max', 'trim|required|integer');
	    if ($this->form_validation->run() == FALSE)
	    {
		$data['error_code']='validation_error';
//                file_put_contents('/tmp/dbmonitor_debug.log',"validation_error",FILE_APPEND);
	    }
	    else
	    {
		$application_id=$this->input->post('application_id');
		$server_id=$this->input->post('server_id');
		$ssh_port=$this->input->post('ssh_port');
		$ssh_user=$this->input->post('ssh_user');
		$ssh_passwd=$this->input->post('ssh_passwd');
                $back_IP=$this->input->post('back_IP');
                $back_ssh_port=$this->input->post('back_ssh_port');
                $back_ssh_user=$this->input->post('back_ssh_user');
                $back_ssh_passwd=$this->input->post('back_ssh_passwd');
                $back_path=$this->input->post('back_path');
                $db_name=$this->input->post('db_name');
                $back_flag=$this->input->post('back_flag');
                $back_cycle=$this->input->post('back_cycle');
                $save_number=$this->input->post('save_number');
		$alarm_flag=$this->input->post('alarm_flag');
                $change_max=$this->input->post('change_max');
                $charge_person=$this->input->post('charge_person');
                $sql=" update redis_coldback_config set ssh_port='$ssh_port',ssh_user='$ssh_user',ssh_passwd='$ssh_passwd',back_IP='$back_IP',back_ssh_port='$back_ssh_port',back_ssh_user='$back_ssh_user',back_ssh_passwd='$back_ssh_passwd',back_path='$back_path',db_name='$db_name',back_flag='$back_flag',back_cycle='$back_cycle',save_number='$save_number',alarm_flag='$alarm_flag',change_max='$change_max',charge_person='$charge_person',modify_time=now() where server_id='$server_id';";
//		file_put_contents('/tmp/dbmonitor_debug.log',$sql,FILE_APPEND); 
		$result=$this->servers->update_sql($sql);
		//$this->dbcoldback->update($data,$server_id);
                redirect(site_url('dbcoldback/index?application_id='.$application_id.'&server_id='.$server_id));
            }
        }
        
	$sql="select a.display_name,b.id,b.host,b.port,b.application_id,c.* from application a,servers b,redis_coldback_config c where a.id=b.application_id and b.id=c.server_id and b.id='$id';";
        $result=$this->servers->get_record_sql($sql);
        $data['record']=$result;
        $data["application"]=$this->app->get_total_record_usage();
        if (isset($_POST["application_id"])){
           $data["server"]=$this->servers->get_record_usage_by_application($_POST["application_id"]);
        }
        else {
           $data["server"]=$this->servers->get_total_record_usage();
        }
	//var_dump($result);
        /*$data["datacount"]=$result['datacount'];
        $data["application"]=$this->app->get_total_record_usage();
	$record = $this->dbcoldback->get_record_by_id($id);
	if(!$id || !$record){
		show_404();
	}
        else{
            $data['record']= $record;
        }*/
          
        $data["cur_nav"]="dbcoldback_edit";
        $this->layout->view("dbcoldback/edit",$data);
    }
    
    /**
     * 加入回收站
     */
    function delete($application_server){
        if($application_server){
	    /*$arr = explode("_", $application_server);
            $application_id=$arr[0];
	    $IP=$arr[1];
	    $port=$arr[2];*/
            $arr = explode("_", $application_server);
            $application_id=$arr[0];
            $server_id=$arr[1];
	    //$sql="delete from redis_coldback_config where IP='$IP' and port='$port'";
	    $sql="delete from redis_coldback_config where server_id='$server_id'";
	    $this->servers->update_sql($sql);
	    //file_put_contents('/tmp/dbmonitor_debug.log',"$sql",FILE_APPEND);
            redirect(site_url('dbcoldback/index?application_id='.$application_id));
        }
    }
}

