<?php
/* Traboulsi Est. — option 5: the bilingual design (English yoursite/ , Arabic yoursite/ar).
   Catalogue layout of option 4 plus the services and FAQ sections, a language switch and a
   full right-to-left Arabic page. Same data as the other options, see controllers/home.php;
   the Arabic labels are in config/site_lang.php. */
// the helpers (o5_e, o5_t, o5_pname ...) are in helpers/o5_helper.php, loaded by controllers/home.php
$o5_lang = isset($lang) && $lang == 'ar' ? 'ar' : 'en';
$is_ar = $o5_lang == 'ar';
$this->config->load('site_lang', TRUE);
$o5_dict = isset($site_dict) ? $site_dict : $this->config->item('site_lang_ar', 'site_lang'); // with the translations of the control panel
// the view runs inside a function: the translation helper reads the language from $GLOBALS
$GLOBALS['o5_lang'] = $o5_lang;
$GLOBALS['o5_dict'] = $o5_dict;
$GLOBALS['o5_pdict'] = (array)$this->config->item('site_products_ar', 'site_lang');
$this->config->load('seo_defaults', TRUE);
$sd = $this->config->item('seo_defaults', 'seo_defaults');

$hp = $home_page;
foreach(array('seo_text_ar', 'seo_services_ar', 'seo_services_en', 'seo_faq', 'seo_faq_en', 'seo_section_title', 'hero_title_ar') as $name){
	if(!isset($hp[$name]) || trim($hp[$name]) === '') $hp[$name] = isset($sd[$name]) ? $sd[$name] : '';
}
$base = base_url();
$url_en = $base;
$url_ar = $base.'ar';
// the photo and video pages are shown by this same view (their content is views/option5/gallery.php): $page = home | photos | videos
$page = isset($page) && in_array($page, array('photos', 'videos', 'sector', 'category', 'product', 'export', 'guides', 'guide', 'section')) ? $page : 'home';
// a page that shows one part of the home page (yoursite/about, yoursite/faq ...): $only = products | services | about | clients | projects | faq | contact
$only = $page === 'section' && isset($section) ? $section : '';
$is_gallery = in_array($page, array('photos', 'videos'));
$page_path = isset($page_path) ? $page_path : $page;
$sectors = isset($sectors) ? $sectors : array();
$ask_subject = '';
$home_url = $is_ar ? $url_ar : $url_en;
$photos_url = $base.($is_ar ? 'ar/' : '').'photos';
$videos_url = $base.($is_ar ? 'ar/' : '').'videos';
// a link to a section of the home page: "#about" on the home page, "home address#about" on the other pages
$a = $page === 'home' ? '' : $home_url;
// the contact form is on every page but the photo and video pages: "#contact" stays on the page being read
$c = $is_gallery ? o5_url('contact') : '';
// each entry of the menu opens its own page
$nav_url = array('home' => $page === 'home' ? '#home' : $home_url, 'products' => o5_url('products'), 'services' => o5_url('services'), 'about' => o5_url('about'), 'clients' => o5_url('clients'), 'projects' => o5_url('projects'), 'showroom' => $photos_url, 'videos' => $videos_url, 'faq' => o5_url('faq'), 'contact' => o5_url('contact'));
// the language switch stays on the same page
if($page !== 'home'){ $url_en = $base.$page_path; $url_ar = $base.'ar/'.$page_path; }
$cat_icons = array('refrig|fridge|cold' => 'fridge', 'cook|heat|oven|grill|bak' => 'flame', 'stain|steel' => 'layers', 'juice|coffee|snack' => 'coffee', 'prep|butch|process' => 'knife', 'market|storage' => 'cart');
$client_icons = array('hospital' => 'medical', 'restaurant' => 'utensils', 'pastr|baker|sweet' => 'cake', 'supermarket' => 'cart');
$cat_texts = array(
	'refrig|fridge|cold' => 'Professional refrigeration for shops, restaurants, butcheries and pastry kitchens, supplied, installed and serviced by Traboulsi Est.',
	'cook|heat|oven|grill|bak' => 'Commercial cooking equipment built for long days of professional use, with installation and after-sales service by our own team.',
	'stain|steel|prep|process' => 'Stainless-steel work and food processing machines for kitchens, butcheries and bakeries. Custom sizes are made to order.',
	'default' => 'Professional equipment for food businesses, supplied, installed and serviced by Traboulsi Est.',
);
$service_icons = array('fridge', 'flame', 'cart', 'knife', 'bread', 'cake', 'coffee', 'icecream', 'medical', 'layers', 'wrench', 'globe', 'truck');

$cat_names = array();
foreach($categories as $category) $cat_names[$category['id_category']] = o5_t(o5_title($category['title']));
$product_total = count($products);

// "All" shows the categories in turn instead of 50 fridges first; each category keeps its own order
$by_cat = array();
foreach($products as $product) $by_cat[$product['id_category']][] = $product;
$mixed = array();
for($i = 0; count($mixed) < $product_total; $i++){
	foreach($by_cat as $items){ if(isset($items[$i])) $mixed[] = $items[$i]; }
}
$products = $mixed;

$founded = (int)$hp['founded_year'];
$years = $founded ? date('Y') - $founded : 0;
$unique_clients = array();
foreach($client_groups as $groups){
	foreach($groups as $group){
		foreach($group['names'] as $client_name) $unique_clients[strtolower($client_name)] = $client_name;
	}
}
$stat_products = o5_num($hp['stat_products_number'], $product_total);
$stat_sectors = o5_num($hp['stat_sectors_number'], count($categories));
$stat_clients = o5_num($hp['stat_clients_number'], count($unique_clients));
// feature cards: an icon that shows what the card is about (factory, stainless work table, shop, wrench), drawn line by line.
// It is picked from the words of the title; when no word matches, from the position of the card.
$feature_icons = array(
	'factory' => array('M3 21V11l6 4v-4l6 4V4h4v17', 'M2 21h20', 'M7 17.5h1.5M12 17.5h1.5'),
	'steel' => array('M2 9h20', 'M4.5 9v12M19.5 9v12', 'M4.5 16.5h15', 'M7.5 9v2.5a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V9', 'M17 9V5.5a2 2 0 0 0-4 0'),
	'shop' => array('M3 9l1.6-5h14.8L21 9', 'M3 9a3 3 0 0 0 6 0a3 3 0 0 0 6 0a3 3 0 0 0 6 0', 'M5 12.5V21h14v-8.5', 'M10 21v-5h4v5'),
	'tool' => array('M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z'),
);
$feature_words = array(
	'tool' => 'mainten|repair|after.?sale|صيان|تصليح',
	'steel' => 'stainless|steel|fabricat|ستانلس|ستيل|فولاذ',
	'shop' => 'project|turn.?key|restaurant|مشاريع|مشروع|مطاعم',
	'factory' => 'factory|workshop|manufactur|made|مصنع|تصنيع|صناعة',
);
$feature_order = array_keys($feature_icons);
$features = array();
for($n = 1; $n <= 4; $n++){
	if(trim($hp['feature_'.$n.'_title']) === '') continue;
	$icon = $feature_order[$n - 1];
	foreach($feature_words as $name => $words){
		if(preg_match('/'.$words.'/iu', $hp['feature_'.$n.'_title'])){ $icon = $name; break; }
	}
	$features[] = array('title' => $hp['feature_'.$n.'_title'], 'text' => $hp['feature_'.$n.'_text'], 'icon' => $icon);
}
$services = o5_lines_split($is_ar ? $hp['seo_services_ar'] : $hp['seo_services_en']);
$faq = o5_faq($is_ar ? $hp['seo_faq'] : $hp['seo_faq_en']);
$about_paragraphs = $is_ar ? preg_split('/\s*\R\s*\R\s*/u', trim($hp['seo_text_ar']), -1, PREG_SPLIT_NO_EMPTY) : array();

$storefront = is_file('uploads/about_us/tra.jpg') ? 'uploads/about_us/tra.jpg' : (is_file('uploads/about_us/530x400/tra.jpg') ? 'uploads/about_us/530x400/tra.jpg' : '');
$about_main = count($hero_images) ? $hero_images[count($hero_images) - 1]['src'] : $storefront;
if ($about_main === $storefront) $storefront_sub = ''; else $storefront_sub = $storefront;

// showroom photos: the banners and the About Us images, without duplicates
$showroom = array();
$seen = array();
foreach($hero_images as $image){
	$key = md5_file($image['src']);
	if(isset($seen[$key])) continue;
	$seen[$key] = 1;
	$showroom[] = array('src' => $image['src'], 'title' => $image['title']);
}
foreach((array)glob('uploads/about_us/530x400/*.{jpg,jpeg,png,webp}', GLOB_BRACE) as $file){
	$key = md5_file($file);
	if(isset($seen[$key])) continue;
	$seen[$key] = 1;
	$big = 'uploads/about_us/'.basename($file);
	$showroom[] = array('src' => is_file($big) ? $big : $file, 'title' => 'Traboulsi Est. building');
}

$map_src = 'https://www.google.com/maps?q=Deir+El+Zahrani,+Nabatieh,+Lebanon&z=14&output=embed';
if(!empty($generalinfo['google_map'])){
	$gm = trim($generalinfo['google_map']);
	if(preg_match('#src="([^"]+)"#', $gm, $m)) $map_src = html_entity_decode($m[1]);
	elseif(preg_match('#^https?://#', $gm)) $map_src = $gm;
}
if($is_ar) $map_src .= (strpos($map_src, '?') === FALSE ? '?' : '&').'hl=ar';

$form = array();
foreach(array('name', 'email', 'telephone', 'company', 'subject', 'message') as $field){
	$form[$field] = ($valid == 'true') ? '' : set_value($field);
}
$call_href = $contact['mobile'] ? $contact['mobile_href'] : $contact['phone_href'];
$call_num = $contact['mobile'] ? $contact['mobile'] : $contact['phone'];

