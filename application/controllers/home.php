<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {

public function __construct()
{
parent::__construct();
$this->load->helper('o5');
$this->load->model('site_content'); // sector pages, guides, banner cards, translations and page texts of the control panel
}

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

// the photo page (yoursite/photos, yoursite/ar/photos) and the video page (yoursite/videos, yoursite/ar/videos)
public function gallery($page='photos',$lang='en')
{
$page=$page=='videos' ? 'videos' : 'photos';
$lang=$lang=='ar' ? 'ar' : 'en';
$data=$this->_page_data();
$data['page']=$page;
$data['page_path']=$page;
$titles=array(
	'photos'=>array('en'=>array('Photos | Traboulsi Est. - commercial kitchen equipment, Lebanon','Photos of the Traboulsi Est. showroom, completed projects and products: refrigerators, cooking lines and stainless-steel work made in Lebanon.'),
		'ar'=>array('الصور | مؤسسة طرابلسي - تجهيز المطابخ والمطاعم في لبنان','صور معرض مؤسسة طرابلسي ومشاريعها المنجزة ومنتجاتها: برادات، خطوط طبخ وأعمال ستانلس ستيل مصنّعة في لبنان.')),
	'videos'=>array('en'=>array('Videos | Traboulsi Est. - commercial kitchen equipment, Lebanon','Videos of Traboulsi Est.: installations, new equipment and our workshop in Deir El Zahrani, Lebanon.'),
		'ar'=>array('الفيديو | مؤسسة طرابلسي - تجهيز المطابخ والمطاعم في لبنان','فيديوهات مؤسسة طرابلسي: تركيبات، معدات جديدة ومشغلنا في دير الزهراني، لبنان.')),
);
$data['page_title']=$titles[$page][$lang][0];
$data['page_description']=$titles[$page][$lang][1];
$this->_render($data,base_url().($lang=='ar' ? 'ar/' : '').$page,$lang);
}

// ---------- One page per sector, per category and per product (texts in config/sectors.php) ----------

// yoursite/restaurant-equipment, yoursite/ar/restaurant-equipment ...
public function sector($slug='',$lang='en')
{
$lang=$this->_lang($lang);
$sectors=$this->_sectors();
if(!isset($sectors[$slug])) return $this->_missing();
$data=$this->_page_data();
$sector=$sectors[$slug];
$t=$sector[$lang];
// its products: at most two of each type, the types in turn
$by_type=array();
foreach($data['products'] as $product){
	if(preg_match('/'.$sector['products'].'/i',$product['title'])) $by_type[$product['type']][]=$product;
}
$picked=array();
for($i=0;$i<2;$i++){
	foreach($by_type as $items){ if(isset($items[$i]) && count($picked)<16) $picked[]=$items[$i]; }
}
// its clients (Our clients) and its completed projects
$clients=array();
foreach($data['client_groups'] as $groups){
	foreach($groups as $group){
		if(preg_match('/'.$sector['clients'].'/i',$group['title'])) $clients=array_merge($clients,$group['names']);
	}
}
$projects=array();
foreach($data['projects'] as $project){ if($project['sector']==$sector['project']) $projects[]=$project; }
$url=o5_url($sector['path']);
$schema=array(
	array('@type'=>'Service','@id'=>$url.'#service','name'=>$t['h1'],'description'=>$t['description'],'url'=>$url,'serviceType'=>$t['name'],
		'provider'=>array('@id'=>base_url().'#organization'),'areaServed'=>array('@type'=>'Country','name'=>'Lebanon')),
	$this->_crumbs(array($t['name']=>$url)),
	$this->_faq($t['faq']),
);
$data['sector']=$sector;
$data['page_products']=$picked;
$data['sector_clients']=array_values(array_unique($clients));
$data['sector_projects']=$projects;
$this->_subpage($data,$lang,'sector',$sector['path'],$t['title'],$t['description'],$schema);
}

// yoursite/products/refrigeration-cold-display
public function category($slug='',$lang='en')
{
$lang=$this->_lang($lang);
$data=$this->_page_data();
$category=NULL;
foreach($data['categories'] as $row){ if($row['slug']===$slug && $data['cat_counts'][$row['id_category']]) $category=$row; }
if(!$category) return $this->_missing();
$this->config->load('sectors',TRUE);
$texts=$this->config->item('category_pages','sectors');
// the title and text typed in the control panel (Category), the built-in ones otherwise
if(!empty($category['h1_'.$lang]) && !empty($category['text_'.$lang])) $texts[$slug][$lang]=array($category['text_'.$lang],$category['h1_'.$lang]);
$name=o5_t(o5_title($category['title']));
$default=$lang=='ar' ? array('معدات احترافية للمشاريع الغذائية، توريد وتركيب وصيانة من مؤسسة طرابلسي.',$name.' في لبنان') : array('Professional equipment for food businesses, supplied, installed and serviced by Traboulsi Est.',$name.' in Lebanon');
$t=isset($texts[$slug][$lang]) ? $texts[$slug][$lang] : $default;
$items=array();
foreach($data['products'] as $product){ if($product['id_category']==$category['id_category']) $items[]=$product; }
$url=o5_url('products/'.$slug);
$list=array();
foreach($items as $i=>$product) $list[]=array('@type'=>'ListItem','position'=>$i+1,'name'=>o5_pname(o5_name($product['title'])),'url'=>o5_url('product/'.$product['slug']));
$schema=array(
	array('@type'=>'CollectionPage','@id'=>$url.'#page','url'=>$url,'name'=>$t[1],'description'=>$t[0],'about'=>array('@id'=>base_url().'#organization')),
	array('@type'=>'ItemList','name'=>$name,'numberOfItems'=>count($list),'itemListElement'=>$list),
	$this->_crumbs(array(o5_t('Products')=>o5_url().'#products',$name=>$url)),
);
$category['name']=$name;
$category['h1']=$t[1];
$category['text']=$t[0];
$data['category_page']=$category;
$data['page_products']=$items;
$this->_subpage($data,$lang,'category','products/'.$slug,$t[1].($lang=='ar' ? ' | مؤسسة طرابلسي' : ' | Traboulsi Est.'),$t[0],$schema);
}

// yoursite/product/showcase-fridge-108
public function product($slug='',$lang='en')
{
$lang=$this->_lang($lang);
$data=$this->_page_data();
$product=NULL;
foreach($data['products'] as $row){ if($row['slug']===$slug) $product=$row; }
if(!$product) return $this->_missing();
$name=o5_pname(o5_name($product['title']));
$cat=o5_t(o5_title($product['category']));
$text=$lang=='ar' ? $product['text_ar'] : $product['text_en'];
// the same type first, then the rest of its category
$same=array();
$others=array();
foreach($data['products'] as $row){
	if($row['id_products']==$product['id_products'] || $row['id_category']!=$product['id_category']) continue;
	if($row['type']===$product['type']) $same[]=$row; else $others[]=$row;
}
// the sectors that use it
$fits=array();
foreach($this->_sectors() as $sector){ if(preg_match('/'.$sector['products'].'/i',$product['title'])) $fits[]=$sector; }
$big=is_file('uploads/products/600x455/'.$product['image']) ? 'uploads/products/600x455/' : 'uploads/products/290x220/';
$url=o5_url('product/'.$slug);
$description=trim($text.' '.($lang=='ar' ? 'توريد وتركيب وصيانة من مؤسسة طرابلسي في كل لبنان.' : 'Supplied, installed and serviced by Traboulsi Est. all over Lebanon.'));
// no price is published (sold on quotation), so the product is described without an offer
$schema=array(
	array('@type'=>'Product','@id'=>$url.'#product','name'=>$name,'url'=>$url,'image'=>base_url().$big.$product['image'],'description'=>$description,'category'=>$cat,
		'sku'=>preg_match('/(\d{1,4})\s*$/',$product['title'],$m) ? 'TR-'.$m[1] : 'TR-P'.$product['id_products'],
		'brand'=>array('@type'=>'Brand','name'=>'Traboulsi Est.')),
	$this->_crumbs(array(o5_t('Products')=>o5_url().'#products',$cat=>o5_url('products/'.$product['cat_slug']),$name=>$url)),
);
$product['name']=$name;
$product['cat_name']=$cat;
$product['text']=$text;
$product['src']=$big.$product['image'];
$data['product_page']=$product;
$data['page_products']=array_slice(array_merge($same,$others),0,8);
$data['product_sectors']=$fits;
$data['page_image']=base_url().$big.$product['image'];
$this->_subpage($data,$lang,'product','product/'.$slug,$name.' | '.$cat.' | '.($lang=='ar' ? 'مؤسسة طرابلسي - لبنان' : 'Traboulsi Est. Lebanon'),$description,$schema);
}

// yoursite/export: delivery in Lebanon and outside it
public function export($lang='en')
{
$lang=$this->_lang($lang);
$t=$this->site_content->export_page($lang);
$data=$this->_page_data();
$data['export_page']=$t;
$this->_subpage($data,$lang,'export','export',$t['title'],$t['description'],array($this->_crumbs(array($t['name']=>o5_url('export'))),$this->_faq($t['faq'])));
}

// yoursite/guides: the list of the guides
public function guides($lang='en')
{
$this->guide('',$lang);
}

// yoursite/guides/restaurant-kitchen-checklist (texts in config/guides.php)
public function guide($slug='',$lang='en')
{
$lang=$this->_lang($lang);
$guides=$this->site_content->guides();
if($slug!=='' && !isset($guides[$slug])) return $this->_missing();
$data=$this->_page_data();
$data['guides']=$guides;
$list_name=o5_t('Guides');
if($slug===''){
	$title=$lang=='ar' ? 'دليل تجهيز المطاعم والمحلات الغذائية في لبنان | مؤسسة طرابلسي' : 'Guides: equipping a restaurant, bakery or shop in Lebanon | Traboulsi Est.';
	$description=$lang=='ar' ? 'مقالات عملية من مؤسسة طرابلسي: كيف تجهّز مطبخ مطعم، كيف تختار برّاد العرض أو غرفة التبريد، وما الذي يجب معرفته قبل شراء المعدات.' : 'Practical guides by Traboulsi Est.: how to equip a restaurant kitchen, how to choose a display fridge or a cold room, and what to know before buying equipment.';
	return $this->_subpage($data,$lang,'guides','guides',$title,$description,array($this->_crumbs(array($list_name=>o5_url('guides')))));
}
$g=$guides[$slug][$lang];
$url=o5_url('guides/'.$slug);
$schema=array(
	array('@type'=>'Article','@id'=>$url.'#article','headline'=>$g['h1'],'description'=>$g['description'],'inLanguage'=>$lang,'mainEntityOfPage'=>$url,
		'datePublished'=>$guides[$slug]['date'],'dateModified'=>$guides[$slug]['date'],'image'=>base_url().'images/logo.png',
		'author'=>array('@id'=>base_url().'#organization'),'publisher'=>array('@id'=>base_url().'#organization')),
	$this->_crumbs(array($list_name=>o5_url('guides'),$g['h1']=>$url)),
);
$data['guide']=$guides[$slug];
$data['guide_slug']=$slug;
$this->_subpage($data,$lang,'guide','guides/'.$slug,$g['title'],$g['description'],$schema);
}

// yoursite/products, /services, /about, /clients, /projects, /faq, /contact: one part of the home page as a page of its own
public function section($name='',$lang='en')
{
$lang=$this->_lang($lang);
$ar=$lang=='ar';
$names=array('products','services','about','clients','projects','faq','contact');
if(!in_array($name,$names)) return $this->_missing();
$data=$this->_page_data();
if($name=='projects' && !count($data['projects'])) return $this->_missing();
// its name, title, line under the title and Google title: control panel > Pages & texts > Texts of the pages
$pt=$this->site_content->page_texts();
$t=array($pt['sec_'.$name.'_name_'.$lang],$pt['sec_'.$name.'_h1_'.$lang],$pt['sec_'.$name.'_lead_'.$lang],$pt['sec_'.$name.'_title_'.$lang]);
$schema=array($this->_crumbs(array($t[0]=>o5_url($name))));
if($name=='faq'){
	// the questions typed in the control panel (the built-in ones when the field is empty)
	$this->config->load('seo_defaults',TRUE);
	$defaults=$this->config->item('seo_defaults','seo_defaults');
	$key=$ar ? 'seo_faq' : 'seo_faq_en';
	$schema[]=$this->_faq(o5_faq(trim($data['home_page'][$key])!=='' ? $data['home_page'][$key] : $defaults[$key]));
}
$data['section']=$name;
$data['section_page']=array('name'=>$t[0],'h1'=>$t[1],'lead'=>$t[2]);
$this->_subpage($data,$lang,'section',$name,$t[3],$t[2],$schema);
}

// the sector pages (control panel > Pages & texts > Sector pages), each with its address
function _sectors()
{
return $this->site_content->sectors();
}

// the language of the page, also given to the helpers (o5_t, o5_pname, o5_url)
function _lang($lang)
{
$lang=$lang=='ar' ? 'ar' : 'en';
$this->config->load('site_lang',TRUE);
$GLOBALS['o5_lang']=$lang;
$GLOBALS['o5_dict']=$this->site_content->dictionary();
$GLOBALS['o5_pdict']=(array)$this->config->item('site_products_ar','site_lang');
return $lang;
}

// "Home / Products / Showcase Fridge 108" for Google
function _crumbs($trail)
{
$items=array(array('@type'=>'ListItem','position'=>1,'name'=>o5_t('Home'),'item'=>o5_url()));
foreach($trail as $name=>$url) $items[]=array('@type'=>'ListItem','position'=>count($items)+1,'name'=>$name,'item'=>$url);
return array('@type'=>'BreadcrumbList','itemListElement'=>$items);
}

// questions and answers for Google
function _faq($list)
{
$faq=array();
foreach($list as $qa) $faq[]=array('@type'=>'Question','name'=>$qa[0],'acceptedAnswer'=>array('@type'=>'Answer','text'=>$qa[1]));
return array('@type'=>'FAQPage','mainEntity'=>$faq);
}

// a page other than the home page: its own address, title, description and structured data
function _subpage($data,$lang,$page,$path,$title,$description,$schema=array())
{
$data['page']=$page;
$data['page_path']=$path;
$data['page_title']=$title;
$data['page_description']=$description;
$data['page_schema']=$schema;
$this->_render($data,base_url().($lang=='ar' ? 'ar/' : '').$path,$lang);
}

// an address that does not exist: a real 404
function _missing()
{
set_status_header(404);
$this->load->view('404');
}

function _render($data,$page_url,$lang='en')
{
$data['page_url']=$page_url;
$data['lang']=$lang;
$data['sectors']=$this->_sectors();
$data['site_dict']=$this->site_content->dictionary();
$data['pt']=$this->site_content->page_texts();
$data['hero_cards']=$this->site_content->rows('hero_cards'); // NULL when the table is not there: the built-in cards
$data['guide_count']=count($this->site_content->guides());
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
$this->site_content->upgrade(); // products.title_ar and the texts of the category pages
$data['categories']=$this->fct->getAll('category','sort_order'); 
$data['products']=$this->fct->getAll('products','sort_order'); 
$data['videos']=$this->fct->get_videos();
$data['projects']=$this->fct->get_projects();
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
$slugs=array();
foreach($data['categories'] as $k=>$category){
	// the address of its page: yoursite/products/refrigeration-cold-display
	$category['slug']=o5_slug($category['title']);
	$data['categories'][$k]['slug']=$category['slug'];
	$items=isset($by_cat[$category['id_category']]) ? $by_cat[$category['id_category']] : array();
	$data['cat_counts'][$category['id_category']]=count($items);
	foreach($items as $k=>$item){
		$items[$k]['category']=trim(html_entity_decode(strip_tags($category['title']),ENT_QUOTES,'UTF-8'));
		$items[$k]['alt']=$this->_product_alt($item,$items[$k]['category']);
		$items[$k]['text_en']=$this->_desc_part($item['description'],'en');
		$items[$k]['text_ar']=$this->_desc_part($item['description'],'ar');
		// the address of its page (yoursite/product/showcase-fridge-108) and its type ("Showcase Fridge")
		$items[$k]['slug']=o5_product_slug($item['title'],$item['id_products'],$slugs);
		$items[$k]['cat_slug']=$category['slug'];
		// its Arabic name (control panel > Products), read by o5_pname()
		if(!empty($item['title_ar'])) $GLOBALS['o5_ptitles'][strtolower(o5_name($item['title']))]=$item['title_ar'];
		$items[$k]['type']=trim(preg_replace('/\s\d{1,4}\s*$/','',o5_name($item['title'])));
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

