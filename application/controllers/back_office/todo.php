<?
class Todo extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="do_it";
}


public function index(){
$data["title"]="List To DO";
$data["content"]="back_office/todo/list";
$data["info"]=$this->fct->getAll($this->table,'Deadline');
$this->load->view('back_office/template',$data);
}

public function add(){
$data["title"]="Add To DO";
$data["content"]="back_office/todo/add";
$this->load->view('back_office/template',$data);
} 

public function edit($id){
$data["title"]="Edit To DO";
$data["content"]="back_office/todo/add";
$cond=array('id_do'=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view('back_office/template',$data);
}

public function delete($id){
$_data=array('deleted'=>1,
'deleted_date'=>date("Y-m-d h:i:s"));
$this->db->where('id_do',$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata('success_message','Information was deleted successfully');
redirect(base_url().'back_office/todo');
}

public function delete_all(){
$cehcklist= $this->input->post('cehcklist');
$check_option= $this->input->post('check_option');
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ''){
$_data=array('deleted'=>1,
'deleted_date'=>date("Y-m-d h:i:s"));
$this->db->where('id_do',$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata('success_message','Informations were deleted successfully');
}
redirect(base_url().'back_office/todo');	
}

public function sorted(){
$sort=array();
foreach($this->input->get('table-1') as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array('sort_order'=>$i);
$this->db->where('id_do',$sort[$i]);
$this->db->update($this->table,$_data);	
}
}


public function submit(){
$this->form_validation->set_rules('title', 'To Do Title', 'trim|required');
$this->form_validation->set_rules('description', 'Description', 'trim');
$this->form_validation->set_rules('Deadline', 'Deadline', 'trim|required');
if ($this->form_validation->run() == FALSE){
$data["title"]="Add To Do";
if($this->input->post('id')!=''){
$cond=array('id_do'=>$this->input->post('id'));
$data["id"]=$this->input->post('id');
$data["info"]=$this->fct->getonerecord($this->table,$cond);
}
$data["content"]="back_office/todo/add";
$this->load->view('back_office/template',$data);
}
else
{
	list($m,$d,$y)=explode('/',$this->input->post('Deadline'));
	$Deadline=$y."-".$m."-".$d;
	$_data=array(
	'title'=>$this->input->post('title'),
	'description'=>$this->input->post('description'),
	'Deadline'=>$Deadline);
	if($this->input->post('id')!=''){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where('id_do',$this->input->post('id'));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata('success_message','Information was updated successfully');
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata('success_message','Information was inserted successfully');
	}
	redirect(base_url().'back_office/todo');
}
}


}