// Facebook: the page of the back office (or the Contact Us link), shown with Facebook's own page plugin
$fb_page = trim(o5_or($hp['facebook_page'], isset($contact_us['facebook']) ? (string)$contact_us['facebook'] : ''));
if($fb_page !== '' && !preg_match('#^https?://(www\.|m\.|web\.)?facebook\.com/#i', $fb_page)) $fb_page = '';
$fb_plugin = $fb_page === '' ? '' : 'https://www.facebook.com/plugins/page.php?href='.rawurlencode($fb_page).'&tabs=timeline&width=500&height=600&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true'.($is_ar ? '&locale=ar_AR' : '');
$videos = isset($videos) ? $videos : array();
// photos of the Facebook card: showroom photos, then product photos
$fb_photos = array_slice($showroom, 0, 3);
foreach($products as $product){
	if(count($fb_photos) >= 7) break;
	$file = is_file('uploads/products/600x455/'.$product['image']) ? 'uploads/products/600x455/'.$product['image'] : 'uploads/products/290x220/'.$product['image'];
	if($product['image'] !== '' && is_file($file)) $fb_photos[] = array('src' => $file, 'title' => isset($product['alt']) ? $product['alt'] : $product['title']);
}
// the photo descriptions in the language of the page
foreach($fb_photos as $k => $photo) $fb_photos[$k]['title'] = $is_ar ? o5_pname(o5_t(preg_replace('/ - .*$/', '', $photo['title']))).' | مؤسسة طرابلسي - لبنان' : $photo['title'];
$band_img = count($hero_images) ? $hero_images[count($hero_images) - 1]['src'] : (count($showroom) ? $showroom[0]['src'] : '');
$company = o5_t(o5_or($hp['contact_company'], 'Traboulsi Est. for Trading & Industry'));

// completed projects (back_office/projects): only shown once one has photos
$projects = isset($projects) ? $projects : array();
$project_sectors = $this->fct->project_sectors();
$project_icons = array('restaurants' => 'i-utensils', 'bakeries' => 'i-bread', 'butchers' => 'i-knife', 'supermarkets' => 'i-cart', 'cafes' => 'i-coffee', 'hotels' => 'i-building', 'hospitals' => 'i-medical', 'other' => 'i-wrench');
$project_count = array();
foreach($projects as $pr){ $k = isset($project_sectors[$pr['sector']]) ? $pr['sector'] : 'other'; $project_count[$k] = isset($project_count[$k]) ? $project_count[$k] + 1 : 1; }

$nav = array('home' => 'Home', 'products' => 'Products', 'services' => 'Services', 'about' => 'About', 'clients' => 'Our clients', 'projects' => 'Projects', 'showroom' => 'Showroom', 'videos' => 'Videos', 'faq' => 'FAQ', 'contact' => 'Contact');
if(!count($projects)) unset($nav['projects']);
if(!count($showroom)) unset($nav['showroom']);
if(!count($videos) && !$fb_plugin) unset($nav['videos']);
if(!count($services)) unset($nav['services']);
if(!count($faq)) unset($nav['faq']);
?>
<svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true">
	<symbol id="i-phone" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
	<symbol id="i-mail" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></symbol>
	<symbol id="i-pin" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></symbol>
	<symbol id="i-fax" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></symbol>
	<symbol id="i-facebook" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></symbol>
	<symbol id="i-instagram" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></symbol>
	<symbol id="i-left" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></symbol>
	<symbol id="i-right" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></symbol>
	<symbol id="i-down" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></symbol>
	<symbol id="i-up" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></symbol>
	<symbol id="i-x" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></symbol>
	<symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.2" y2="16.2"/></symbol>
	<symbol id="i-plus" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></symbol>
	<symbol id="i-minus" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/></symbol>
	<symbol id="i-arrow" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></symbol>
	<symbol id="i-check" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></symbol>
	<symbol id="i-rows" viewBox="0 0 24 24"><rect x="3" y="4" width="7" height="6"/><line x1="13" y1="6" x2="21" y2="6"/><line x1="13" y1="9" x2="18" y2="9"/><rect x="3" y="14" width="7" height="6"/><line x1="13" y1="16" x2="21" y2="16"/><line x1="13" y1="19" x2="18" y2="19"/></symbol>
	<symbol id="i-image" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></symbol>
	<symbol id="i-play" viewBox="0 0 24 24"><polygon points="6 4 20 12 6 20 6 4"/></symbol>
	<symbol id="i-grid" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></symbol>
	<symbol id="i-globe" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></symbol>
	<symbol id="i-all" viewBox="0 0 48 48"><rect x="6" y="6" width="15" height="15" rx="2"/><rect x="27" y="6" width="15" height="15" rx="2"/><rect x="6" y="27" width="15" height="15" rx="2"/><rect x="27" y="27" width="15" height="15" rx="2"/></symbol>
	<symbol id="i-fridge" viewBox="0 0 48 48"><rect x="11" y="3" width="26" height="42" rx="3"/><line x1="11" y1="19" x2="37" y2="19"/><line x1="17" y1="9" x2="17" y2="14"/><line x1="17" y1="25" x2="17" y2="32"/></symbol>
	<symbol id="i-flame" viewBox="0 0 48 48"><path d="M17 29a5 5 0 0 0 5-5c0-2.8-1-4-2-6-2.1-4.3-.4-8.1 4-12 1 5 4 9.8 8 13 4 3.2 6 7 6 11a14 14 0 1 1-28 0c0-2.3.9-4.6 2-6a5 5 0 0 0 5 5z"/></symbol>
	<symbol id="i-layers" viewBox="0 0 48 48"><polygon points="24 4 4 14 24 24 44 14 24 4"/><polyline points="4 34 24 44 44 34"/><polyline points="4 24 24 34 44 24"/></symbol>
	<symbol id="i-coffee" viewBox="0 0 48 48"><path d="M8 18h26v12a10 10 0 0 1-10 10H18A10 10 0 0 1 8 30z"/><path d="M34 22h4a5 5 0 0 1 0 10h-4"/><line x1="16" y1="6" x2="16" y2="12"/><line x1="22" y1="6" x2="22" y2="12"/><line x1="28" y1="6" x2="28" y2="12"/></symbol>
	<symbol id="i-knife" viewBox="0 0 48 48"><path d="M6 36 30 12c4-4 10-4 12 0L18 36c-3 3-9 3-12 0z"/><line x1="30" y1="12" x2="40" y2="22"/></symbol>
	<symbol id="i-cart" viewBox="0 0 48 48"><circle cx="18" cy="42" r="2"/><circle cx="38" cy="42" r="2"/><path d="M2 4h8l5 26h24l5-18H12"/></symbol>
	<symbol id="i-bread" viewBox="0 0 48 48"><path d="M8 20a8 8 0 0 1 8-8h16a8 8 0 0 1 4 15v13H12V27a8 8 0 0 1-4-7z"/><line x1="20" y1="20" x2="20" y2="30"/><line x1="28" y1="20" x2="28" y2="30"/></symbol>
	<symbol id="i-cake" viewBox="0 0 48 48"><path d="M8 40V24a4 4 0 0 1 4-4h24a4 4 0 0 1 4 4v16"/><path d="M8 30c3 0 3 3 6 3s3-3 6-3 3 3 6 3 3-3 6-3 3 3 6 3"/><line x1="4" y1="40" x2="44" y2="40"/><line x1="16" y1="12" x2="16" y2="20"/><line x1="24" y1="10" x2="24" y2="20"/><line x1="32" y1="12" x2="32" y2="20"/></symbol>
	<symbol id="i-icecream" viewBox="0 0 48 48"><path d="M14 20a10 10 0 0 1 20 0z"/><path d="M14 20h20L24 44z"/><line x1="24" y1="20" x2="24" y2="36"/></symbol>
	<symbol id="i-medical" viewBox="0 0 48 48"><rect x="6" y="6" width="36" height="36" rx="6"/><line x1="24" y1="16" x2="24" y2="32"/><line x1="16" y1="24" x2="32" y2="24"/></symbol>
	<symbol id="i-wrench" viewBox="0 0 48 48"><path d="M30 6a10 10 0 0 0-9 14L6 35a4 4 0 0 0 6 6l15-15a10 10 0 0 0 14-9l-6 2-4-4 2-6z"/></symbol>
	<symbol id="i-truck" viewBox="0 0 48 48"><rect x="4" y="12" width="26" height="20"/><path d="M30 18h8l6 8v6h-14z"/><circle cx="12" cy="36" r="4"/><circle cx="36" cy="36" r="4"/></symbol>
	<symbol id="i-utensils" viewBox="0 0 24 24"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></symbol>
	<symbol id="i-building" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></symbol>
	<symbol id="i-whatsapp" viewBox="0 0 24 24"><path fill="currentColor" stroke="none" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></symbol>
</svg>

<a class="skip-link" href="#main"><?=o5_te('Skip to content')?></a>

<div class="loader" id="loader" aria-hidden="true">
	<div class="loader-panel"></div>
	<canvas class="loader-sparks" id="loaderSparks"></canvas>
	<div class="loader-inner">
		<span class="loader-ring" aria-hidden="true"></span>
		<span class="loader-logo"><img src="<?=$base?>images/logo.png" width="300" height="54" alt="<?=o5_e($company)?>"></span>
		<b class="loader-year" id="loaderYear" data-from="<?=$founded ? $founded : 1982?>" data-to="<?=date('Y')?>"><?=$founded ? $founded : 1982?></b>
		<p class="loader-tag"><?=$is_ar ? 'صناعة لبنانية منذ '.($founded ? $founded : 1982) : 'Made in Lebanon since '.($founded ? $founded : 1982)?></p>
		<p class="loader-words"><?php foreach(($is_ar ? array('تبريد', 'خطوط طبخ', 'ستانلس ستيل', 'تركيب وصيانة') : array('Refrigeration', 'Cooking lines', 'Stainless steel', 'Installation & service')) as $i => $word){ ?><span style="--i:<?=$i?>"><?=$word?></span><?php } ?></p>
		<div class="loader-bar"><span id="loaderBar"></span></div>
		<b class="loader-num" id="loaderNum">0</b>
	</div>
</div>
<div class="cursor" id="cursor" aria-hidden="true"><span class="cursor-label" id="cursorLabel"></span></div>
<div class="cursor-dot" id="cursorDot" aria-hidden="true"></div>

