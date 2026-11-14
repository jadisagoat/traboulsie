<?
class Our_clients extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="our_clients";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List our clients";
$data["content"]="back_office/our_clients/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add our clients";
$data["content"]="back_office/our_clients/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit our clients";
$data["content"]="back_office/our_clients/add";
$cond=array("id_our_clients"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_our_clients",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/our_clients");
}

public function delete_all(){
$cehcklist= $this->input->post("cehcklist");
$check_option= $this->input->post("check_option");
if($check_option == "delete_all"){
if(count($cehcklist) > 0){
for($i = 0; $i < count($cehcklist); $i++){
if($cehcklist[$i] != ""){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_our_clients",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/our_clients");	
}

public function sorted(){
$sort=array();
foreach($this->input->get("table-1") as $key => $val){
if(!empty($val))
$sort[]=$val;	
}
$i=0;
for($i=0; $i<count($sort); $i++){
$_data=array("sort_order"=>$i);
$this->db->where("id_our_clients",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit our clients";
$this->form_validation->set_rules("title", "title", "trim");
$this->form_validation->set_rules("country", "country", "trim");
$this->form_validation->set_rules("description", "description", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_our_clients"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("our_clients",$cond); }
$data["content"]="back_office/our_clients/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_country"]=$this->input->post("country"); 
$_data["description"]=$this->input->post("description");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_our_clients",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/our_clients");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/our_clients/".$image)){
unlink("./uploads/our_clients/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'our clients')
AND name = ".$this->db->escape($field);
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/our_clients/".$value."/".$image)){
unlink("./uploads/our_clients/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_our_clients",$id);
$this->db->update("our_clients",$_data);
redirect(base_url()."back_office/our_clients");	
}

}