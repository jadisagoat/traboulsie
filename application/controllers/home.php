<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {

// the home page (English); the Arabic page is yoursite/ar
public function index()
{
$this->_render($this->_page_data(),base_url());
}

// the old preview links of the removed designs 1 to 4 now lead to the home page
public function old_design()
{
redirect(base_url(),'location',301);
}

// the bilingual design: yoursite/option5 (English) and yoursite/option5/ar (Arabic)
public function option5($lang='en')
{
$lang=$lang=='ar' ? 'ar' : 'en';
$this->_render($this->_page_data(),base_url().($lang=='ar' ? 'ar' : 'option5'),$lang);
}

// the Arabic page of the website
public function ar()
{
$this->_render($this->_page_data(),base_url().'ar','ar');
}

function _render($data,$page_url,$lang='en')
{
$data['page_url']=$page_url;
$data['lang']=$lang;
$data['content']='option5/home';
$this->load->view('option5/template',$data);
}

// everything both designs show, and the contact form
function _page_data()
{
$cond=array('admin_id'=>2);
$data['generalinfo'] = $this->fct->getonerow("admin",$cond);
$data['about_us'] = $this->fct->getonerow("about_us",array('id_about_us'=>1));
$data['home_page'] = $this->fct->get_home_page();
$data['banner']=$this->fct->getAll("banner","sort_order");
$data['categories']=$this->fct->getAll('category','sort_order'); 
$data['products']=$this->fct->getAll('products','sort_order'); 
$data['videos']=$this->fct->get_videos();
/*$data['menuactive'] ="home";
$data['tab']=0;
$data['countries']=$this->fct->getAll("countries","title");*/
//search
//$sqla="SELECT our_clients.id_our_clients,our_clients.id_country,our_clients.title,country.title as ccountry,our_clients.description FROM our_clients,country where 1 ";
//$sqla.=" AND our_clients.id_country=country.id_country AND our_clients.deleted=0 ";

 $sqla= "SELECT our_clients.id_our_clients,our_clients.id_country,our_clients.title,country.title as ccountry,our_clients.description FROM our_clients,country where 1 AND our_clients.id_country=country.id_country AND our_clients.deleted=0
 ORDER BY our_clients.`sort_order`";

$data['client_groups']=$this->_group_clients($this->fct->search($sqla));
$condition1=array('id_contact_us'=>1);
$data['contact_us'] = $this->fct->getonerecord("contact_us",$condition1);

/* data for the 2026 theme */
$data['hero_images']=$this->_hero_images($data['banner']);
$data['locations']=preg_split('/\s*\R\s*/u',trim(isset($data['contact_us']['locations']) ? (string)$data['contact_us']['locations'] : ''),-1,PREG_SPLIT_NO_EMPTY);
$data['contact_items']=$this->_parse_contact($data['contact_us']['description']);
$data['contact']=array('phone'=>'','phone_href'=>'','mobile'=>'','mobile_href'=>'','email'=>'','whatsapp'=>'');
foreach($data['contact_items'] as $item){
	$label=strtolower($item['label']);
	if($item['type']=='mailto'){
		if($data['contact']['email']=='') $data['contact']['email']=$item['value'];
	}elseif(strpos($label,'mobile')!==false){
		$data['contact']['mobile']=$item['value'];
		$data['contact']['mobile_href']=$item['href'];
	}elseif(strpos($label,'fax')===false && $data['contact']['phone']==''){
		$data['contact']['phone']=$item['value'];
		$data['contact']['phone_href']=$item['href'];
	}
}
$data['contact']['whatsapp']=preg_replace('/\D/','',$data['contact']['mobile']);

// products ordered by category, only for categories that exist
$by_cat=array();
foreach($data['products'] as $product){
	if(is_file("uploads/products/290x220/".$product['image']))
		$by_cat[$product['id_category']][]=$product;
}
$data['products']=array();
$data['cat_counts']=array();
foreach($data['categories'] as $category){
	$items=isset($by_cat[$category['id_category']]) ? $by_cat[$category['id_category']] : array();
	$data['cat_counts'][$category['id_category']]=count($items);
	foreach($items as $k=>$item){
		$items[$k]['category']=trim(html_entity_decode(strip_tags($category['title']),ENT_QUOTES,'UTF-8'));
		$items[$k]['alt']=$this->_product_alt($item,$items[$k]['category']);
		$items[$k]['text_en']=$this->_desc_part($item['description'],'en');
		$items[$k]['text_ar']=$this->_desc_part($item['description'],'ar');
	}
	$data['products']=array_merge($data['products'],$items);
}

/* contact us */
$data['msg_thanks'] = '';
$data['valid'] = "not1";
$this->load->library('form_validation');
$this->form_validation->set_rules("name", "Name", "required|callback_validation1");
$this->form_validation->set_rules("email", "Email", "trim|required|valid_email|callback_validation3");
$this->form_validation->set_rules("company", "Company", "required|callback_validation2");
$this->form_validation->set_rules("telephone", "Telephone", "trim|required|callback_validation3");
$this->form_validation->set_rules("message", "Message", "trim|required|callback_validation4");
$this->form_validation->set_rules('subject', 'Subject', 'trim'); 
$_data["name"]=$this->input->post("name");
$_data["subject"]=$this->input->post("subject");
$_data["message"]=$this->input->post("message");


$_data["email"]=$this->input->post("email");
$_data["company"]=$this->input->post("company"); 
$_data["telephone"]=$this->input->post("telephone");
$_data["created_date"]=date("Y-m-d h:i:s");
if ($this->form_validation->run() == FALSE)
  {
    $data['valid'] = "not";
   //$this->load->view('contact',$data);
 }
  else
  { 
   $this->db->insert('contactform',$_data);  
   $data['valid'] = "true";

//$condition1=array('id_contact_us'=>1);
//$data['msg_thanks'] = $this->fct->getonerecord("contact_us",$condition1);

$data['msg_thanks'] ="Thanks send email";
// the visitor's words are shown as text in the email, never as HTML
$h=array();
foreach($_data as $k=>$v) $h[$k]=htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');
$m = "<div style='color:darkblue'><b>The following user has submitted the contact form</b></div><br>";
 
$m .= "<table border='3' cellspacing='0' cellpadding='5'>
<tr><td>Name </td><td>".$h["name"]."</td></tr>
<tr><td>Email </td><td>".$h["email"]."</td></tr>
<tr><td>Telephone </td><td>".$h["telephone"]."</td></tr>
<tr><td>Subject </td><td>".$h["subject"]."</td></tr>
<tr><td>Company </td><td>".$h["company"]."</td></tr>
<tr><td>Message </td><td>".$h["message"]."</td></tr>
<tr><td>Date </td><td>".$h["created_date"]."</td></tr>
</table>";
 
 
$this->load->library('email');
$condition=array('admin_id'=>2);
$admin_data = $this->fct->getonecell("admin","email",$condition);
// sent from the company address (the SMTP mailbox when set in config/email.php), the visitor goes in "reply to"
$from=$this->email->smtp_user!='' ? $this->email->smtp_user : $admin_data;
$config= Array(
'mailtype'  => 'html'
);  
$this->email->initialize($config);
$this->email->set_newline("\r\n");
$this->email->from($from, 'Traboulsi website');
$this->email->reply_to($_data["email"], $_data["name"]);
$this->email->to($admin_data);
$this->email->subject('Contact form');
$this->email->message($m);
$this->email->send(); 
 
 
 
//$condition1=array('id_contact_us'=>1);
//$msg_ad = $this->fct->getonerecord("contact_us",$condition1);
//$m="<div style='color:darkblue'>".$msg_ad['description']."</div><br>";

$msg_ad ="Thanks send email";
$m="<div style='color:darkblue'>".$msg_ad."</div><br>";

$this->email->initialize($config);
$this->email->set_newline("\r\n");
$this->email->from($from, 'Traboulsi Est');
$this->email->to($_data["email"]);
$this->email->subject('Contact form');
$this->email->message($m);
$this->email->send();  
 
}

/*  finich contact us  */




















return $data;
}

// the alt text Google reads on a product photo: "Showcase Fridge 108 - Refrigeration & Cold Display | Traboulsi Est. Lebanon"
function _product_alt($product,$category)
{
$title=trim(html_entity_decode(strip_tags($product['title']),ENT_QUOTES,'UTF-8'));
return $title.($category!='' ? ' - '.$category : '').' | Traboulsi Est. Lebanon';
}

// the English (first paragraph) or Arabic (lang="ar" paragraph) text of a product description
function _desc_part($html,$lang)
{
$html=(string)$html;
if($lang=='ar'){
	if(!preg_match('#<p[^>]*lang="ar"[^>]*>(.*?)</p>#isu',$html,$m)) return '';
}else{
	if(!preg_match('#<p(?![^>]*lang="ar")[^>]*>(.*?)</p>#isu',$html,$m)) $m=array('',$html);
}
return trim(preg_replace('/\s+/u',' ',html_entity_decode(strip_tags($m[1]),ENT_QUOTES,'UTF-8')));
}

// banner images that exist on disk; falls back to any image in the banner folder
function _hero_images($banners)
{
$dir="uploads/banner/1950x960/";
$images=array();
foreach($banners as $banner){
	if($banner['image']!='' && is_file($dir.$banner['image']))
		$images[]=array('src'=>$dir.$banner['image'],'title'=>$banner['title']);
}
if(!count($images)){
	foreach((array)glob($dir."*.*") as $file){
		if(preg_match('/\.(jpe?g|png|webp)$/i',$file) && strpos(basename($file),'__')===false)
			$images[]=array('src'=>$file,'title'=>'Traboulsi Est');
	}
}
return $images;
}

// "<strong>Tel:</strong><a href="tel:...">...</a>" pairs from the contact_us description
function _parse_contact($html)
{
$items=array();
preg_match_all('#<strong>\s*(?:<span[^>]*>)?\s*([A-Za-z ]+?)\s*:\s*(?:</span>)?\s*</strong>\s*<a[^>]*href="(tel|mailto):([^"]+)"[^>]*>(.*?)</a>#is',$html,$matches,PREG_SET_ORDER);
foreach($matches as $m){
	$type=strtolower($m[2]);
	$value=trim(strip_tags($m[4]));
	$items[]=array(
		'label'=>trim($m[1]),
		'type'=>$type,
		'href'=>$type.':'.trim($m[3]),
		'value'=>$type=='tel' ? $this->_format_phone($value) : $value
	);
}
return $items;
}

// +9617530986 -> +961 7 530 986
function _format_phone($phone)
{
$digits=preg_replace('/\D/','',$phone);
if(strpos($digits,'961')===0){
	$local=substr($digits,3);
	if(strlen($local)==7) return '+961 '.substr($local,0,1).' '.substr($local,1,3).' '.substr($local,4);
	if(strlen($local)==8) return '+961 '.substr($local,0,2).' '.substr($local,2,3).' '.substr($local,5);
}
return $phone;
}

// clients grouped by country, with the <li> names of each description extracted
function _group_clients($rows)
{
$groups=array();
foreach($rows as $row){
	preg_match_all('#<li[^>]*>(.*?)</li>#is',$row['description'],$m);
	$names=array();
	foreach($m[1] as $li){
		$name=trim(str_replace("\xC2\xA0",' ',html_entity_decode(strip_tags($li),ENT_QUOTES,'UTF-8')));
		if($name!='') $names[]=$name;
	}
	$row['names']=$names;
	$groups[$row['ccountry']][]=$row;
}
return $groups;
}



}

// the role of a branch, shown under its name in the contact section of every design
function branch_role($location)
{
	if(preg_match('/zahrani|الزهراني/iu', $location)) return 'Main factory and showroom';
	if(preg_match('/khald|خلدة/iu', $location)) return 'Serving the greater Beirut region';
	return '';
}