<!-- ============ Header ============ -->
<header class="site-header" id="siteHeader">
	<div class="topbar">
		<div class="container topbar-inner">
			<div class="topbar-info">
				<?php if($contact['phone']){ ?><a href="<?=o5_e($contact['phone_href'])?>"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o5_e($contact['phone'])?></span></a><?php } ?>
				<?php if($contact['email']){ ?><a href="mailto:<?=o5_e($contact['email'])?>"><svg class="ic"><use href="#i-mail"/></svg><?=o5_e($contact['email'])?></a><?php } ?>
			</div>
			<div class="topbar-side">
				<?php if($is_ar){ ?><span><?=o5_e(o5_or($hp['topbar_name_ar'], 'مؤسسة طرابلسي للتجارة والصناعة'))?></span><?php }else{ ?><span class="latin"><?=o5_e(o5_or($hp['contact_company'], 'Traboulsi Est. for Trading & Industry'))?></span><?php } ?>
				<a class="lang-switch" href="<?=$is_ar ? $url_en : $url_ar?>" hreflang="<?=$is_ar ? 'en' : 'ar'?>" lang="<?=$is_ar ? 'en' : 'ar'?>" dir="<?=$is_ar ? 'ltr' : 'rtl'?>"><svg class="ic"><use href="#i-globe"/></svg><?=$is_ar ? 'English' : 'عربي'?></a>
				<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=o5_e($contact_us['facebook'])?>" target="_blank" rel="noopener" aria-label="<?=o5_te('Facebook')?>"><svg class="ic"><use href="#i-facebook"/></svg></a><?php } ?>
				<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=o5_e($contact_us['instagram'])?>" target="_blank" rel="noopener" aria-label="Instagram"><svg class="ic"><use href="#i-instagram"/></svg></a><?php } ?>
			</div>
		</div>
	</div>

	<div class="navbar">
		<div class="container navbar-inner">
			<a href="<?=$a?>#home" class="brand" aria-label="<?=o5_e($company)?>">
				<img src="<?=$base?>images/logo.png" width="300" height="54" alt="<?=o5_e($company)?><?=$founded ? ' — '.o5_t('Since').' '.$founded : ''?>">
			</a>

			<nav class="nav" id="nav" aria-label="<?=o5_te('Main')?>">
				<ul>
					<?php foreach($nav as $id => $label){
						if($id == 'products'){ ?>
					<li class="has-drop">
						<a href="<?=o5_e($nav_url['products'])?>"<?=$only === 'products' ? ' class="is-active"' : ''?>><?=o5_te($label)?><svg class="ic caret"><use href="#i-down"/></svg></a>
						<div class="drop">
							<ul>
								<li><a href="<?=o5_e($nav_url['products'])?>"><?=o5_te('All products')?> <small><?=$product_total?></small></a></li>
								<?php foreach($categories as $category){
									if(!$cat_counts[$category['id_category']]) continue; ?>
								<li><a href="<?=o5_e(o5_url('products/'.$category['slug']))?>"><?=o5_e($cat_names[$category['id_category']])?> <small><?=$cat_counts[$category['id_category']]?></small></a></li>
								<?php } ?>
							</ul>
						</div>
					</li>
					<?php }elseif($id == 'services' && count($sectors)){ // the sector pages ?>
					<li class="has-drop">
						<a href="<?=o5_e($nav_url['services'])?>"<?=$only === 'services' ? ' class="is-active"' : ''?>><?=o5_te($label)?><svg class="ic caret"><use href="#i-down"/></svg></a>
						<div class="drop">
							<ul>
								<?php foreach($sectors as $s){ ?>
								<li><a href="<?=o5_e(o5_url($s['path']))?>"><?=o5_e($s[$o5_lang]['name'])?></a></li>
								<?php } ?>
								<li><a href="<?=o5_e(o5_url('export'))?>"><?=o5_te('Delivery & export')?></a></li>
								<?php if(!isset($guide_count) || $guide_count){ ?><li><a href="<?=o5_e(o5_url('guides'))?>"><?=o5_te('Guides')?></a></li><?php } ?>
							</ul>
						</div>
					</li>
					<?php }else{ ?>
					<li><a href="<?=o5_e($nav_url[$id])?>"<?=(($page === 'photos' && $id == 'showroom') || ($page === 'videos' && $id == 'videos') || $only === $id) ? ' class="is-active"' : ''?>><?=o5_te($label)?></a></li>
					<?php } } ?>
				</ul>
				<div class="nav-mobile-info">
					<?php if($call_num){ ?><a href="<?=o5_e($call_href)?>" class="tel"><svg class="ic"><use href="#i-phone"/></svg><?=o5_e($call_num)?></a><?php } ?>
					<a class="lang-switch" href="<?=$is_ar ? $url_en : $url_ar?>" lang="<?=$is_ar ? 'en' : 'ar'?>" dir="<?=$is_ar ? 'ltr' : 'rtl'?>"><svg class="ic"><use href="#i-globe"/></svg><?=$is_ar ? 'English' : 'عربي'?></a>
				</div>
			</nav>

			<div class="navbar-side">
				<a class="lang-switch lang-switch-nav" href="<?=$is_ar ? $url_en : $url_ar?>" lang="<?=$is_ar ? 'en' : 'ar'?>" dir="<?=$is_ar ? 'ltr' : 'rtl'?>" aria-label="<?=$is_ar ? 'English version' : 'النسخة العربية'?>"><?=$is_ar ? 'EN' : 'عربي'?></a>
				<?php if($page === 'home' || $only === 'products'){ ?><button type="button" class="icon-btn" id="searchToggle" aria-label="<?=o5_te('Search a product')?>"><svg class="ic"><use href="#i-search"/></svg></button><?php } ?>
				<button type="button" class="burger" id="menuToggle" aria-controls="nav" aria-expanded="false" aria-label="<?=o5_te('Open menu')?>"><span></span><span></span><span></span></button>
			</div>
		</div>
	</div>
	<div class="progress" aria-hidden="true"><span id="scrollBar"></span></div>
</header>

