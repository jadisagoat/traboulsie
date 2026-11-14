<?
class Home_page extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->table="home_page_text";
$this->config->load("home_page");
if(!$this->session->userdata("user_id"))
redirect(base_url()."back_office/home");
if($this->session->userdata("level") == 0){
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"home page"));
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
if($this->fct->getonecell("priviledge","write_p",$cond) != 1)
redirect(base_url()."back_office/home/dashboard");
}
}

// one form with every text of the home page, built from config/home_page.php
public function index(){
$data["title"]="Home page";
$data["content"]="back_office/home_page/edit";
$data["info"]=$this->fct->get_home_page();
$data["sections"]=$this->config->item("home_page_sections");
$this->load->view("back_office/template",$data);
}

public function submit(){
$existing=array();
foreach($this->db->select("name")->get($this->table)->result_array() as $row) $existing[]=$row["name"];
foreach($this->config->item("home_page_sections") as $fields){
foreach($fields as $name => $field){
$_data=array("value"=>trim((string)$this->input->post($name)),"updated_date"=>date("Y-m-d H:i:s"));
if(in_array($name,$existing)){
$this->db->where("name",$name);
$this->db->update($this->table,$_data);
} else {
$_data["name"]=$name;
$this->db->insert($this->table,$_data);
}
}
}
$this->session->set_userdata("success_message","Information was updated successfully");
redirect(base_url()."back_office/home_page");
}

}
