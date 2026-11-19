<?php
// Videos shown on the home page: a Facebook or YouTube link per video, with a title and text in English (English page) and Arabic (/ar).
// Login and form tokens are checked for the whole back office in hooks/Security_guard.php
class Videos extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="videos";
$this->fct->videos_table();
}

// read / write / delete rights of a limited user on this section
function _priv($type)
{
if($this->session->userdata("level") != 0) return true;
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"videos"));
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
return $this->fct->getonecell("priviledge",$type."_p",$cond) == 1;
}

public function index(){
if(!$this->_priv("read")) redirect(base_url()."back_office/home/dashboard");
$data["title"]="List videos";
$data["content"]="back_office/videos/list";
$data["info"]=$this->fct->getAll($this->table,"sort_order");
$data["can_write"]=$this->_priv("write");
$data["can_delete"]=$this->_priv("delete");
$this->load->view("back_office/template",$data);
}

public function add(){
if(!$this->_priv("write")) redirect(base_url()."back_office/home/dashboard");
$data["title"]="Add video";
$data["content"]="back_office/videos/add";
$this->load->view("back_office/template",$data);
}

public function edit($id){
if(!$this->_priv("write")) redirect(base_url()."back_office/home/dashboard");
$data["title"]="Edit video";
$data["content"]="back_office/videos/add";
$data["id"]=(int)$id;
$data["info"]=$this->fct->getonerecord($this->table,array("id_videos"=>(int)$id));
$this->load->view("back_office/template",$data);
}

public function delete($id){
if($this->_priv("delete")){
	$this->db->where("id_videos",(int)$id);
	$this->db->update($this->table,array("deleted"=>1,"deleted_date"=>date("Y-m-d H:i:s")));
	$this->session->set_userdata("success_message","Information was deleted successfully");
}
redirect(base_url()."back_office/videos");
}

public function delete_all(){
$list=(array)$this->input->post("cehcklist");
if($this->_priv("delete") && $this->input->post("check_option") == "delete_all"){
	foreach($list as $id){
		if((int)$id < 1) continue;
		$this->db->where("id_videos",(int)$id);
		$this->db->update($this->table,array("deleted"=>1,"deleted_date"=>date("Y-m-d H:i:s")));
	}
	$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/videos");
}

public function sorted(){
if(!$this->_priv("write")) return;
$sort=array();
foreach((array)$this->input->get("table-1") as $val){
	if(!empty($val)) $sort[]=(int)$val;
}
foreach($sort as $i => $id){
	$this->db->where("id_videos",$id);
	$this->db->update($this->table,array("sort_order"=>$i));
}
}

// the link must open a Facebook or YouTube video
public function valid_video($url){
if($this->fct->video_embed($url)) return true;
$this->form_validation->set_message("valid_video","Paste the link of a Facebook or YouTube video (for example https://www.facebook.com/traboulsi.est/videos/123456789).");
return false;
}

// a title in English, in Arabic, or both
public function one_title($title){
if(trim((string)$title) !== "" || trim((string)$this->input->post("title_ar")) !== "") return true;
$this->form_validation->set_message("one_title","Write the title in English, in Arabic, or both.");
return false;
}

public function submit(){
if(!$this->_priv("write")) redirect(base_url()."back_office/home/dashboard");
$id=(int)$this->input->post("id");
$this->form_validation->set_rules("title","Title","trim|max_length[250]|callback_one_title");
$this->form_validation->set_rules("title_ar","Arabic title","trim|max_length[250]");
$this->form_validation->set_rules("video_url","Video link","trim|required|max_length[500]|callback_valid_video");
$this->form_validation->set_rules("description","Text","trim|max_length[1000]");
$this->form_validation->set_rules("description_ar","Arabic text","trim|max_length[1000]");
if($this->form_validation->run() == FALSE){
	$data["title"]="Add / Edit video";
	if($id){
		$data["id"]=$id;
		$data["info"]=$this->fct->getonerecord($this->table,array("id_videos"=>$id));
	}
	$data["content"]="back_office/videos/add";
	$this->load->view("back_office/template",$data);
	return;
}
$_data=array(
	"title"=>$this->input->post("title"),
	"video_url"=>$this->input->post("video_url"),
	"title_ar"=>$this->input->post("title_ar"),
	"description"=>$this->input->post("description"),
	"description_ar"=>$this->input->post("description_ar"),
);
if($id){
	$_data["updated_date"]=date("Y-m-d H:i:s");
	$this->db->where("id_videos",$id);
	$this->db->update($this->table,$_data);
	$this->session->set_userdata("success_message","Information was updated successfully");
}else{
	$_data["created_date"]=date("Y-m-d H:i:s");
	$this->db->insert($this->table,$_data);
	$this->session->set_userdata("success_message","Information was inserted successfully");
}
redirect(base_url()."back_office/videos");
}

}
