<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Servers extends Front_Controller {
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
		$this->load->library('form_validation');
	
	}
    
    /**
     * 首页
     */
    public function index(){
        $ext_where='';
        $application_id=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
        $host=isset($_GET["host"]) ? $_GET["host"] : "";
        if(!empty($application_id)){
            $ext_where=" and servers.application_id=$application_id ";
        } 
        if(!empty($host)){
            $ext_where=$ext_where."  and servers.host like '%$host%' ";
        }

        $sql="select servers.*,application.display_name,application.name,application.owner from servers  join application on servers.application_id=application.id and servers.is_delete=0 $ext_where order by application.id,servers.host,servers.port";
        $result=$this->servers->get_total_record_sql($sql);
        $data["datalist"]=$result['datalist'];
        $data["datacount"]=$result['datacount'];
        $data["application"]=$this->app->get_total_record_usage();
        $setval["application_id"]=$application_id;
        $setval["host"]=$host;
        $data["setval"]=$setval;
        $data["cur_nav"]="servers_index";
        $this->layout->view("servers/index",$data);
    }
    
    /**
     * 回收站
     */
    public function trash(){
        $sql="select servers.*,application.owner,application.display_name,application.name from servers  join application on servers.application_id=application.id and servers.is_delete=1";
        $result=$this->servers->get_total_record_sql($sql);
        $data["datalist"]=$result['datalist'];
        $data["datacount"]=$result['datacount'];
        $data["cur_nav"]="servers_trash";
        $this->layout->view("servers/trash",$data);
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
	    $this->form_validation->set_rules('host',  'lang:host', 'trim|required|min_length[6]|max_length[30]');
            $this->form_validation->set_rules('port',  'lang:port', 'trim|required|min_length[4]|max_length[6]|integer');
            $this->form_validation->set_rules('application_id',  'lang:application_id', 'trim|required');
            $this->form_validation->set_rules('threshold_connections',  'lang:threshold_connections', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_used_memory',  'lang:threshold_used_memory', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_repl_delay',  'lang:threshold_repl_delay', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_slow_querys',  'lang:threshold_slow_querys', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_error_querys',  'lang:threshold_error_querys', 'trim|required|integer');
 	    if ($this->form_validation->run() == FALSE)
	   {
 	   	$data['error_code']='validation_error';
 	   }
 	   else
 	   {
	      $data['error_code']=0;
	      $data = array(
		'host'=>$this->input->post('host'),
		'port'=>$this->input->post('port'),
                'passwd'=>$this->input->post('passwd'),
	        'application_id'=>$this->input->post('application_id'),
	        'status'=>$this->input->post('status'),
	        'send_mail'=>$this->input->post('send_mail'),
	        'slow_query'=>$this->input->post('slow_query'),
	        'alarm_connections'=>$this->input->post('alarm_connections'),
	        'alarm_used_memory'=>$this->input->post('alarm_used_memory'),
	        'alarm_repl_status'=>$this->input->post('alarm_repl_status'),
	        'alarm_repl_delay'=>$this->input->post('alarm_repl_delay'),
	        'threshold_connections'=>$this->input->post('threshold_connections'),
	        'threshold_used_memory'=>$this->input->post('threshold_used_memory'),
	        'threshold_repl_delay'=>$this->input->post('threshold_repl_delay'),
	       	'alarm_interval'=>$this->input->post('alarm_interval'),
	       	'alarm_type'=>$this->input->post('alarm_type'),
	       	'converge_type'=>$this->input->post('converge_type'),
	       	'alarm_person'=>$this->input->post('alarm_person'),
	       	'alarm_slow_querys'=>$this->input->post('alarm_slow_querys'),
	       	'threshold_slow_querys'=>$this->input->post('threshold_slow_querys'),
	       	'alarm_error_querys'=>$this->input->post('alarm_error_querys'),
	       	'threshold_error_querys'=>$this->input->post('threshold_error_querys'),
		);
	      $this->servers->insert($data);
              redirect(site_url('servers/index'));
            }
        }
        //$data["application"]=$this->app->get_total_record();   
        $data["application"]=$this->app->get_total_record_usage();
        $data["cur_nav"]="servers_add";
        $this->layout->view("servers/add",$data);
    }
    
    /**
     * 编辑
     */
    public function edit($id){

        $id  = !empty($id) ? $id : $_POST['id'];
        /*
		 * 提交编辑后处理
		 */
        $data['error_code']=0;
		if(isset($_POST['submit']) && $_POST['submit']=='edit')
        {
            $this->form_validation->set_rules('host',  'lang:host', 'trim|required|min_length[6]|max_length[30]');
            $this->form_validation->set_rules('port',  'lang:port', 'trim|required|min_length[4]|max_length[6]|integer');
            $this->form_validation->set_rules('application_id',  'lang:application_id', 'trim|required');
            $this->form_validation->set_rules('threshold_connections',  'lang:threshold_connections', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_used_memory',  'lang:threshold_used_memory', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_repl_delay',  'lang:threshold_repl_delay', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_slow_querys',  'lang:threshold_slow_querys', 'trim|required|integer');
            $this->form_validation->set_rules('threshold_error_querys',  'lang:threshold_error_querys', 'trim|required|integer');
	    if ($this->form_validation->run() == FALSE)
	    {
		$data['error_code']='validation_error';
	    }
	    else
	    {
		$data['error_code']=0;
		$data = array(
		    'host'=>$this->input->post('host'),
		    'port'=>$this->input->post('port'),
		    'passwd'=>$this->input->post('passwd'),
		    'application_id'=>$this->input->post('application_id'),
                    'status'=>$this->input->post('status'),
                    'send_mail'=>$this->input->post('send_mail'),
                    'alarm_connections'=>$this->input->post('alarm_connections'),
                    'alarm_used_memory'=>$this->input->post('alarm_used_memory'),
                    'alarm_repl_status'=>$this->input->post('alarm_repl_status'),
                    'alarm_repl_delay'=>$this->input->post('alarm_repl_delay'),
                    'threshold_connections'=>$this->input->post('threshold_connections'),
                    'threshold_used_memory'=>$this->input->post('threshold_used_memory'),
                    'threshold_repl_delay'=>$this->input->post('threshold_repl_delay'),
	            'alarm_interval'=>$this->input->post('alarm_interval'),
	            'alarm_type'=>$this->input->post('alarm_type'),
	            'converge_type'=>$this->input->post('converge_type'),
	            'alarm_person'=>$this->input->post('alarm_person'),
	            'alarm_slow_querys'=>$this->input->post('alarm_slow_querys'),
	            'threshold_slow_querys'=>$this->input->post('threshold_slow_querys'),
	            'alarm_error_querys'=>$this->input->post('alarm_error_querys'),
	            'threshold_error_querys'=>$this->input->post('threshold_error_querys'),
		);
		$this->servers->update($data,$id);
                redirect(site_url('servers/index'));
            }
        }
        
        //$data["application"]=$this->app->get_total_record(); 
        $data["application"]=$this->app->get_total_record_usage();
	$record = $this->servers->get_record_by_id($id);
	if(!$id || !$record){
	    show_404();
	}
        else{
            $data['record']= $record;
        }
          
        $data["cur_nav"]="servers_edit";
        $this->layout->view("servers/edit",$data);
    }
    
    /**
     * 加入回收站
     */
    function delete($id){
        if($id){
            $data = array(
			'is_delete'=>1
            );
	    $this->servers->update($data,$id);
            redirect(site_url('servers/index'));
        }
    }
    
    /**
     * 恢复
     */
    function recover($id){
        if($id){
            $data = array(
		'is_delete'=>0
            );
	    $this->servers->update($data,$id);
            redirect(site_url('servers/trash'));
        }
    }  
    
    /**
     * 彻底删除
     */
    function forever_delete($id){
        if($id){
            //检查该数据是否是回收站数据
            $record = $this->servers->get_record_by_id($id);
            $is_delete = $record['is_delete'];
            if($is_delete==1){
                $this->servers->delete($id);
            }
            redirect(site_url('servers/trash'));
        }
        
    }
    
}

/* End of file servers.php */
/* Location: ./servers/controllers/servers.php */
