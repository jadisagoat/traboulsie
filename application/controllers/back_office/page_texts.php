<?php
// "Pages & texts" > "Texts of the pages": the single texts of the website that are not part of the home page form:
// the pages of the menu (yoursite/about, /faq ...), the block "Why Traboulsi Est." and the page yoursite/export.
// The fields and their built-in texts come from models/site_content.php (page_text_sections); they are stored in
// home_page_text like the texts of the home page. A field left empty shows its built-in text again.
class Page_texts extends CI_Controller{

public function __construct()
{
parent::__construct();
$this->load->helper('o5');
$this->load->model('site_content');
if($this->session->userdata("level") == 0) redirect(base_url()."back_office/home/dashboard");
}

public function index(){
$data["title"]="Texts of the pages";
$data["content"]="back_office/home_page/edit";
$data["sections"]=$this->site_content->page_text_sections();
$data["info"]=$this->site_content->page_texts();
$data["form_action"]=base_url()."back_office/page_texts/submit";
$data["form_help"]="The texts of the pages of the menu, of the block <b>Why Traboulsi Est.</b> and of the page <b>Delivery &amp; export</b>, in English and in Arabic. A field left empty shows its original text again.";
$this->load->view("back_office/template",$data);
}

public function submit(){
$existing=array();
foreach($this->db->select("name")->get("home_page_text")->result_array() as $row) $existing[]=$row["name"];
foreach($this->site_content->page_text_sections() as $fields){
foreach($fields as $name => $field){
$_data=array("value"=>trim((string)$this->input->post($name)),"updated_date"=>date("Y-m-d H:i:s"));
if(in_array($name,$existing)){
$this->db->where("name",$name);
$this->db->update("home_page_text",$_data);
} else {
$_data["name"]=$name;
$this->db->insert("home_page_text",$_data);
}
}
}
$this->session->set_userdata("success_message","Information was updated successfully");
redirect(base_url()."back_office/page_texts");
}

}
