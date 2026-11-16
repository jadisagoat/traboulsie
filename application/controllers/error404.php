<?
class Error404 extends CI_Controller{
	
public function index(){
set_status_header(404); // a real 404 for Google, not a page that looks missing
$this->load->view('404');	
}
}