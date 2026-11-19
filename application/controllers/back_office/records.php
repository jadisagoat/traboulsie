<?php
// "Pages & texts" of the control panel: the lists of config/site_records.php (sector pages, guides, banner cards,
// Arabic translations). One list, one form and one table per kind; the fields come from the config.
// Addresses: back_office/records/index/sectors, /add/sectors, /edit/sectors/5 ...
// Login and form tokens are checked for the whole back office in hooks/Security_guard.php
class Records extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->load->helper('o5');
$this->load->model('site_content');
// these screens are for the administrators of the site
if($this->session->userdata("level") == 0) redirect(base_url()."back_office/home/dashboard");
}

// the kind of list asked for, with its table ready
function _def($type)
{
$def=$this->site_content->def($type);
if(!$def) redirect(base_url()."back_office/home/dashboard");
if(!$this->site_content->table($type)) show_error("The table ".$def["table"]." could not be created. Run docs/restore_additions.sql in phpMyAdmin, then open this page again.");
return $def;
}

public function index($type=""){
$def=$this->_def($type);
$data["title"]=$def["title"];
$data["type"]=$type;
$data["def"]=$def;
$data["info"]=$this->site_content->rows($type);
$data["content"]="back_office/records/list";
$this->load->view("back_office/template",$data);
}

public function add($type=""){
$def=$this->_def($type);
$data["title"]="Add ".$def["one"];
$data["type"]=$type;
$data["def"]=$def;
$data["content"]="back_office/records/form";
$this->load->view("back_office/template",$data);
}

public function edit($type="",$id=0){
$def=$this->_def($type);
$info=$this->db->where("id",(int)$id)->where("deleted",0)->get($def["table"])->row_array();
if(!$info) redirect(base_url()."back_office/records/index/".$type);
$data["title"]="Edit ".$def["one"];
$data["type"]=$type;
$data["def"]=$def;
$data["id"]=(int)$id;
$data["info"]=$info;
$data["content"]="back_office/records/form";
$this->load->view("back_office/template",$data);
}

public function delete($type="",$id=0){
$def=$this->_def($type);
$this->db->where("id",(int)$id)->update($def["table"],array("deleted"=>1,"updated_date"=>date("Y-m-d H:i:s")));
$this->session->set_userdata("success_message","Information was deleted successfully");
redirect(base_url()."back_office/records/index/".$type);
}

public function delete_all($type=""){
$def=$this->_def($type);
if($this->input->post("check_option") == "delete_all"){
	foreach((array)$this->input->post("cehcklist") as $id){
		if((int)$id > 0) $this->db->where("id",(int)$id)->update($def["table"],array("deleted"=>1,"updated_date"=>date("Y-m-d H:i:s")));
	}
	$this->session->set_userdata("success_message","Informations were deleted successfully");
}
redirect(base_url()."back_office/records/index/".$type);
}

// the new order after a row was dragged
public function sorted($type=""){
$def=$this->_def($type);
$i=0;
foreach((array)$this->input->get("table-1") as $id){
	if((int)$id > 0) $this->db->where("id",(int)$id)->update($def["table"],array("sort_order"=>$i++));
}
}

// a video or a photo of the form, saved in uploads/site: its address, "" when no file was sent, FALSE when it is refused
function _upload($name)
{
if(empty($_FILES[$name]["name"]) || $_FILES[$name]["error"] == UPLOAD_ERR_NO_FILE) return "";
$file=$_FILES[$name];
if($file["error"] != UPLOAD_ERR_OK || !is_uploaded_file($file["tmp_name"]) || $file["size"] > 12 * 1024 * 1024) return FALSE;
$ext=strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));
if($ext == "jpeg") $ext="jpg";
$images=array("jpg"=>IMAGETYPE_JPEG,"png"=>IMAGETYPE_PNG,"webp"=>IMAGETYPE_WEBP);
if(isset($images[$ext])){
	// a real image of the kind its name says
	$size=@getimagesize($file["tmp_name"]);
	if(!$size || $size[2] != $images[$ext]) return FALSE;
}elseif($ext == "mp4" || $ext == "webm"){
	// a real video: mp4 files carry "ftyp" at byte 4, webm files start with the EBML mark
	$head=(string)@file_get_contents($file["tmp_name"],FALSE,NULL,0,12);
	if($ext == "mp4" ? substr($head,4,4) !== "ftyp" : substr($head,0,4) !== "\x1A\x45\xDF\xA3") return FALSE;
}else{
	return FALSE;
}
if(!is_dir("uploads/site")) @mkdir("uploads/site",0755,TRUE);
$path="uploads/site/".date("Ymd")."-".substr(md5(uniqid(mt_rand(),TRUE)),0,10).".".$ext;
return move_uploaded_file($file["tmp_name"],$path) ? $path : FALSE;
}

public function submit($type=""){
$def=$this->_def($type);
$id=(int)$this->input->post("id");
$old=$id ? $this->db->where("id",$id)->where("deleted",0)->get($def["table"])->row_array() : array();
if($id && !$old) redirect(base_url()."back_office/records/index/".$type);

$_data=array();
$errors=array();
foreach($def["fields"] as $name => $field){
	if($field[1] == "file"){
		$saved=$this->_upload($name);
		if($saved === FALSE) $errors[]=$field[0].": the file was refused. Send an mp4 or webm video, or a jpg, png or webp photo, up to 12 MB.";
		elseif($saved !== "") $_data[$name]=$saved;
		elseif($this->input->post("remove_".$name)) $_data[$name]="";
		elseif(!$id && trim((string)$this->input->post("path_".$name)) !== "") $_data[$name]=trim((string)$this->input->post("path_".$name));
		continue;
	}
	$value=trim((string)$this->input->post($name));
	if($field[1] == "slug") $value=o5_slug($value);
	if($field[1] == "choice" && !isset($field[3][$value])) $value=(string)key($field[3]);
	if($field[1] == "date" && !preg_match('/^\d{4}-\d{2}-\d{2}$/',$value)) $value=date("Y-m-d");
	$_data[$name]=$value;
	if(in_array($name,$def["required"]) && $value === "") $errors[]=$field[0].": this field is required.";
	// two pages cannot have the same address
	if($field[1] == "slug" && $value !== ""){
		$same=$this->db->where($name,$value)->where("deleted",0)->where("id !=",$id)->count_all_results($def["table"]);
		if($same) $errors[]=$field[0].": another ".$def["one"]." already uses \"".$value."\".";
	}
}
if(count($errors)){
	$data["title"]=($id ? "Edit " : "Add ").$def["one"];
	$data["type"]=$type;
	$data["def"]=$def;
	if($id) $data["id"]=$id;
	$data["info"]=$_data + (array)$old;
	$data["errors"]=$errors;
	$data["content"]="back_office/records/form";
	$this->load->view("back_office/template",$data);
	return;
}
$_data["updated_date"]=date("Y-m-d H:i:s");
if($id){
	$this->db->where("id",$id)->update($def["table"],$_data);
}else{
	$_data["created_date"]=$_data["updated_date"];
	$last=$this->db->select_max("sort_order")->where("deleted",0)->get($def["table"])->row_array();
	$_data["sort_order"]=(int)$last["sort_order"] + 1;
	$this->db->insert($def["table"],$_data);
}
$this->session->set_userdata("success_message","Information was saved successfully");
redirect(base_url()."back_office/records/index/".$type);
}

}
