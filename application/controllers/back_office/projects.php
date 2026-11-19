<?php
// Completed projects shown in the "Our projects" section of the home page:
// a title, place and text in English (English page) and Arabic (/ar), a sector, and photos.
// Login and form tokens are checked for the whole back office in hooks/Security_guard.php
class Projects extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="projects";
$this->fct->projects_table();
}

// read / write / delete rights of a limited user on this section
function _priv($type)
{
if($this->session->userdata("level") != 0) return true;
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"projects"));
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
return $this->fct->getonecell("priviledge",$type."_p",$cond) == 1;
}

public function index(){
if(!$this->_priv("read")) redirect(base_url()."back_office/home/dashboard");
$data["title"]="List projects";
$data["content"]="back_office/projects/list";
$data["info"]=$this->fct->getAll($this->table,"sort_order");
$data["sectors"]=$this->fct->project_sectors();
$data["can_write"]=$this->_priv("write");
$data["can_delete"]=$this->_priv("delete");
$this->load->view("back_office/template",$data);
}

public function add(){
if(!$this->_priv("write")) redirect(base_url()."back_office/home/dashboard");
$data["title"]="Add project";
$data["content"]="back_office/projects/add";
$data["sectors"]=$this->fct->project_sectors();
$this->load->view("back_office/template",$data);
}

public function edit($id){
if(!$this->_priv("write")) redirect(base_url()."back_office/home/dashboard");
$data["title"]="Edit project";
$data["content"]="back_office/projects/add";
$data["id"]=(int)$id;
$data["info"]=$this->fct->getonerecord($this->table,array("id_projects"=>(int)$id));
$data["sectors"]=$this->fct->project_sectors();
$this->load->view("back_office/template",$data);
}

// deleted projects go to the recycle bin; their photos stay until the project is removed from there
public function delete($id){
if($this->_priv("delete")){
	$this->db->where("id_projects",(int)$id);
	$this->db->update($this->table,array("deleted"=>1,"deleted_date"=>date("Y-m-d H:i:s")));
	$this->session->set_userdata("success_message","Information was deleted successfully");
}
redirect(base_url()."back_office/projects");
}

public function delete_all(){
$list=(array)$this->input->post("cehcklist");
if($this->_priv("delete") && $this->input->post("check_option") == "delete_all"){
	foreach($list as $id){
		if((int)$id < 1) continue;
		$this->db->where("id_projects",(int)$id);
		$this->db->update($this->table,array("deleted"=>1,"deleted_date"=>date("Y-m-d H:i:s")));
	}
	$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/projects");
}

public function sorted(){
if(!$this->_priv("write")) return;
$sort=array();
foreach((array)$this->input->get("table-1") as $val){
	if(!empty($val)) $sort[]=(int)$val;
}
foreach($sort as $i => $id){
	$this->db->where("id_projects",$id);
	$this->db->update($this->table,array("sort_order"=>$i));
}
}

// a title in English, in Arabic, or both
public function one_title($title){
if(trim((string)$title) !== "" || trim((string)$this->input->post("title_ar")) !== "") return true;
$this->form_validation->set_message("one_title","Write the title in English, in Arabic, or both.");
return false;
}

public function valid_sector($sector){
if(isset($this->fct->project_sectors()[$sector])) return true;
$this->form_validation->set_message("valid_sector","Choose the sector of the project.");
return false;
}

// the files of the "photos[]" field, one $_FILES-style array per file
function _uploaded_files(){
$files=array();
if(empty($_FILES["photos"]["name"]) || !is_array($_FILES["photos"]["name"])) return $files;
foreach($_FILES["photos"]["name"] as $i => $name){
	if($_FILES["photos"]["error"][$i] == UPLOAD_ERR_NO_FILE) continue;
	$files[]=array(
		"name"=>$name,
		"tmp_name"=>$_FILES["photos"]["tmp_name"][$i],
		"error"=>$_FILES["photos"]["error"][$i],
		"size"=>$_FILES["photos"]["size"][$i],
	);
}
return $files;
}

public function submit(){
if(!$this->_priv("write")) redirect(base_url()."back_office/home/dashboard");
$id=(int)$this->input->post("id");
$old=$id ? $this->fct->getonerecord($this->table,array("id_projects"=>$id)) : array();
if($id && !$old) redirect(base_url()."back_office/projects");

$this->form_validation->set_rules("title","Title","trim|max_length[250]|callback_one_title");
$this->form_validation->set_rules("title_ar","Arabic title","trim|max_length[250]");
$this->form_validation->set_rules("sector","Sector","trim|required|callback_valid_sector");
$this->form_validation->set_rules("place","Place","trim|max_length[150]");
$this->form_validation->set_rules("place_ar","Arabic place","trim|max_length[150]");
$this->form_validation->set_rules("description","Text","trim|max_length[2000]");
$this->form_validation->set_rules("description_ar","Arabic text","trim|max_length[2000]");

// photos: keep the ones not ticked for removal, the chosen cover first, then add the new uploads
$photos=$old ? $this->fct->project_photos($old) : array();
$remove=(array)$this->input->post("remove_photo");
$cover=(string)$this->input->post("cover_photo");
$kept=array();
foreach($photos as $p){ if(!in_array($p,$remove,true)) $kept[]=$p; }
if($cover !== "" && in_array($cover,$kept,true)){ $kept=array_values(array_diff($kept,array($cover))); array_unshift($kept,$cover); }

$errors=array();
$new=array();
$valid=$this->form_validation->run();
if($valid){
	foreach($this->_uploaded_files() as $f){
		if(count($kept)+count($new) >= 30){ $errors[]=$f["name"]." (30 photos at most per project)"; continue; }
		$saved=$this->fct->project_save_photo($f);
		if($saved) $new[]=$saved; else $errors[]=$f["name"];
	}
	if(!count($kept) && !count($new)) $errors[]="Add at least one photo: the project shows on the website only with photos.";
}
if(!$valid){
	$data["title"]="Add / Edit project";
	if($id){ $data["id"]=$id; $data["info"]=$old; }
	$data["sectors"]=$this->fct->project_sectors();
	$data["content"]="back_office/projects/add";
	$this->load->view("back_office/template",$data);
	return;
}

$_data=array(
	"title"=>$this->input->post("title"),
	"title_ar"=>$this->input->post("title_ar"),
	"sector"=>$this->input->post("sector"),
	"place"=>$this->input->post("place"),
	"place_ar"=>$this->input->post("place_ar"),
	"description"=>$this->input->post("description"),
	"description_ar"=>$this->input->post("description_ar"),
	"photos"=>json_encode(array_merge($kept,$new)),
);
if($id){
	$_data["updated_date"]=date("Y-m-d H:i:s");
	$this->db->where("id_projects",$id);
	$this->db->update($this->table,$_data);
	foreach($photos as $p){ if(in_array($p,$remove,true)) $this->fct->project_delete_photo($p); }
}else{
	$_data["created_date"]=date("Y-m-d H:i:s");
	$this->db->insert($this->table,$_data);
	$id=$this->db->insert_id();
}
if(count($errors)){
	$this->session->set_userdata("error_message","The project was saved. These photos were not added (only jpg, png or webp images up to 15 MB): ".implode(", ",$errors));
	redirect(base_url()."back_office/projects/edit/".$id);
}
$this->session->set_userdata("success_message","Information was saved successfully");
redirect(base_url()."back_office/projects");
}

}
