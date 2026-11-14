<?
class Events extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="events";
}


public function index(){
$data["title"]="List Events";
$data["content"]="back_office/events/list";
$data["info"]=$this->fct->getAll($this->table,'sort_order');
$this->load->view('back_office/template',$data);
}

public function add(){
$data["title"]="Add Events";
$data["content"]="back_office/events/add";
$this->load->view('back_office/template',$data);
} 

public function edit($id){
$data["title"]="Edit Event";
$data["content"]="back_office/events/add";
$cond=array('id_events'=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view('back_office/template',$data);
}

public function delete($id){
$_data=array('deleted'=>1,
'deleted_date'=>date("Y-m-d h:i:s"));
$this->db->where('id_events',$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata('success_message','Information was deleted successfully');
redirect(base_url().'back_office/events');
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
$this->db->where('id_events',$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata('success_message','Informations were deleted successfully');
}
redirect(base_url().'back_office/events');	
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
$this->db->where('id_events',$sort[$i]);
$this->db->update($this->table,$_data);	
}
}


public function submit(){
$this->form_validation->set_rules('title', 'Event Title', 'trim|required');
$this->form_validation->set_rules('description', 'description', 'trim');
$this->form_validation->set_rules('date', 'Date', 'trim|required');

if ($this->form_validation->run() == FALSE){
$data["title"]="Add Events";
if($this->input->post("id")!=""){
$cond=array('id_events'=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord($this->table,$cond);
}
$data["content"]="back_office/events/add";
$this->load->view('back_office/template',$data);
}
else
{
//	$this->uploadImage();
$date=$this->fct->date_in_formate($this->input->post('date'));
$_data=array(
'title'=>$this->input->post('title'),
'description'=>$this->input->post('description'),
'date'=>$date);
if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_events"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("events","image",$cond_image);
if(!empty($old_image)){
unlink("./uploads/events/".$old_image);	
//unlink("./uploads/events/32x32/".$old_image);	 
}								
}
$image1= $this->fct->uploadImage("image","events");
//$this->fct->createthumb($image1,"events","32x32"); 
$_data["image"]=$image1;	
}

if($this->input->post('id')!=''){
$_data["updated_date"]=date("Y-m-d h:i:s");
$this->db->where('id_events',$this->input->post('id'));
$this->db->update($this->table,$_data);
$this->session->set_userdata('success_message','Information was updated successfully');
} else {

$_data["created_date"]=date("Y-m-d h:i:s");
$this->db->insert($this->table,$_data); 	
$this->session->set_userdata('success_message','Information was inserted successfully');
}
redirect(base_url().'back_office/events');
}
}

public function delete_image($field,$image,$id){
unlink("./uploads/events/".$image);
//unlink("./uploads/content_type/32x32/".$image);									
$_data[$field]="";
$this->db->where("id_events",$id);
$this->db->update("events",$_data);
redirect(base_url()."back_office/events");	
}

}