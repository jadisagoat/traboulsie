<?
class Products extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="products";
}


public function index($order=""){
if($order == "")
$order ="sort_order";
$data["title"]="List products";
$data["content"]="back_office/products/list";
$data["info"]=$this->fct->getAll($this->table,$order);
$this->load->view("back_office/template",$data);
}

public function add(){
$data["title"]="Add products";
$data["content"]="back_office/products/add";
$this->load->view("back_office/template",$data);
} 

public function edit($id){
$data["title"]="Edit products";
$data["content"]="back_office/products/add";
$cond=array("id_products"=>$id);
$data["id"]=$id;
$data["info"]=$this->fct->getonerecord($this->table,$cond);
$this->load->view("back_office/template",$data);
}

public function delete($id){
$_data=array("deleted"=>1,
"deleted_date"=>date("Y-m-d h:i:s"));
$this->db->where("id_products",$id);
$this->db->update($this->table,$_data);
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/products/index");
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
$this->db->where("id_products",$cehcklist[$i]);
$this->db->update($this->table,$_data);	
}
} } 
$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/products/index");
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
$this->db->where("id_products",$sort[$i]);
$this->db->update($this->table,$_data);	
}
}

public function submit(){
$data["title"]="Add / Edit products";
$this->form_validation->set_rules("title", "title", "trim|required");
$this->form_validation->set_rules("category", "category", "trim|required");
$this->form_validation->set_rules("image", "image", "trim");
$this->form_validation->set_rules("description", "description", "trim");
if ($this->form_validation->run() == FALSE){
if($this->input->post("id")!=""){
$cond=array("id_products"=>$this->input->post("id"));
$data["id"]=$this->input->post("id");
$data["info"]=$this->fct->getonerecord("products",$cond); }
$data["content"]="back_office/products/add";
$this->load->view("back_office/template",$data);
}
else
{
$_data["title"]=$this->input->post("title");
$_data["id_category"]=$this->input->post("category"); 
if(!empty($_FILES["image"]["name"])) {
if($this->input->post("id")!=""){
$cond_image=array("id_products"=>$this->input->post("id"));
$old_image=$this->fct->getonecell("products","image",$cond_image);
if(!empty($old_image) && file_exists('./uploads/products/'.$old_image)){
unlink("./uploads/products/".$old_image);
$sumb_val1=explode(",","290x220,600x455");
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/products/".$value."/".$old_image)){
unlink("./uploads/products/".$value."/".$old_image);	 }							
} 
 } }
$image1= $this->fct->uploadImage("image","products");
$this->fct->createthumb1($image1,"products","290x220,600x455");$_data["image"]=$image1;	
}
$_data["description"]=$this->input->post("description");

	if($this->input->post("id")!=""){
	$_data["updated_date"]=date("Y-m-d h:i:s");
	$this->db->where("id_products",$this->input->post("id"));
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
	} else {
	$_data["created_date"]=date("Y-m-d h:i:s");
	$this->db->insert($this->table,$_data); 	
	$this->session->set_userdata("success_message","Information was inserted successfully");
	}
	redirect(base_url()."back_office/products/index");
}
	
}

public function delete_image($field,$image,$id){
if(file_exists("./uploads/products/".$image)){
unlink("./uploads/products/".$image); }
$q=" SELECT thumb,thumb_val
FROM `content_type_attr`
WHERE id_content = (SELECT id_content FROM `content_type` WHERE name = 'products')
AND name = ".$this->db->escape($field);
$query=$this->db->query($q);
$res=$query->row_array();
if($res["thumb"] == 1){
$sumb_val1=explode(",",$res["thumb_val"]);
foreach($sumb_val1 as $key => $value){
if(file_exists("./uploads/products/".$value."/".$image)){
unlink("./uploads/products/".$value."/".$image);	 }								
} } 
$_data[$field]="";
$this->db->where("id_products",$id);
$this->db->update("products",$_data);
redirect(base_url()."back_office/products");	
}

}