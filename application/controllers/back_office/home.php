<?
class Home extends CI_Controller{
public function __construct()
{
parent::__construct();
// Your own constructor code
//$this->headers='back_office/includes/header';
//$this->footer='back_office/includes/footer';

}



public function index(){
$this->load->view('back_office/login');
/*	$data=array(
	'pg_title'=>'Home Page',
	'title'=>'DashBoard',
	'header' => $this->headers,
	'content' =>'back_office/dashboard',
	'footer'=>$this->footer );
	$this->load->view('back_office/template',$data);*/
}

public function login_validate(){
$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|xss_clean');
$this->form_validation->set_rules('pass', 'Password', 'trim|required|min_length[5]|max_length[12]|xss_clean');
if ($this->form_validation->run() == FALSE){
 $this->load->view('back_office/login'); 
}else{
$username= $this->input->post('username');
$password=$this->input->post('pass');
$this->db->where('user_name',$username);
$this->db->where('password',$password);
$query=$this->db->get('admin'); 
$admin=$query->row_array();

if($query->num_rows() >0 ){
$this->session->set_userdata('user_id',$admin["admin_id"]);
$this->session->set_userdata('user_name',$admin["admin_name"]);
$this->session->set_userdata('level',$admin["level"]);
$this->session->set_userdata('login_date',date("d M Y - h:i:s A"));
redirect(base_url().'back_office/home/dashboard');	 	
} else{
// check in user table 
$this->db->where('username',$username);
$this->db->where('password',$password);
$this->db->where('deleted',0);
$query=$this->db->get('user');
$user=$query->row_array();
if($query->num_rows() >0 ){
$this->session->set_userdata('user_id',$user["id_user"]);
$this->session->set_userdata('user_name',$user["username"]);
$this->session->set_userdata('level',0);
$this->session->set_userdata('login_date',date("d M Y - h:i:s A"));
redirect(base_url().'back_office/home/dashboard');	
} 
else{
$this->session->set_userdata('login_error','check your username or password .');
$this->load->view('back_office/login'); 	
}
}

}
}
public function dashboard(){
$data["title"] = "Admin Section";
$data["content"]="back_office/dashboard";
$data["content_type"]=$this->fct->getAll('content_type','sort_order'); 

// counts only: the table holds tens of thousands of messages
$data["messages_count"]=$this->db->where('deleted',0)->count_all_results('contactform');
$data["new_message_count"]=$this->db->where('deleted',0)->where('readed',0)->count_all_results('contactform');
$data["users"]=$this->fct->getAll_orderdate('user');


$this->load->view('back_office/template', $data);	
}

public function message_popup($id){
$cond=array('id'=>$id);
$do_it=$this->fct->getonerow('contactform',$cond);
echo '<div style="height:auto;"><h2 style="font-size:160%; font-weight:bold; margin:10px 0;">'.$do_it["subject"].'&nbsp;&nbsp;<span style="color:#AE9A62">Date:'.$do_it["created_date"].'</span> </h2>
<p>'.$do_it["message"].'</p></div>';	
}

public function logout(){
$this->session->sess_destroy();
redirect(base_url().'back_office/home');	 
}
	
	
}