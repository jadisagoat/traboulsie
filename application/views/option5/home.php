<?php
/* Traboulsi Est. — option 5: the bilingual design (English yoursite/ , Arabic yoursite/ar).
   Catalogue layout of option 4 plus the services and FAQ sections, a language switch and a
   full right-to-left Arabic page. Same data as the other options, see controllers/home.php;
   the Arabic labels are in config/site_lang.php. */
if(!function_exists('o5_e')){
	function o5_e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8', false); }
	// the Arabic version of a label on the Arabic page, the label itself otherwise
	function o5_t($s){
		$s = (string)$s;
		if(empty($GLOBALS['o5_lang']) || $GLOBALS['o5_lang'] != 'ar') return $s;
		$k = trim(preg_replace('/\s+/u', ' ', html_entity_decode($s, ENT_QUOTES, 'UTF-8')));
		return isset($GLOBALS['o5_dict'][$k]) ? $GLOBALS['o5_dict'][$k] : $s;
	}
	// translated + escaped
	function o5_te($s){ return o5_e(o5_t($s)); }
	// product name on the Arabic page: "Showcase Fridge 108" -> "برّاد عرض 108"
	function o5_pname($s){
		if(empty($GLOBALS['o5_lang']) || $GLOBALS['o5_lang'] != 'ar') return $s;
		$num = preg_match('/\s(\d{1,4})\s*$/', $s, $m) ? $m[1] : '';
		$type = trim($num === '' ? $s : substr($s, 0, -strlen($m[0])));
		foreach($GLOBALS['o5_pdict'] as $en => $ar){ if(strcasecmp($en, $type) == 0) return trim($ar.' '.$num); }
		return $s;
	}
	function o5_title($s){
		$s = ucwords(strtolower(preg_replace('/\s+/', ' ', trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8')))));
		return preg_replace_callback('/(?<=\s)(And|Or|Of|For|The)(?=\s)/', function($m){ return strtolower($m[1]); }, $s);
	}
	function o5_name($s){ return ucfirst(preg_replace('/\s+/', ' ', trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8')))); }
	function o5_icon($title, $map, $default){
		$t = strtolower($title);
		foreach($map as $needle => $icon){ if(preg_match('/'.$needle.'/', $t)) return $icon; }
		return $default;
	}
	// a back office text (translated on the Arabic page) inside its tag, or nothing when empty
	function o5_tag($open, $text, $close){ return trim($text) === '' ? '' : $open.o5_te($text).$close; }
	function o5_or($text, $default){ return trim($text) === '' ? $default : $text; }
	function o5_num($typed, $auto){ $n = preg_replace('/\D/', '', $typed); return $n === '' ? $auto : (int)$n; }
	// hero title: one line per line typed in the back office, each rising from behind a mask, [words] in red
	function o5_lines($s){
		$out = '';
		foreach(preg_split('/\s*\R\s*/u', trim($s)) as $i => $line){
			$line = preg_replace('/\[(.+?)\]/su', '<em>$1</em>', o5_te($line));
			$out .= '<span class="line"><span style="--i:'.$i.'">'.$line.'</span></span> ';
		}
		return $out;
	}
	function o5_lines_split($text){ return preg_split('/\s*\R\s*/u', trim((string)$text), -1, PREG_SPLIT_NO_EMPTY); }
	function o5_faq($text){
		$out = array();
		foreach(o5_lines_split($text) as $line){
			if(strpos($line, '|') === FALSE) continue;
			list($q, $a) = array_map('trim', explode('|', $line, 2));
			if($q !== '' && $a !== '') $out[] = array($q, $a);
		}
		return $out;
	}
}
$o5_lang = isset($lang) && $lang == 'ar' ? 'ar' : 'en';
$is_ar = $o5_lang == 'ar';
$this->config->load('site_lang', TRUE);
$o5_dict = $this->config->item('site_lang_ar', 'site_lang');
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
$features = array();
for($n = 1; $n <= 4; $n++){
	if(trim($hp['feature_'.$n.'_title']) !== '') $features[] = array('title' => $hp['feature_'.$n.'_title'], 'text' => $hp['feature_'.$n.'_text']);
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

$nav = array('home' => 'Home', 'products' => 'Products', 'services' => 'Services', 'about' => 'About', 'clients' => 'Our clients', 'showroom' => 'Showroom', 'videos' => 'Videos', 'faq' => 'FAQ', 'contact' => 'Contact');
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
		<img src="<?=$base?>images/logo.png" width="300" height="54" alt="<?=o5_e($company)?>">
		<b class="loader-year" id="loaderYear" data-from="<?=$founded ? $founded : 1982?>" data-to="<?=date('Y')?>"><?=$founded ? $founded : 1982?></b>
		<p class="loader-tag"><?=$is_ar ? 'صناعة لبنانية منذ '.($founded ? $founded : 1982) : 'Made in Lebanon since '.($founded ? $founded : 1982)?></p>
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
			<a href="#home" class="brand" aria-label="<?=o5_e($company)?>">
				<img src="<?=$base?>images/logo.png" width="300" height="54" alt="<?=o5_e($company)?><?=$founded ? ' — '.o5_t('Since').' '.$founded : ''?>">
			</a>

			<nav class="nav" id="nav" aria-label="<?=o5_te('Main')?>">
				<ul>
					<?php foreach($nav as $id => $label){
						if($id == 'products'){ ?>
					<li class="has-drop">
						<a href="#products"><?=o5_te($label)?><svg class="ic caret"><use href="#i-down"/></svg></a>
						<div class="drop">
							<ul>
								<li><a href="#products" data-filter-link="*"><?=o5_te('All products')?> <small><?=$product_total?></small></a></li>
								<?php foreach($categories as $category){
									if(!$cat_counts[$category['id_category']]) continue; ?>
								<li><a href="#products" data-filter-link="prod<?=$category['id_category']?>"><?=o5_e($cat_names[$category['id_category']])?> <small><?=$cat_counts[$category['id_category']]?></small></a></li>
								<?php } ?>
							</ul>
						</div>
					</li>
					<?php }else{ ?>
					<li><a href="#<?=$id?>"><?=o5_te($label)?></a></li>
					<?php } } ?>
				</ul>
				<div class="nav-mobile-info">
					<?php if($call_num){ ?><a href="<?=o5_e($call_href)?>" class="tel"><svg class="ic"><use href="#i-phone"/></svg><?=o5_e($call_num)?></a><?php } ?>
					<a class="lang-switch" href="<?=$is_ar ? $url_en : $url_ar?>" lang="<?=$is_ar ? 'en' : 'ar'?>" dir="<?=$is_ar ? 'ltr' : 'rtl'?>"><svg class="ic"><use href="#i-globe"/></svg><?=$is_ar ? 'English' : 'عربي'?></a>
				</div>
			</nav>

			<div class="navbar-side">
				<a class="lang-switch lang-switch-nav" href="<?=$is_ar ? $url_en : $url_ar?>" lang="<?=$is_ar ? 'en' : 'ar'?>" dir="<?=$is_ar ? 'ltr' : 'rtl'?>" aria-label="<?=$is_ar ? 'English version' : 'النسخة العربية'?>"><?=$is_ar ? 'EN' : 'عربي'?></a>
				<button type="button" class="icon-btn" id="searchToggle" aria-label="<?=o5_te('Search a product')?>"><svg class="ic"><use href="#i-search"/></svg></button>
				<button type="button" class="burger" id="menuToggle" aria-controls="nav" aria-expanded="false" aria-label="<?=o5_te('Open menu')?>"><span></span><span></span><span></span></button>
			</div>
		</div>
	</div>
	<div class="progress" aria-hidden="true"><span id="scrollBar"></span></div>
</header>

<main id="main">

<!-- ============ Slider ============ -->
<section id="home" class="slider" aria-roledescription="carousel" aria-label="<?=o5_e($company)?>">
	<div class="slides">
		<?php foreach($hero_images as $i => $image){ ?>
		<div class="slide<?=$i == 0 ? ' is-active' : ''?>"><img src="<?=$base.o5_e($image['src'])?>" alt="<?=o5_te($image['title'])?>" <?=$i == 0 ? 'fetchpriority="high"' : 'fetchpriority="low"'?>></div>
		<?php } ?>
	</div>
	<div class="slider-shade" aria-hidden="true"></div>
	<canvas class="hero-sparks" id="heroSparks" aria-hidden="true"></canvas>
	<div class="hero-stamp" aria-hidden="true">
		<svg viewBox="0 0 200 200"><defs><path id="stampPath" d="M100,100 m-80,0 a80,80 0 1,1 160,0 a80,80 0 1,1 -160,0"/></defs><text><textPath href="#stampPath"<?=$is_ar ? '' : ' textLength="490"'?>><?php if($is_ar){ ?>صناعة لبنانية • منذ <?=$founded ? $founded : 1982?> • صناعة لبنانية • منذ <?=$founded ? $founded : 1982?> • <?php }else{ ?>MADE IN LEBANON • SINCE <?=$founded ? $founded : 1982?> • TRABOULSI EST. • <?php } ?></textPath></text></svg>
		<span class="stamp-core"><b><?=$founded ? $founded : 1982?></b><small><?=$is_ar ? 'تأسست' : 'EST.'?></small></span>
	</div>
	<div class="hero-chips" aria-hidden="true">
		<span class="hero-chip chip-factory"><i class="live-dot"></i><span><b><?=o5_te('Factory')?></b><small><?=o5_te('Deir El Zahrani')?></small></span></span>
		<span class="hero-chip chip-branch"><svg class="ic"><use href="#i-pin"/></svg><span><b><?=o5_te('Branch')?></b><small><?=o5_te('Khalde · Beirut')?></small></span></span>
	</div>
	<div class="container slider-caption">
		<?=o5_tag('<p class="hero-eyebrow">', $hp['hero_eyebrow'], '</p>')?>
		<?php if($is_ar && trim($hp['hero_title_ar']) !== ''){ ?><h1 class="hero-title"><?=o5_lines($hp['hero_title_ar'])?></h1>
		<?php }elseif(trim($hp['hero_title']) !== ''){ ?><h1 class="hero-title"><?=o5_lines($hp['hero_title'])?></h1><?php } ?>
		<?php if($is_ar && trim($hp['hero_text_ar']) !== ''){ ?><p class="hero-text"><?=o5_e($hp['hero_text_ar'])?></p><?php } ?>
		<div class="hero-actions">
			<?=o5_tag('<a href="#products" class="btn-more btn-white" data-magnetic><span>', $hp['hero_button_1'], '</span><svg class="ic"><use href="#i-plus"/></svg></a>')?>
			<?=o5_tag('<a href="#contact" class="btn-link btn-link-light">', $hp['hero_button_2'], '<svg class="ic"><use href="#i-arrow"/></svg></a>')?>
		</div>
	</div>
	<?php if(count($hero_images) > 1){ ?>
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

<!-- ============ Two crossing bands with what we do ============ -->
<?php $tape_words = $is_ar ? array('تصنيع', 'برادات', 'ستانلس ستيل', 'شوايات', 'غرف تبريد', 'معدات مطابخ', 'تركيب', 'صيانة', 'صناعة لبنانية') : array('Manufacturing', 'Refrigeration', 'Stainless steel', 'Grills', 'Cold rooms', 'Kitchen equipment', 'Installation', 'Maintenance', 'Made in Lebanon'); ?>
<div class="tapes" aria-hidden="true">
	<div class="tape tape-a"><div class="tape-track"><?php for($copy = 0; $copy < 2; $copy++){ foreach($tape_words as $word){ ?><span><?=o5_e($word)?></span><i>✦</i><?php } } ?></div></div>
	<div class="tape tape-b"><div class="tape-track"><?php for($copy = 0; $copy < 2; $copy++){ foreach(array_reverse($tape_words) as $word){ ?><span><?=o5_e($word)?></span><i>✦</i><?php } } ?></div></div>
</div>

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
			<a class="cat-tile" href="#products" data-filter-link="*" data-reveal="up" data-tilt>
				<svg class="cat-icon"><use href="#i-all"/></svg>
				<h2><?=o5_te('All products')?></h2>
				<span><?=$product_total?> <?=o5_te('products')?></span>
			</a>
			<?php foreach($categories as $i => $category){
				$id = $category['id_category'];
				$count = $cat_counts[$id]; ?>
			<a class="cat-tile" data-reveal="up" data-tilt style="--d:<?=($i + 1) * 110?>ms" <?php if($count){ ?>href="#products" data-filter-link="prod<?=$id?>"<?php }else{ ?>href="#contact"<?php } ?>>
				<svg class="cat-icon"><use href="#i-<?=o5_icon($category['title'], $cat_icons, 'layers')?>"/></svg>
				<h2><?=o5_e($cat_names[$id])?></h2>
				<span><?=$count ? $count.' '.o5_t('products') : o5_t('Made to order')?></span>
			</a>
			<?php } ?>
		</div>
	</div>
</section>

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
				<a class="p-img<?=($size && $size[1] < 380) ? ' is-small' : ''?>" href="<?=$base.$big.o5_e($product['image'])?>" aria-label="<?=o5_te('View')?> <?=o5_e($name)?>" data-cursor="<?=o5_te('View')?>">
					<span class="p-disc" aria-hidden="true"></span>
					<?php if(preg_match('/(\d{2,4})\s*$/', $product['title'], $ref)){ ?><span class="p-ghost" aria-hidden="true"><?=$ref[1]?></span><?php } ?>
					<img src="<?=$base.$big.o5_e($product['image'])?>" alt="<?=o5_e($alt)?>" loading="lazy" style="--max-h:<?=$max_h?>px"<?php if($size){ ?> width="<?=$size[0]?>" height="<?=$size[1]?>"<?php } ?>>
				</a>
				<div class="p-text">
					<span class="p-cat"><?=o5_e($cat)?></span>
					<h3 class="p-name"><?=o5_e($name)?></h3>
					<p class="p-desc"><?=o5_e($text)?></p>
					<div class="p-actions">
						<button type="button" class="btn-more" data-more><span><?=o5_te('More')?></span><svg class="ic"><use href="#i-plus"/></svg></button>
						<button type="button" class="btn-link" data-ask><?=o5_te('Ask for a quote')?><svg class="ic"><use href="#i-arrow"/></svg></button>
					</div>
				</div>
			</div>
		</article>
		<?php } ?>
	</div>

	<div class="container">
		<p class="empty" id="productsEmpty" hidden><?=o5_te('No products match your search.')?> <a href="#contact"><?=o5_te('Ask us directly')?></a><?=o5_te(', we may have it in stock or make it to order.')?></p>
		<div class="more" id="loadMoreWrap">
			<p><?=o5_te('Showing')?> <b id="shownCount"><?=$product_total?></b> <?=o5_te('of')?> <b id="totalCount"><?=$product_total?></b> <?=o5_te('products')?></p>
			<button type="button" class="btn-more btn-wide" id="loadMore" hidden><span><?=o5_te('Load more products')?></span><svg class="ic"><use href="#i-plus"/></svg></button>
		</div>
	</div>
</section>

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
			<li class="service" data-reveal="up" style="--d:<?=($i % 4) * 90?>ms">
				<span class="s-num" aria-hidden="true"><?=str_pad($i + 1, 2, '0', STR_PAD_LEFT)?></span>
				<svg class="s-icon"><use href="#i-<?=$service_icons[$i % count($service_icons)]?>"/></svg>
				<h3><?=o5_e($service)?></h3>
			</li>
			<?php } ?>
		</ul>
	</div>
</section>
<?php } ?>

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
				<?=o5_tag('<a href="#contact" class="btn-solid btn-glow" data-magnetic><span>', $hp['cta_button'], '</span><svg class="ic"><use href="#i-arrow"/></svg></a>')?>
				<?php if($call_num){ ?><a href="<?=o5_e($call_href)?>" class="cta-call"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o5_e($call_num)?></span></a><?php } ?>
			</div>
		</div>
	</div>
</section>

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
				<span class="f-icon"><svg class="ic" viewBox="0 0 24 24"><path class="f-tick" d="M20 6 9 17l-5-5"/></svg></span>
				<h3><?=o5_te($feature['title'])?></h3>
				<?=o5_tag('<p>', $feature['text'], '</p>')?>
				<span class="f-bar" aria-hidden="true"></span>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
</section>

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

<!-- ============ Showroom ============ -->
<?php if(count($showroom)){ ?>
<section id="showroom" class="showroom">
	<div class="container">
		<div class="row-title" data-reveal="up">
			<h2 class="h3"><?=o5_te(o5_or($hp['showroom_title'], 'Our showroom'))?></h2>
			<div class="row-arrows">
				<button type="button" class="arrow-btn" data-scroll="-1" aria-label="<?=o5_te('Previous photos')?>"><svg class="ic"><use href="#i-left"/></svg></button>
				<button type="button" class="arrow-btn" data-scroll="1" aria-label="<?=o5_te('Next photos')?>"><svg class="ic"><use href="#i-right"/></svg></button>
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
			<?php if(count($videos) > 1){ ?>
			<div class="row-arrows">
				<button type="button" class="arrow-btn" data-scroll="-1" data-track="videoTrack" aria-label="<?=o5_te('Previous videos')?>"><svg class="ic"><use href="#i-left"/></svg></button>
				<button type="button" class="arrow-btn" data-scroll="1" data-track="videoTrack" aria-label="<?=o5_te('Next videos')?>"><svg class="ic"><use href="#i-right"/></svg></button>
			</div>
			<?php } ?>
		</div>
		<p class="lead" data-reveal="up" style="--d:100ms"><?=o5_te(o5_or($hp['videos_text'], 'Installations, new arrivals and our workshop, straight from our Facebook page.'))?></p>

		<?php if(count($videos)){ ?>
		<div class="video-track" id="videoTrack">
			<?php foreach($videos as $i => $video){ ?>
			<figure class="video-card<?=$video['vertical'] ? ' is-vertical' : ''?>" data-reveal="up" style="--d:<?=min($i, 3) * 120?>ms">
				<div class="video-frame" data-cursor="<?=o5_te('Play')?>">
					<iframe src="<?=o5_e($video['src'])?>" title="<?=o5_e($video['title'])?>" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowfullscreen></iframe>
				</div>
				<figcaption>
					<h3><?=o5_e($video['title'])?></h3>
					<?=o5_tag('<p>', (string)$video['description'], '</p>')?>
				</figcaption>
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
						<a href="#contact" class="faq-help-btn is-light"><svg class="ic"><use href="#i-mail"/></svg><span><?=o5_te('Send a message')?></span></a>
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

<?php // the Arabic services and FAQ are on the Arabic page (yoursite/ar); the English page stays in English ?>

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
				<label class="field"><span><?=o5_te('Subject *')?></span><input type="text" name="subject" id="subjectField" value="<?=$form['subject']?>" required></label>
				<label class="field"><span><?=o5_te('Message *')?></span><textarea name="message" id="message" rows="5" required><?=$form['message']?></textarea></label>
				<button type="submit" name="submit" class="btn-solid btn-glow"><span><?=o5_te('Send message')?></span><svg class="ic"><use href="#i-arrow"/></svg></button>
			</form>
		</div>
	</div>
</section>

</main>

<!-- ============ Footer ============ -->
<footer class="footer">
	<div class="container footer-grid">
		<div class="footer-col footer-brand">
			<img src="<?=$base?>images/logo.png" width="222" height="40" alt="<?=o5_e($company)?>" loading="lazy">
			<?=$is_ar ? o5_tag('<p>', $hp['footer_text_ar'], '</p>') : o5_tag('<p>', $hp['footer_text'], '</p>')?>
		</div>
		<div class="footer-col" data-collapse>
			<button type="button" class="footer-title" aria-expanded="false"><?=o5_te('Company')?><svg class="ic plus"><use href="#i-plus"/></svg><svg class="ic minus"><use href="#i-minus"/></svg></button>
			<ul>
				<li><a href="#about"><?=o5_te('About us')?></a></li>
				<?php if(isset($nav['services'])){ ?><li><a href="#services"><?=o5_te('Services')?></a></li><?php } ?>
				<li><a href="#clients"><?=o5_te('Our clients')?></a></li>
				<?php if(count($showroom)){ ?><li><a href="#showroom"><?=o5_te('Showroom')?></a></li><?php } ?>
				<?php if(isset($nav['videos'])){ ?><li><a href="#videos"><?=o5_te('Videos')?></a></li><?php } ?>
				<?php if(isset($nav['faq'])){ ?><li><a href="#faq"><?=o5_te('FAQ')?></a></li><?php } ?>
				<li><a href="#contact"><?=o5_te('Contact us')?></a></li>
				<li><a href="<?=$is_ar ? $url_en : $url_ar?>" lang="<?=$is_ar ? 'en' : 'ar'?>" dir="<?=$is_ar ? 'ltr' : 'rtl'?>"><?=$is_ar ? 'English' : 'النسخة العربية'?></a></li>
			</ul>
		</div>
		<div class="footer-col" data-collapse>
			<button type="button" class="footer-title" aria-expanded="false"><?=o5_te('Products')?><svg class="ic plus"><use href="#i-plus"/></svg><svg class="ic minus"><use href="#i-minus"/></svg></button>
			<ul>
				<?php foreach($categories as $category){
					if(!$cat_counts[$category['id_category']]) continue; ?>
				<li><a href="#products" data-filter-link="prod<?=$category['id_category']?>"><?=o5_e($cat_names[$category['id_category']])?></a></li>
				<?php } ?>
			</ul>
		</div>
		<div class="footer-col" data-collapse>
			<button type="button" class="footer-title" aria-expanded="false"><?=o5_te('Contacts')?><svg class="ic plus"><use href="#i-plus"/></svg><svg class="ic minus"><use href="#i-minus"/></svg></button>
			<ul>
				<?php if($contact['phone']){ ?><li><a href="<?=o5_e($contact['phone_href'])?>" class="tel"><?=o5_e($contact['phone'])?></a></li><?php } ?>
				<?php if($contact['mobile']){ ?><li><a href="<?=o5_e($contact['mobile_href'])?>" class="tel"><?=o5_e($contact['mobile'])?></a></li><?php } ?>
				<?php if($contact['email']){ ?><li><a href="mailto:<?=o5_e($contact['email'])?>"><?=o5_e($contact['email'])?></a></li><?php } ?>
				<?php foreach($locations as $location){ ?><li><span><?=o5_te($location)?></span></li><?php } ?>
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
	<div class="footer-giant" aria-hidden="true" data-reveal="up"><?php $giant = $is_ar ? 'طرابلسي' : 'TRABOULSIEST'; ?><span class="giant-glow"><?=$giant?></span><span class="giant-text"><?=$giant?></span></div>
	<div class="copyright">
		<p>&copy; <?=date('Y')?> <?=o5_te($hp['footer_copyright'])?><?php if($contact['phone']){ ?> &nbsp;|&nbsp; <?=o5_te('Tel:')?> <span class="tel"><?=o5_e($contact['phone'])?></span><?php } ?><?php if($contact['email']){ ?> &nbsp;|&nbsp; <?=o5_e($contact['email'])?><?php } ?></p>
		<p><?=o5_te('Powered by')?> <a href="https://creativelebanon.com/" target="_blank" rel="noopener">CreativeLebanon</a></p>
	</div>
</footer>

<?php if($contact['whatsapp']){ ?>
<a class="wa-float" href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="<?=o5_te('Chat with us on WhatsApp')?>"><svg class="ic"><use href="#i-whatsapp"/></svg></a>
<?php } ?>
<a class="to-top" id="toTop" href="#home" aria-label="<?=o5_te('Back to top')?>"><svg class="ic"><use href="#i-up"/></svg></a>

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
				<button type="button" class="btn-solid" id="lbAsk"><span><?=o5_te('Ask about this product')?></span><svg class="ic"><use href="#i-arrow"/></svg></button>
				<span class="lb-count" id="lbCount"></span>
			</div>
		</div>
	</div>
</div>