<main id="main">
<?php if($is_gallery){ include APPPATH.'views/option5/gallery.php'; }elseif($page !== 'home' && $page !== 'section'){ include APPPATH.'views/option5/pages.php'; }else{ ?>
<?php if($only !== ''){ // a page of its own for one part of the home page: its title first ?>
<section id="top" class="g-hero is-dark">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container">
		<nav class="crumbs" aria-label="<?=o5_te('You are here')?>" data-reveal="up">
			<a href="<?=o5_e($home_url)?>"><?=o5_te('Home')?></a><span aria-hidden="true">/</span><b><?=o5_e($section_page['name'])?></b>
		</nav>
		<h1 class="g-title" data-reveal="up" style="--d:100ms"><?=o5_e($section_page['h1'])?></h1>
		<p class="lead" data-reveal="up" style="--d:180ms"><?=o5_e($section_page['lead'])?></p>
	</div>
</section>
<?php } ?>

<?php if($only === ''){ ?>
<!-- ============ Slider ============ -->
<?php // short loops cut from the factory reels of the Facebook page (images/hero-reels)
$hero_reels = array();
foreach(array('laser' => 'Laser cutting', 'grills' => 'Grills & hoods', 'finishing' => 'Finishing') as $file => $label){
	if(is_file('images/hero-reels/'.$file.'.mp4')) $hero_reels[] = array('file' => $file, 'label' => $label);
}
?>
<?php // the background of the banner: a designed drawing (default) or the photos of back office > Banner
$hero_designed = !isset($hp['hero_style']) || $hp['hero_style'] !== 'photos' || !count($hero_images); ?>
<?php // the stage of the banner: the cards of control panel > Pages & texts > Banner cards, turning one after the other
$hero_stage = array();
if(isset($hero_cards) && is_array($hero_cards)){
	foreach($hero_cards as $card){
		$file = trim((string)$card['file']);
		if($file === '' || !is_file($file)) continue;
		$is_video = (bool)preg_match('/\.(mp4|webm)$/i', $file);
		$type = $is_video ? 'video' : ($card['type'] == 'portrait' ? 'portrait' : 'photo');
		$poster = trim((string)$card['poster']);
		$link = trim((string)$card['link']);
		$hero_stage[] = array(
			'type' => $type,
			'src' => $file,
			'poster' => ($poster !== '' && is_file($poster)) ? $poster : ($is_video ? '' : $file),
			'label' => $is_ar && trim((string)$card['label_ar']) !== '' ? $card['label_ar'] : o5_t($card['label_en']),
			'href' => $link === '' ? $a.'#videos' : (preg_match('#^https?://#i', $link) ? $link : ($link[0] == '#' ? $a.$link : o5_url(ltrim($link, '/')))),
		);
	}
}elseif(count($hero_reels) == 3){
	// the table of the cards is not there yet: the three factory loops and two photos of the catalogue
	$stage_photos = array();
	foreach(array('Complete Cooking Line' => 'Cooking lines', 'Showcase Fridge 111' => 'Display fridges') as $needle => $label){
		foreach($products as $product){
			if(stripos($product['title'], $needle) === FALSE) continue;
			$dir = is_file('uploads/products/600x455/'.$product['image']) ? 'uploads/products/600x455/' : 'uploads/products/290x220/';
			$stage_photos[] = array('type' => 'photo', 'src' => $dir.$product['image'], 'poster' => '', 'label' => o5_t($label), 'href' => o5_url('product/'.$product['slug']));
			break;
		}
	}
	foreach($hero_reels as $i => $reel){
		$hero_stage[] = array('type' => 'video', 'src' => 'images/hero-reels/'.$reel['file'].'.mp4', 'poster' => 'images/hero-reels/'.$reel['file'].'.jpg', 'label' => o5_t($reel['label']), 'href' => $a.'#videos');
		if(isset($stage_photos[$i])) $hero_stage[] = $stage_photos[$i];
	}
}
$stage_first = (int)floor(count($hero_stage) / 2); ?>
<section id="home" class="slider<?=count($hero_stage) ? ' has-reels has-stage' : ''?><?=$hero_designed ? ' is-designed' : ''?>" aria-roledescription="carousel" aria-label="<?=o5_e($company)?>">
	<div class="slides">
		<?php if($hero_designed) $this->load->view('option5/_hero_art'); else foreach($hero_images as $i => $image){ ?>
		<div class="slide<?=$i == 0 ? ' is-active' : ''?>"><img src="<?=$base.o5_e($image['src'])?>" alt="<?=o5_te($image['title'])?>" <?=$i == 0 ? 'fetchpriority="high"' : 'fetchpriority="low"'?>></div>
		<?php } ?>
		<?php if($hero_designed && count($hero_stage)){ // the video or photo of the card in front, large and softened behind the whole banner ?>
		<div class="stage-ambient" aria-hidden="true">
			<?php foreach($hero_stage as $i => $card){ ?><span class="is-<?=$card['type']?><?=$i == $stage_first ? ' is-active' : ''?>"<?php if($card['type'] != 'photo' && $card['poster'] !== ''){ ?> style="background-image:url(<?=$base.o5_e($card['poster'])?>)"<?php } ?>><?php if($card['type'] == 'video'){ ?><video muted loop playsinline preload="none" data-src="<?=$base.o5_e($card['src'])?>"></video><?php } ?></span><?php } ?>
		</div>
		<?php } ?>
	</div>
	<div class="slider-shade" aria-hidden="true"></div>
	<canvas class="hero-sparks" id="heroSparks" aria-hidden="true"></canvas>
	<div class="hero-stamp" aria-hidden="true">
		<svg viewBox="0 0 200 200"><defs><path id="stampPath" d="M100,100 m-80,0 a80,80 0 1,1 160,0 a80,80 0 1,1 -160,0"/></defs><text><textPath href="<?=$a?>#stampPath"<?=$is_ar ? '' : ' textLength="490"'?>><?php if($is_ar){ ?>صناعة لبنانية • منذ <?=$founded ? $founded : 1982?> • صناعة لبنانية • منذ <?=$founded ? $founded : 1982?> • <?php }else{ ?>MADE IN LEBANON • SINCE <?=$founded ? $founded : 1982?> • TRABOULSI EST. • <?php } ?></textPath></text></svg>
		<span class="stamp-core"><b><?=$founded ? $founded : 1982?></b><small><?=$is_ar ? 'تأسست' : 'EST.'?></small></span>
	</div>
	<div class="hero-chips" aria-hidden="true">
		<span class="hero-chip chip-factory"><i class="live-dot"></i><span><b><?=o5_te('Factory')?></b><small><?=o5_te('Deir El Zahrani')?></small></span></span>
		<span class="hero-chip chip-branch"><svg class="ic"><use href="#i-pin"/></svg><span><b><?=o5_te('Branch')?></b><small><?=o5_te('Khalde · Beirut')?></small></span></span>
	</div>
	<?php if(count($hero_stage)){ ?>
	<div class="hero-reels hero-stage" id="heroStage" role="group" aria-label="<?=o5_te('From our factory')?>" style="--n:<?=count($hero_stage)?>">
		<div class="stage-cards">
			<?php foreach($hero_stage as $i => $card){ ?>
			<a href="<?=o5_e($card['href'])?>" class="stage-card is-<?=$card['type']?><?=$i == $stage_first ? ' is-active' : ''?>" style="--p:<?=$i - $stage_first?>;--ap:<?=abs($i - $stage_first)?>;z-index:<?=10 - abs($i - $stage_first)?>" aria-label="<?=o5_e($card['label'])?>">
				<?php if($card['type'] == 'video' || isset($card['video'])){ ?>
				<video muted loop playsinline preload="none"<?php if($card['poster'] !== ''){ ?> poster="<?=$base.o5_e($card['poster'])?>"<?php } ?> data-src="<?=$base.o5_e(isset($card['video']) ? $card['video'] : $card['src'])?>" aria-hidden="true"></video>
				<?php }else{ ?>
				<img src="<?=$base.o5_e($card['src'])?>" alt="<?=o5_e($card['label'])?>" loading="lazy">
				<?php } ?>
				<span class="stage-kind" aria-hidden="true"><svg class="ic"><use href="#i-<?=$card['type'] == 'video' ? 'play' : ($card['type'] == 'portrait' ? 'check' : 'image')?>"/></svg></span>
				<span class="stage-label"><b><?=str_pad($i + 1, 2, '0', STR_PAD_LEFT)?></b><?=o5_e($card['label'])?></span>
				<span class="stage-bar" aria-hidden="true"><i></i></span>
			</a>
			<?php } ?>
		</div>
		<div class="stage-nav">
			<button type="button" class="stage-btn" data-stage="-1" aria-label="<?=o5_te('Previous')?>"><svg class="ic"><use href="#i-left"/></svg></button>
			<span class="stage-count" aria-hidden="true"><b id="stageNow"><?=str_pad($stage_first + 1, 2, '0', STR_PAD_LEFT)?></b><i></i><?=str_pad(count($hero_stage), 2, '0', STR_PAD_LEFT)?></span>
			<button type="button" class="stage-btn" data-stage="1" aria-label="<?=o5_te('Next')?>"><svg class="ic"><use href="#i-right"/></svg></button>
			<a href="<?=$a?>#videos" class="stage-more"><?=o5_te('Watch the videos')?><svg class="ic"><use href="#i-arrow"/></svg></a>
		</div>
	</div>
	<?php } ?>
	<div class="container slider-caption">
		<?=o5_tag('<p class="hero-eyebrow">', $hp['hero_eyebrow'], '</p>')?>
		<?php if($is_ar && trim($hp['hero_title_ar']) !== ''){ ?><h1 class="hero-title"><?=o5_lines($hp['hero_title_ar'])?></h1>
		<?php }elseif(trim($hp['hero_title']) !== ''){ ?><h1 class="hero-title"><?=o5_lines($hp['hero_title'])?></h1><?php } ?>
		<?php if($is_ar && trim($hp['hero_text_ar']) !== ''){ ?><p class="hero-text"><?=o5_e($hp['hero_text_ar'])?></p><?php } ?>
		<?php if(!$is_ar && trim((string)@$hp['hero_line_en']) !== ''){ ?><p class="hero-text"><?=o5_e($hp['hero_line_en'])?></p><?php } ?>
		<div class="hero-actions">
			<?=o5_tag('<a href="'.$a.'#products" class="btn-more btn-white" data-magnetic><span>', $hp['hero_button_1'], '</span><svg class="ic"><use href="#i-plus"/></svg></a>')?>
			<?=o5_tag('<a href="'.$a.'#contact" class="btn-link btn-link-light">', $hp['hero_button_2'], '<svg class="ic"><use href="#i-arrow"/></svg></a>')?>
		</div>
	</div>
	<?php if(count($hero_images) > 1 && !$hero_designed){ ?>
	<p class="slider-count" aria-hidden="true"><b id="slideNow">01</b><span></span><?=str_pad(count($hero_images), 2, '0', STR_PAD_LEFT)?></p>
	<button type="button" class="slider-arrow prev" aria-label="<?=o5_te('Previous photo')?>"><svg class="ic"><use href="#i-left"/></svg></button>
	<button type="button" class="slider-arrow next" aria-label="<?=o5_te('Next photo')?>"><svg class="ic"><use href="#i-right"/></svg></button>
	<div class="slider-dots">
		<?php foreach($hero_images as $i => $image){ ?>
		<button type="button" class="<?=$i == 0 ? 'is-active' : ''?>" aria-label="<?=o5_te('Show photo')?> <?=$i + 1?>"></button>
		<?php } ?>
	</div>
	<?php } ?>
</section>
<?php } ?>

<?php if($only === ''){ ?>
<!-- ============ Two crossing bands with what we do ============ -->
<?php $tape_words = $is_ar ? array('تصنيع', 'برادات', 'ستانلس ستيل', 'شوايات', 'غرف تبريد', 'معدات مطابخ', 'تركيب', 'صيانة', 'صناعة لبنانية') : array('Manufacturing', 'Refrigeration', 'Stainless steel', 'Grills', 'Cold rooms', 'Kitchen equipment', 'Installation', 'Maintenance', 'Made in Lebanon'); ?>
<div class="tapes" aria-hidden="true">
	<div class="tape tape-a"><div class="tape-track"><?php for($copy = 0; $copy < 2; $copy++){ foreach($tape_words as $word){ ?><span><?=o5_e($word)?></span><i>✦</i><?php } } ?></div></div>
	<div class="tape tape-b"><div class="tape-track"><?php for($copy = 0; $copy < 2; $copy++){ foreach(array_reverse($tape_words) as $word){ ?><span><?=o5_e($word)?></span><i>✦</i><?php } } ?></div></div>
</div>
<?php } ?>

<?php if($only === ''){ ?>
<!-- ============ Quote + key figures ============ -->
<section class="quote">
	<?php if($founded){ ?><div class="ghost-year" aria-hidden="true" data-drift><?=$founded?></div><?php } ?>
	<div class="container narrow">
		<?php if($founded){ ?><p class="quote-badge" data-reveal="up"><span class="pulse-dot" aria-hidden="true"></span><?=$is_ar ? 'منذ '.$founded.' · لبنان' : 'Since '.$founded.' · Lebanon'?></p><?php } ?>
		<h2 class="quote-title" data-reveal="split"><?=$is_ar ? '&ldquo;'.o5_e(o5_or($hp['topbar_name_ar'], 'مؤسسة طرابلسي')).'&rdquo;' : '&ldquo;TRABOULSI EST.&rdquo;'?></h2>
		<?=o5_tag('<p class="lead" data-words>', $hp['hero_text'], '</p>')?>
		<?php // "We equip ..." with the sectors changing one after the other
		$equip_words = $is_ar ? array('المطاعم', 'الفنادق', 'السوبرماركت', 'الملاحم', 'الأفران والحلويات', 'المقاهي', 'المستشفيات') : array('restaurants', 'hotels', 'supermarkets', 'butcheries', 'bakeries & pastry shops', 'cafés', 'hospitals'); ?>
		<p class="equip-line" data-reveal="up" style="--d:150ms"><span class="equip-lead"><?=o5_te('We equip')?></span> <span class="equip-rotator" style="--n:<?=count($equip_words)?>"><?php foreach($equip_words as $i => $word){ ?><span style="--i:<?=$i?>"<?=$i ? ' aria-hidden="true"' : ''?>><?=o5_e($word)?></span><?php } ?></span></p>
		<ul class="figures">
			<?php if($years){ ?><li data-reveal="up" style="--d:100ms"><span class="fig-icon" aria-hidden="true"><svg class="ic"><use href="#i-wrench"/></svg></span><strong><span data-count="<?=$years?>"><?=$years?></span>+</strong><span class="fig-label"><?=o5_te(o5_or($hp['stat_years_label'], 'Years of experience'))?></span></li><?php } ?>
			<li data-reveal="up" style="--d:200ms"><span class="fig-icon" aria-hidden="true"><svg class="ic"><use href="#i-fridge"/></svg></span><strong><span data-count="<?=$stat_products?>"><?=$stat_products?></span>+</strong><span class="fig-label"><?=o5_te(o5_or($hp['stat_products_label'], 'Products'))?></span></li>
			<?php if($stat_sectors){ ?><li data-reveal="up" style="--d:250ms"><span class="fig-icon" aria-hidden="true"><svg class="ic"><use href="#i-layers"/></svg></span><strong><span data-count="<?=$stat_sectors?>"><?=$stat_sectors?></span></strong><span class="fig-label"><?=o5_te(o5_or($hp['stat_sectors_label'], 'Business sectors equipped'))?></span></li><?php } ?>
			<?php if($stat_clients){ ?><li data-reveal="up" style="--d:300ms"><span class="fig-icon" aria-hidden="true"><svg class="ic"><use href="#i-building"/></svg></span><strong><span data-count="<?=$stat_clients?>"><?=$stat_clients?></span>+</strong><span class="fig-label"><?=o5_te(o5_or($hp['stat_clients_label'], 'Clients'))?></span></li><?php } ?>
		</ul>
	</div>
</section>
<?php } ?>

<?php if($only === '' || $only === 'products'){ ?>
<!-- ============ Category tiles ============ -->
<section class="categories is-dark" aria-label="<?=o5_te('Product categories')?>">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="glow glow-b" aria-hidden="true"></div>
	<div class="container">
		<div class="section-head" data-reveal="up">
			<?=o5_tag('<p class="eyebrow">', $hp['solutions_eyebrow'], '</p>')?>
			<?=o5_tag('<h2 class="h2">', $hp['solutions_title'], '</h2>')?>
			<?=o5_tag('<p class="lead">', $hp['solutions_text'], '</p>')?>
		</div>
		<div class="cat-tiles">
			<a class="cat-tile" href="<?=$only === 'products' ? '#products' : o5_e($nav_url['products'])?>" data-reveal="up" data-tilt>
				<svg class="cat-icon"><use href="#i-all"/></svg>
				<h2><?=o5_te('All products')?></h2>
				<span><?=$product_total?> <?=o5_te('products')?></span>
			</a>
			<?php foreach($categories as $i => $category){
				$id = $category['id_category'];
				$count = $cat_counts[$id]; ?>
			<a class="cat-tile" data-reveal="up" data-tilt style="--d:<?=($i + 1) * 110?>ms" href="<?=$count ? o5_e(o5_url('products/'.$category['slug'])) : $c.'#contact'?>">
				<svg class="cat-icon"><use href="#i-<?=o5_icon($category['title'], $cat_icons, 'layers')?>"/></svg>
				<h2><?=o5_e($cat_names[$id])?></h2>
				<span><?=$count ? $count.' '.o5_t('products') : o5_t('Made to order')?></span>
			</a>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>

<?php if($only === '' || $only === 'products'){ ?>
<!-- ============ Products ============ -->
<section id="products" class="products">
	<div class="container">
		<div class="products-head" data-reveal="up">
			<div>
				<?=o5_tag('<p class="eyebrow">', $hp['products_eyebrow'], '</p>')?>
				<h2 class="h2"><?=o5_te(o5_or($hp['products_title'], 'Our products'))?></h2>
			</div>
			<div class="products-tools">
				<label class="search">
					<svg class="ic"><use href="#i-search"/></svg>
					<input type="search" id="productSearch" placeholder="<?=o5_te('Search a product…')?>" aria-label="<?=o5_te('Search products')?>" autocomplete="off">
				</label>
				<div class="view-switch" role="group" aria-label="<?=o5_te('Display')?>">
					<button type="button" class="is-active" data-view="rows" aria-pressed="true" title="<?=o5_te('Large rows')?>"><svg class="ic"><use href="#i-rows"/></svg><span class="sr"><?=o5_te('Large rows')?></span></button>
					<button type="button" data-view="grid" aria-pressed="false" title="<?=o5_te('Grid')?>"><svg class="ic"><use href="#i-grid"/></svg><span class="sr"><?=o5_te('Grid')?></span></button>
				</div>
			</div>
		</div>
		<div class="chips" role="toolbar" aria-label="<?=o5_te('Filter products by category')?>" data-reveal="up" style="--d:120ms">
			<button type="button" class="chip is-active" data-filter="*" aria-pressed="true"><?=o5_te('All')?> <small><?=$product_total?></small></button>
			<?php foreach($categories as $category){
				if(!$cat_counts[$category['id_category']]) continue; ?>
			<button type="button" class="chip" data-filter="prod<?=$category['id_category']?>" aria-pressed="false"><?=o5_e($cat_names[$category['id_category']])?> <small><?=$cat_counts[$category['id_category']]?></small></button>
			<?php } ?>
		</div>
	</div>

	<div class="p-list is-rows" id="productList">
		<?php foreach($products as $n => $product){
			$cat = isset($cat_names[$product['id_category']]) ? $cat_names[$product['id_category']] : '';
			$name = o5_pname(o5_name($product['title']));
			$alt = $is_ar ? $name.' - '.o5_t($cat).' | مؤسسة طرابلسي - لبنان' : $product['alt'];
			$text = $is_ar ? $product['text_ar'] : $product['text_en'];
			if($text === '') $text = o5_t(o5_icon($cat, $cat_texts, $cat_texts['default']));
			$big = is_file('uploads/products/600x455/'.$product['image']) ? 'uploads/products/600x455/' : 'uploads/products/290x220/';
			$size = @getimagesize($big.$product['image']);
			$max_h = $size ? round($size[1] * 1.4) : 640; ?>
		<article class="p-row<?=$n % 2 ? ' is-flip' : ''?>" data-cat="prod<?=$product['id_category']?>" data-search="<?=o5_e(mb_strtolower($product['title'].' '.$cat.' '.$text, 'UTF-8'))?>">
			<div class="p-row-inner">
				<a class="p-img<?=($size && $size[1] < 380) ? ' is-small' : ''?>" href="<?=o5_e(o5_url('product/'.$product['slug']))?>" aria-label="<?=o5_te('View')?> <?=o5_e($name)?>" data-cursor="<?=o5_te('View')?>">
					<span class="p-disc" aria-hidden="true"></span>
					<?php if(preg_match('/(\d{2,4})\s*$/', $product['title'], $ref)){ ?><span class="p-ghost" aria-hidden="true"><?=$ref[1]?></span><?php } ?>
					<img src="<?=$base.$big.o5_e($product['image'])?>" alt="<?=o5_e($alt)?>" loading="lazy" style="--max-h:<?=$max_h?>px"<?php if($size){ ?> width="<?=$size[0]?>" height="<?=$size[1]?>"<?php } ?>>
				</a>
				<div class="p-text">
					<span class="p-cat"><?=o5_e($cat)?></span>
					<h3 class="p-name"><a href="<?=o5_e(o5_url('product/'.$product['slug']))?>"><?=o5_e($name)?></a></h3>
					<p class="p-desc"><?=o5_e($text)?></p>
					<div class="p-actions">
						<a class="btn-more" href="<?=o5_e(o5_url('product/'.$product['slug']))?>"><span><?=o5_te('More')?></span><svg class="ic"><use href="#i-plus"/></svg></a>
						<button type="button" class="btn-link" data-ask><?=o5_te('Ask for a quote')?><svg class="ic"><use href="#i-arrow"/></svg></button>
					</div>
				</div>
			</div>
		</article>
		<?php } ?>
	</div>

	<div class="container">
		<p class="empty" id="productsEmpty" hidden><?=o5_te('No products match your search.')?> <a href="<?=$c?>#contact"><?=o5_te('Ask us directly')?></a><?=o5_te(', we may have it in stock or make it to order.')?></p>
		<div class="more" id="loadMoreWrap">
			<p><?=o5_te('Showing')?> <b id="shownCount"><?=$product_total?></b> <?=o5_te('of')?> <b id="totalCount"><?=$product_total?></b> <?=o5_te('products')?></p>
			<button type="button" class="btn-more btn-wide" id="loadMore" hidden><span><?=o5_te('Load more products')?></span><svg class="ic"><use href="#i-plus"/></svg></button>
		</div>
	</div>
</section>
<?php } ?>

<?php if($only === '' || $only === 'services'){ ?>
<!-- ============ Services ============ -->
<?php if(count($services)){ ?>
<section id="services" class="services">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?=o5_te(o5_or($hp['services_eyebrow'], 'From the plan to the last screw'))?></p>
			<h2 class="h2"><?=o5_te(o5_or($hp['services_title'], 'Our services'))?></h2>
			<p class="lead"><?=o5_te(o5_or($hp['services_text'], 'Manufacturing, import, supply, installation and maintenance of everything a food business needs, by one team.'))?></p>
		</div>
		<ul class="service-grid">
			<?php foreach($services as $i => $service){ ?>
			<li class="service" data-reveal="up" style="--d:<?=($i % 4) * 110?>ms;--i:<?=$i?>">
				<span class="s-num" aria-hidden="true"><?=str_pad($i + 1, 2, '0', STR_PAD_LEFT)?></span>
				<svg class="s-icon"><use href="#i-<?=$service_icons[$i % count($service_icons)]?>"/></svg>
				<h3><?=o5_e($service)?></h3>
			</li>
			<?php } ?>
		</ul>
		<?php if(count($sectors)){ // one page per sector ?>
		<h3 class="sec-title" data-reveal="up"><?=o5_te('Solutions by sector')?></h3>
		<div class="sec-grid">
			<?php $i = 0; foreach($sectors as $s){ ?>
			<a class="sec-card" href="<?=o5_e(o5_url($s['path']))?>" data-reveal="up" style="--d:<?=($i++ % 4) * 80?>ms">
				<svg class="ic"><use href="#i-<?=$s['icon']?>"/></svg>
				<b><?=o5_e($s[$o5_lang]['name'])?></b>
				<i aria-hidden="true"><svg class="ic"><use href="#i-arrow"/></svg></i>
			</a>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</section>
<?php } ?>
<?php } ?>

<?php if($only === ''){ ?>
<!-- ============ Parallax band: years + call to action ============ -->
<section class="band">
	<?php if($band_img){ ?><div class="band-media" aria-hidden="true"><img src="<?=$base.o5_e($band_img)?>" alt="<?=o5_e($company)?>" loading="lazy" data-parallax></div><?php } ?>
	<div class="container band-inner">
		<?php if($years){ ?>
		<div class="band-years" data-reveal="left">
			<strong><span data-count="<?=$years?>"><?=$years?></span><sup>+</sup></strong>
			<span><?=o5_te(o5_or($hp['stat_years_label'], 'Years of experience'))?></span>
		</div>
		<?php } ?>
		<div class="band-text" data-reveal="right" style="--d:150ms">
			<?=o5_tag('<h2>', $hp['cta_title'], '</h2>')?>
			<?=o5_tag('<p>', $hp['cta_text'], '</p>')?>
			<div class="cta-actions">
				<?=o5_tag('<a href="'.$a.'#contact" class="btn-solid btn-glow" data-magnetic><span>', $hp['cta_button'], '</span><svg class="ic"><use href="#i-arrow"/></svg></a>')?>
				<?php if($call_num){ ?><a href="<?=o5_e($call_href)?>" class="cta-call"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o5_e($call_num)?></span></a><?php } ?>
			</div>
		</div>
	</div>
</section>
<?php } ?>

<?php if($only === '' || $only === 'about'){ ?>
<!-- ============ About ============ -->
<section id="about" class="about">
	<div class="container about-grid<?=$about_main ? '' : ' no-img'?>">
		<div class="about-body" data-reveal="left">
			<p class="eyebrow"><?=o5_te($about_us['title'])?></p>
			<?=o5_tag('<h2 class="h2">', $hp['about_title'], '</h2>')?>
			<?php if($is_ar && count($about_paragraphs)){ ?>
			<div class="about-text is-clamped" id="aboutText"><?php foreach($about_paragraphs as $p){ ?><p><?=nl2br(o5_e($p))?></p><?php } ?></div>
			<?php }else{ ?>
			<div class="about-text is-clamped" id="aboutText"><?=$about_us['description']?></div>
			<?php } ?>
			<button type="button" class="btn-more" data-toggle-about aria-expanded="false" aria-controls="aboutText" data-less="<?=o5_te('Read less')?>"><span><?=o5_te('Read more')?></span><svg class="ic"><use href="#i-plus"/></svg></button>
		</div>
		<?php if($about_main){ ?>
		<figure class="about-img<?=$storefront_sub ? ' has-sub' : ''?>" data-reveal="wipe" style="--d:150ms">
			<span class="about-photo"><img src="<?=$base.o5_e($about_main)?>" alt="<?=o5_te('Traboulsi Est. showroom with commercial kitchen equipment')?>"></span>
			<?php if($storefront_sub){ ?><span class="about-sub"><img src="<?=$base.$storefront_sub?>" width="530" height="400" alt="<?=o5_te('Traboulsi Est. building in Deir El Zahrani')?>"></span><?php } ?>
			<?php if($founded){ ?><figcaption><strong><?=$founded?></strong><?=o5_te(o5_or($hp['about_badge_label'], 'Established'))?></figcaption><?php } ?>
		</figure>
		<?php } ?>
	</div>
	<?php if(count($features)){ ?>
	<div class="container">
		<ul class="features">
			<?php foreach($features as $i => $feature){ ?>
			<li data-feature style="--d:<?=$i * 110?>ms">
				<span class="f-num" aria-hidden="true"><?=str_pad($i + 1, 2, '0', STR_PAD_LEFT)?></span>
				<span class="f-icon"><svg class="ic" viewBox="0 0 24 24"><?php foreach($feature_icons[$feature['icon']] as $d){ ?><path class="f-draw" pathLength="1" d="<?=$d?>"/><?php } ?></svg></span>
				<h3><?=o5_te($feature['title'])?></h3>
				<?=o5_tag('<p>', $feature['text'], '</p>')?>
				<span class="f-bar" aria-hidden="true"></span>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
</section>
<?php } ?>

<?php if($only === '' || $only === 'clients'){ ?>
<!-- ============ Clients ============ -->
<section id="clients" class="clients is-dark">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container">
		<div class="section-head" data-reveal="up">
			<?=o5_tag('<p class="eyebrow">', $hp['clients_eyebrow'], '</p>')?>
			<?=o5_tag('<h2 class="h2">', $hp['clients_title'], '</h2>')?>
			<?=o5_tag('<p class="lead">', $hp['clients_text'], '</p>')?>
		</div>
	</div>
	<?php if(count($unique_clients)){ ?>
	<div class="marquee" aria-hidden="true">
		<div class="marquee-skew" id="marqueeSkew"><div class="marquee-track">
			<?php for($copy = 0; $copy < 2; $copy++){ foreach($unique_clients as $client_name){ ?><span><?=o5_te($client_name)?></span><?php } } ?>
		</div></div>
	</div>
	<?php } ?>
	<div class="container">
		<?php foreach($client_groups as $country => $groups){ ?>
		<?php if(count($client_groups) > 1){ ?><p class="c-country"><svg class="ic"><use href="#i-pin"/></svg><?=o5_te($country)?></p><?php } ?>
		<div class="c-grid">
			<?php foreach($groups as $i => $group){ ?>
			<div class="c-card" data-reveal="up" style="--d:<?=($i % 4) * 110?>ms">
				<div class="c-head">
					<svg class="ic"><use href="#i-<?=o5_icon($group['title'], $client_icons, 'building')?>"/></svg>
					<h3><?=o5_te($group['title'])?></h3>
					<?php if(count($group['names'])){ ?><span><?=count($group['names'])?></span><?php } ?>
				</div>
				<?php if(count($group['names'])){ ?>
				<ul>
					<?php foreach($group['names'] as $client_name){ ?><li><?=o5_te($client_name)?></li><?php } ?>
				</ul>
				<?php }else{ ?>
				<div class="c-raw"><?=$group['description']?></div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</section>
<?php } ?>

<?php if($only === '' || $only === 'projects' || $only === 'clients'){ ?>
<!-- ============ Completed projects ============ -->
<?php if(count($projects)){ ?>
<section id="projects" class="projects">
	<div class="container">
		<div class="row-title" data-reveal="up">
			<div>
				<p class="eyebrow"><?=o5_te('Our work')?></p>
				<h2 class="h2"><?=o5_te('Completed projects')?></h2>
			</div>
			<p class="proj-total"><strong><span data-count="<?=count($projects)?>"><?=count($projects)?></span></strong> <?=o5_te(count($projects) == 1 ? 'project' : 'projects')?></p>
		</div>
		<p class="lead" data-reveal="up" style="--d:100ms"><?=o5_te('Kitchens, bakeries, butcher shops and supermarkets we equipped across Lebanon, from the first drawing to delivery.')?></p>
		<?php if(count($project_count) > 1){ ?>
		<div class="chips proj-chips" role="group" aria-label="<?=o5_te('Filter projects by sector')?>" data-reveal="up" style="--d:150ms">
			<button type="button" class="chip is-active" data-proj-filter="all" aria-pressed="true"><?=o5_te('All')?> <small><?=count($projects)?></small></button>
			<?php foreach($project_sectors as $key => $name){ if(empty($project_count[$key])) continue; ?>
			<button type="button" class="chip" data-proj-filter="<?=o5_e($key)?>" aria-pressed="false"><svg class="ic"><use href="#<?=$project_icons[$key]?>"/></svg><?=o5_te($name)?> <small><?=$project_count[$key]?></small></button>
			<?php } ?>
		</div>
		<?php } ?>
		<div class="proj-grid">
			<?php foreach($projects as $i => $pr){
				$key = isset($project_sectors[$pr['sector']]) ? $pr['sector'] : 'other';
				// one language per page
				$p_title = trim((string)($is_ar ? $pr['title_ar'] : $pr['title']));
				$p_place = trim((string)($is_ar ? $pr['place_ar'] : $pr['place']));
				$p_text = trim((string)($is_ar ? $pr['description_ar'] : $pr['description']));
				$p_cat = o5_t($project_sectors[$key]);
				if($p_title === '') $p_title = $p_cat.($p_place !== '' ? ' · '.$p_place : '');
				$p_photos = array();
				foreach($pr['photo_list'] as $ph) $p_photos[] = $base.'uploads/projects/'.$ph;
				$cover = $pr['photo_list'][0];
				$cover_src = is_file('uploads/projects/thumb/'.$cover) ? 'uploads/projects/thumb/'.$cover : 'uploads/projects/'.$cover;
			?>
			<a href="<?=o5_e($p_photos[0])?>" class="proj-card" data-project data-sector="<?=o5_e($key)?>" data-photos="<?=o5_e(json_encode($p_photos))?>" data-title="<?=o5_e($p_title)?>" data-cat="<?=o5_e($p_cat.($p_place !== '' ? ' · '.$p_place : ''))?>" data-text="<?=o5_e($p_text)?>" data-reveal="up" style="--d:<?=min($i % 3, 2) * 110?>ms">
				<span class="proj-img"><img src="<?=$base.o5_e($cover_src)?>" alt="<?=o5_e($p_title)?> — <?=o5_e($p_cat)?>" loading="lazy"></span>
				<?php if(count($p_photos) > 1){ ?><span class="proj-count"><svg class="ic"><use href="#i-grid"/></svg><?=count($p_photos)?></span><?php } ?>
				<span class="proj-body">
					<span class="proj-cat"><svg class="ic"><use href="#<?=$project_icons[$key]?>"/></svg><?=o5_e($p_cat)?></span>
					<span class="proj-title"><?=o5_e($p_title)?></span>
					<?php if($p_place !== ''){ ?><span class="proj-place"><svg class="ic"><use href="#i-pin"/></svg><?=o5_e($p_place)?></span><?php } ?>
				</span>
			</a>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>
<?php } ?>

<?php if($only === ''){ ?>
<!-- ============ Showroom ============ -->
<?php if(count($showroom)){ ?>
<section id="showroom" class="showroom">
	<div class="container">
		<div class="row-title" data-reveal="up">
			<h2 class="h3"><?=o5_te(o5_or($hp['showroom_title'], 'Our showroom'))?></h2>
			<div class="row-side">
				<a class="btn-link row-all" href="<?=o5_e($photos_url)?>"><?=o5_te('View all photos')?><svg class="ic"><use href="#i-arrow"/></svg></a>
				<div class="row-arrows">
					<button type="button" class="arrow-btn" data-scroll="-1" aria-label="<?=o5_te('Previous photos')?>"><svg class="ic"><use href="#i-left"/></svg></button>
					<button type="button" class="arrow-btn" data-scroll="1" aria-label="<?=o5_te('Next photos')?>"><svg class="ic"><use href="#i-right"/></svg></button>
				</div>
			</div>
		</div>
		<p class="lead"><?=o5_te(o5_or($hp['showroom_text'], 'Visit our factory in Deir El Zahrani or our branch in Khalde: refrigerators, cooking lines and stainless-steel work, on display and ready to deliver.'))?></p>
		<div class="show-track" id="showTrack">
			<?php foreach($showroom as $i => $photo){ ?>
			<a href="<?=$base.o5_e($photo['src'])?>" class="show-item" data-gallery data-reveal="zoom" style="--d:<?=min($i, 3) * 120?>ms" aria-label="<?=o5_te('Open photo')?> <?=$i + 1?>">
				<img src="<?=$base.o5_e($photo['src'])?>" alt="<?=o5_e($photo['title'])?> — <?=o5_te('showroom photo')?> <?=$i + 1?>" loading="lazy">
				<span class="show-num"><?=str_pad($i + 1, 2, '0', STR_PAD_LEFT)?></span>
				<span class="show-cap"><b><?=o5_te($photo['title'])?></b><small><?=o5_te('Deir El Zahrani · click to enlarge')?></small><i class="show-zoom"><svg class="ic"><use href="#i-plus"/></svg></i></span>
			</a>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>
<?php } ?>

<?php if($only === ''){ ?>
<!-- ============ Videos & Facebook ============ -->
<?php if(count($videos) || $fb_plugin){ ?>
<section id="videos" class="videos is-dark<?=count($videos) ? '' : ' no-videos'?>">
	<div class="glow glow-b" aria-hidden="true"></div>
	<div class="container">
		<div class="row-title" data-reveal="up">
			<div>
				<p class="eyebrow"><?=o5_te(o5_or($hp['videos_eyebrow'], 'Watch'))?></p>
				<h2 class="h2"><?=o5_te(o5_or($hp['videos_title'], 'Our equipment in action'))?></h2>
			</div>
			<div class="row-side">
			<?php if(count($videos)){ ?><a class="btn-link btn-link-light row-all" href="<?=o5_e($videos_url)?>"><?=o5_te('View all videos')?><svg class="ic"><use href="#i-arrow"/></svg></a><?php } ?>
			<?php if(count($videos) > 1){ ?>
			<div class="row-arrows">
				<button type="button" class="arrow-btn" data-scroll="-1" data-track="videoTrack" aria-label="<?=o5_te('Previous videos')?>"><svg class="ic"><use href="#i-left"/></svg></button>
				<button type="button" class="arrow-btn" data-scroll="1" data-track="videoTrack" aria-label="<?=o5_te('Next videos')?>"><svg class="ic"><use href="#i-right"/></svg></button>
			</div>
			<?php } ?>
			</div>
		</div>
		<p class="lead" data-reveal="up" style="--d:100ms"><?=o5_te(o5_or($hp['videos_text'], 'Installations, new arrivals and our workshop, straight from our Facebook page.'))?></p>

		<?php if(count($videos)){ ?>
		<div class="video-track" id="videoTrack">
			<?php foreach($videos as $i => $video){
				// one language per page: the Arabic title and text on /ar, the English ones on the English page
				$v_title = $is_ar ? trim((string)@$video['title_ar']) : trim((string)$video['title']);
				$v_text = $is_ar ? trim((string)@$video['description_ar']) : trim((string)$video['description']);
				if($is_ar && $v_title === '' && trim((string)$video['title']) !== '' && o5_t($video['title']) !== $video['title']) $v_title = o5_t($video['title']);
			?>
			<figure class="video-card<?=$video['vertical'] ? ' is-vertical' : ''?>" data-reveal="up" style="--d:<?=min($i, 3) * 120?>ms">
				<div class="video-frame" data-cursor="<?=o5_te('Play')?>">
					<iframe src="<?=o5_e($video['src'])?>" title="<?=o5_e($v_title !== '' ? $v_title : o5_t('Video'))?>" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowfullscreen></iframe>
				</div>
				<?php if($v_title !== '' || $v_text !== ''){ ?>
				<figcaption>
					<?php if($v_title !== ''){ ?><h3><?=o5_e($v_title)?></h3><?php } ?>
					<?php if($v_text !== ''){ ?><p><?=nl2br(o5_e($v_text), false)?></p><?php } ?>
				</figcaption>
				<?php } ?>
			</figure>
			<?php } ?>
		</div>
		<?php } ?>

		<?php if($fb_plugin){ ?>
		<div class="fb-wrap" data-reveal="up">
			<div class="fb-text">
				<span class="fb-badge"><svg class="ic"><use href="#i-facebook"/></svg></span>
				<p class="eyebrow" dir="ltr">facebook.com/<?=o5_e(trim(parse_url($fb_page, PHP_URL_PATH), '/'))?></p>
				<h3><?=o5_te('Follow us on Facebook')?></h3>
				<p><?=o5_te('New equipment, finished projects and offers are posted on our page first. Follow us to see them as soon as they are out.')?></p>
				<ul class="fb-points">
					<li><svg class="ic"><use href="#i-check"/></svg><?=o5_te('New arrivals and installations')?></li>
					<li><svg class="ic"><use href="#i-check"/></svg><?=o5_te('Photos and videos from our workshop')?></li>
					<li><svg class="ic"><use href="#i-check"/></svg><?=o5_te('Message us directly on Facebook')?></li>
				</ul>
				<div class="fb-actions">
					<a href="<?=o5_e($fb_page)?>" target="_blank" rel="noopener" class="btn-solid btn-glow" data-magnetic><span><?=o5_te('Follow our page')?></span><svg class="ic"><use href="#i-arrow"/></svg></a>
					<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" class="btn-link btn-link-light"><svg class="ic"><use href="#i-whatsapp"/></svg><?=o5_te('WhatsApp')?></a><?php } ?>
				</div>
			</div>
			<div class="fb-feed" id="fb-feed">
				<!-- Facebook page plugin; the card under it stays until the plugin has loaded (ad blockers / plugin blank) -->
				<iframe src="<?=o5_e($fb_plugin)?>" title="<?=o5_te('Facebook page')?>" allow="encrypted-media" referrerpolicy="origin" onload="this.parentNode.classList.add('fb-live')"></iframe>
				<?php $this->load->view('include/_fb_card', array('fb_page' => $fb_page, 'photos' => $fb_photos, 'labels' => array('name' => $is_ar ? 'مؤسسة طرابلسي للتجارة والصناعة' : 'Traboulsi Est.', 'follow' => o5_t('Follow'), 'all' => o5_t('See all posts on Facebook'), 'aria' => o5_t('Latest posts of Traboulsi Est. on Facebook')))); ?>
			</div>
		</div>
		<?php } ?>
	</div>
</section>
<?php } ?>
<?php } ?>

<?php if($only === '' || $only === 'faq'){ ?>
<!-- ============ FAQ ============ -->
<?php if(count($faq)){ ?>
<section id="faq" class="faq">
	<div class="container">
		<div class="faq-grid">
			<div class="faq-intro" data-reveal="left">
				<p class="eyebrow"><?=o5_te('FAQ')?></p>
				<h2 class="h2"><?=o5_te('Frequently asked questions')?></h2>
				<p class="lead"><?=o5_te('Answers to what our customers ask most. Anything else? Call us or send a message.')?></p>
				<div class="faq-help">
					<span class="faq-help-mark" aria-hidden="true">?</span>
					<p class="faq-help-title"><?=o5_te('Still have a question?')?></p>
					<p class="faq-help-text"><?=o5_te('Talk to our team directly, we will be glad to help with your project.')?></p>
					<div class="faq-help-actions">
						<?php if($call_num){ ?><a href="<?=o5_e($call_href)?>" class="faq-help-btn"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o5_e($call_num)?></span></a><?php } ?>
						<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" class="faq-help-btn is-wa"><svg class="ic"><use href="#i-whatsapp"/></svg><span><?=o5_te('WhatsApp')?></span></a><?php } ?>
						<a href="<?=$c?>#contact" class="faq-help-btn is-light"><svg class="ic"><use href="#i-mail"/></svg><span><?=o5_te('Send a message')?></span></a>
					</div>
				</div>
			</div>
			<div class="faq-list" data-reveal="right" style="--d:120ms">
				<?php foreach($faq as $i => $qa){ ?>
				<details class="faq-item" name="faq"<?=$i == 0 ? ' open' : ''?>>
					<summary><span class="faq-num"><?=sprintf('%02d', $i + 1)?></span><span class="faq-q"><?=o5_e($qa[0])?></span><span class="faq-toggle" aria-hidden="true"><svg class="ic"><use href="#i-plus"/></svg></span></summary>
					<div class="faq-a"><p><?=o5_e($qa[1])?></p></div>
				</details>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
<?php } ?>
<?php } ?>

<?php if(in_array($only, array('products', 'services', 'about', 'clients'))) include APPPATH.'views/option5/_why.php'; ?>

<?php // the Arabic services and FAQ are on the Arabic page (yoursite/ar); the English page stays in English ?>

<?php } ?>
<?php if(!$is_gallery){ // the contact form closes the home page and the sector, category, product, export and guide pages ?>
<!-- ============ Contact ============ -->
<section id="contact" class="contact">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<?=o5_tag('<p class="eyebrow">', $hp['contact_eyebrow'], '</p>')?>
			<?=o5_tag('<h2 class="h2">', $hp['contact_title'], '</h2>')?>
			<?=o5_tag('<p class="lead">', $hp['contact_text'], '</p>')?>
		</div>

		<div class="contact-grid">
			<aside class="info" data-reveal="left">
				<h3><?=o5_e($company)?></h3>
				<?php if(count($contact_items)){ ?>
				<ul class="info-list">
					<?php foreach($contact_items as $item){
						$label = strtolower($item['label']);
						$icon = $item['type'] == 'mailto' ? 'mail' : (strpos($label, 'fax') !== false ? 'fax' : 'phone'); ?>
					<li>
						<svg class="ic"><use href="#i-<?=$icon?>"/></svg>
						<div><small><?=o5_te($item['label'])?></small><a href="<?=o5_e($item['href'])?>"<?=$item['type'] == 'tel' ? ' class="tel"' : ''?>><?=o5_e($item['value'])?></a></div>
					</li>
					<?php } ?>
					<?php foreach($locations as $location){ ?>
					<li>
						<svg class="ic"><use href="#i-pin"/></svg>
						<div><small><?=o5_te('Branch')?></small><span><?=o5_te($location)?></span><?php $role = branch_role($location); if($role !== ''){ ?><span class="branch-role"><?=o5_te($role)?></span><?php } ?></div>
					</li>
					<?php } ?>
				</ul>
				<?php }else{ ?>
				<div class="info-raw"><?=$contact_us['description']?></div>
				<?php } ?>
				<div class="info-map">
					<iframe src="<?=o5_e($map_src)?>" title="<?=o5_te('Map')?> — <?=o5_te('Deir El Zahrani, Lebanon')?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
				</div>
			</aside>

			<form id="main-contact-form" class="form" name="contact-form" method="post" action="<?=o5_e($page_url)?>#contact" data-reveal="right" style="--d:120ms">
				<?=o5_tag('<h3>', $hp['contact_form_title'], '</h3>')?>
				<?php if($valid == 'not' && validation_errors()){ ?>
				<div class="alert alert-error" role="alert"><?=validation_errors()?></div>
				<?php } ?>
				<?php if($valid == 'true'){ ?>
				<div class="alert alert-success" role="status"><svg class="ic"><use href="#i-check"/></svg><?=o5_te('Thank you! Your message has been sent. We\'ll get back to you shortly.')?></div>
				<?php } ?>
				<div class="form-row">
					<label class="field"><span><?=o5_te('Full name *')?></span><input type="text" name="name" value="<?=$form['name']?>" autocomplete="name" required></label>
					<label class="field"><span><?=o5_te('Email *')?></span><input type="email" name="email" value="<?=$form['email']?>" autocomplete="email" required dir="ltr"></label>
				</div>
				<div class="form-row">
					<label class="field"><span><?=o5_te('Phone *')?></span><input type="tel" name="telephone" value="<?=$form['telephone']?>" autocomplete="tel" required dir="ltr"></label>
					<label class="field"><span><?=o5_te('Company name *')?></span><input type="text" name="company" value="<?=$form['company']?>" autocomplete="organization" required></label>
				</div>
				<label class="field"><span><?=o5_te('Subject *')?></span><input type="text" name="subject" id="subjectField" value="<?=$form['subject'] !== '' ? $form['subject'] : o5_e($ask_subject)?>" required></label>
				<label class="field"><span><?=o5_te('Message *')?></span><textarea name="message" id="message" rows="5" required><?=$form['message']?></textarea></label>
				<button type="submit" name="submit" class="btn-solid btn-glow"><span><?=o5_te('Send message')?></span><svg class="ic"><use href="#i-arrow"/></svg></button>
			</form>
		</div>
	</div>
</section>

<?php } ?>
</main>

<!-- ============ Footer ============ -->
<footer class="footer">
	<div class="container footer-grid">
		<div class="footer-col footer-brand">
			<img src="<?=$base?>images/logo.png" width="222" height="40" alt="<?=o5_e($company)?>" loading="lazy">
			<?=$is_ar ? o5_tag('<p>', $hp['footer_text_ar'], '</p>') : o5_tag('<p>', $hp['footer_text'], '</p>')?>
			<a href="<?=$c?>#contact" class="btn-solid btn-glow footer-cta" data-magnetic><span><?=o5_te('Request a quote')?></span><svg class="ic"><use href="#i-arrow"/></svg></a>
		</div>
		<div class="footer-col" data-collapse>
			<button type="button" class="footer-title" aria-expanded="false"><?=o5_te('Company')?><svg class="ic plus"><use href="#i-plus"/></svg><svg class="ic minus"><use href="#i-minus"/></svg></button>
			<ul>
				<li><a href="<?=o5_e($nav_url['about'])?>"><?=o5_te('About us')?></a></li>
				<?php if(isset($nav['services'])){ ?><li><a href="<?=o5_e($nav_url['services'])?>"><?=o5_te('Services')?></a></li><?php } ?>
				<li><a href="<?=o5_e($nav_url['clients'])?>"><?=o5_te('Our clients')?></a></li>
				<?php if(count($showroom)){ ?><?php } ?>
				<li><a href="<?=o5_e($photos_url)?>"><?=o5_te('Photos')?></a></li>
				<?php if(count($videos)){ ?><li><a href="<?=o5_e($videos_url)?>"><?=o5_te('Videos')?></a></li><?php }elseif(isset($nav['videos'])){ ?><li><a href="<?=$a?>#videos"><?=o5_te('Videos')?></a></li><?php } ?>
				<?php if(isset($nav['faq'])){ ?><li><a href="<?=o5_e($nav_url['faq'])?>"><?=o5_te('FAQ')?></a></li><?php } ?>
				<li><a href="<?=o5_e(o5_url('export'))?>"><?=o5_te('Delivery & export')?></a></li>
				<?php if(!isset($guide_count) || $guide_count){ ?><li><a href="<?=o5_e(o5_url('guides'))?>"><?=o5_te('Guides')?></a></li><?php } ?>
				<li><a href="<?=o5_e($nav_url['contact'])?>"><?=o5_te('Contact us')?></a></li>
				<li><a href="<?=$is_ar ? $url_en : $url_ar?>" lang="<?=$is_ar ? 'en' : 'ar'?>" dir="<?=$is_ar ? 'ltr' : 'rtl'?>"><?=$is_ar ? 'English' : 'النسخة العربية'?></a></li>
			</ul>
		</div>
		<div class="footer-col" data-collapse>
			<button type="button" class="footer-title" aria-expanded="false"><?=o5_te('Products')?><svg class="ic plus"><use href="#i-plus"/></svg><svg class="ic minus"><use href="#i-minus"/></svg></button>
			<ul>
				<?php foreach($categories as $category){
					if(!$cat_counts[$category['id_category']]) continue; ?>
				<li><a href="<?=o5_e(o5_url('products/'.$category['slug']))?>"><?=o5_e($cat_names[$category['id_category']])?></a></li>
				<?php } ?>
			</ul>
		</div>
		<div class="footer-col" data-collapse>
			<button type="button" class="footer-title" aria-expanded="false"><?=o5_te('Contacts')?><svg class="ic plus"><use href="#i-plus"/></svg><svg class="ic minus"><use href="#i-minus"/></svg></button>
			<ul class="footer-contacts">
				<?php if($contact['phone']){ ?><li><a href="<?=o5_e($contact['phone_href'])?>"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o5_e($contact['phone'])?></span></a></li><?php } ?>
				<?php if($contact['mobile']){ ?><li><a href="<?=o5_e($contact['mobile_href'])?>"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o5_e($contact['mobile'])?></span></a></li><?php } ?>
				<?php if($contact['email']){ ?><li><a href="mailto:<?=o5_e($contact['email'])?>"><svg class="ic"><use href="#i-mail"/></svg><?=o5_e($contact['email'])?></a></li><?php } ?>
				<?php foreach($locations as $location){ ?><li><span><svg class="ic"><use href="#i-pin"/></svg><?=o5_te($location)?></span></li><?php } ?>
			</ul>
		</div>
		<div class="footer-col footer-follow">
			<p class="footer-title"><?=o5_te('Follow us')?></p>
			<div class="footer-social">
				<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=o5_e($contact_us['facebook'])?>" target="_blank" rel="noopener" aria-label="<?=o5_te('Facebook')?>"><svg class="ic"><use href="#i-facebook"/></svg></a><?php } ?>
				<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=o5_e($contact_us['instagram'])?>" target="_blank" rel="noopener" aria-label="Instagram"><svg class="ic"><use href="#i-instagram"/></svg></a><?php } ?>
				<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="<?=o5_te('WhatsApp')?>"><svg class="ic"><use href="#i-whatsapp"/></svg></a><?php } ?>
			</div>
		</div>
	</div>
	<?php if(count($sectors)){ ?>
	<nav class="container footer-sectors" aria-label="<?=o5_te('What we equip')?>">
		<b><?=o5_te('What we equip')?></b>
		<?php foreach($sectors as $s){ ?><a href="<?=o5_e(o5_url($s['path']))?>"><?=o5_e($s[$o5_lang]['name'])?></a><?php } ?>
	</nav>
	<?php } ?>
	<div class="footer-giant" aria-hidden="true" data-reveal="up"><?php $giant = $is_ar ? 'طرابلسي' : 'TRABOULSIEST'; ?><span class="giant-glow"><?=$giant?></span><span class="giant-text"><?=$giant?></span></div>
	<div class="copyright">
		<p>&copy; <?=date('Y')?> <?=o5_te($hp['footer_copyright'])?><?php if($contact['phone']){ ?> &nbsp;|&nbsp; <?=o5_te('Tel:')?> <span class="tel"><?=o5_e($contact['phone'])?></span><?php } ?><?php if($contact['email']){ ?> &nbsp;|&nbsp; <?=o5_e($contact['email'])?><?php } ?></p>
		<p><?=o5_te('Powered by')?> <a href="https://creativelebanon.com/" target="_blank" rel="noopener">CreativeLebanon</a></p>
	</div>
</footer>

<?php if($contact['whatsapp']){ ?>
<a class="wa-float" href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="<?=o5_te('Chat with us on WhatsApp')?>"><svg class="ic"><use href="#i-whatsapp"/></svg></a>
<?php } ?>
<a class="to-top" id="toTop" href="#<?=$page === 'home' ? 'home' : 'top'?>" aria-label="<?=o5_te('Back to top')?>"><svg class="ic"><use href="#i-up"/></svg></a>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-labelledby="lbTitle" hidden>
	<div class="lb-box">
		<button type="button" class="lb-btn lb-close" aria-label="<?=o5_te('Close')?>"><svg class="ic"><use href="#i-x"/></svg></button>
		<div class="lb-img">
			<button type="button" class="lb-btn lb-prev" aria-label="<?=o5_te('Previous')?>"><svg class="ic"><use href="#i-left"/></svg></button>
			<img id="lbImg" alt="">
			<button type="button" class="lb-btn lb-next" aria-label="<?=o5_te('Next')?>"><svg class="ic"><use href="#i-right"/></svg></button>
		</div>
		<div class="lb-text">
			<span class="p-cat" id="lbCat"></span>
			<h3 id="lbTitle"<?=$is_ar ? ' dir="ltr"' : ''?>></h3>
			<p id="lbDesc"></p>
			<div class="lb-foot">
				<button type="button" class="btn-solid" id="lbAsk" data-label-product="<?=o5_te('Ask about this product')?>" data-label-project="<?=o5_te('Ask about a similar project')?>"><span><?=o5_te('Ask about this product')?></span><svg class="ic"><use href="#i-arrow"/></svg></button>
				<span class="lb-count" id="lbCount"></span>
			</div>
		</div>
	</div>
</div>
