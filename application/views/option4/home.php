<?php
/* Traboulsi Est. — option 4 (catalogue design, products shown as alternating image / text rows).
   Same data as the other options, see controllers/home.php */
if(!function_exists('o4_e')){
	function o4_e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8', false); }
	// "STAINLESS, PROCESSING AND OTHERS" -> "Stainless, Processing and Others"
	function o4_title($s){
		$s = ucwords(strtolower(preg_replace('/\s+/', ' ', trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8')))));
		return preg_replace_callback('/(?<=\s)(And|Or|Of|For|The)(?=\s)/', function($m){ return strtolower($m[1]); }, $s);
	}
	// "steak machine 251" -> "Steak machine 251", like the reference design
	function o4_name($s){ return ucfirst(strtolower(preg_replace('/\s+/', ' ', trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8'))))); }
	function o4_icon($title, $map, $default){
		$t = strtolower($title);
		foreach($map as $needle => $icon){ if(preg_match('/'.$needle.'/', $t)) return $icon; }
		return $default;
	}
	// a back office text inside its tag, or nothing when it was left empty
	function o4_tag($open, $text, $close){ return trim($text) === '' ? '' : $open.o4_e($text).$close; }
	function o4_or($text, $default){ return trim($text) === '' ? $default : $text; }
	function o4_num($typed, $auto){ $n = preg_replace('/\D/', '', $typed); return $n === '' ? $auto : (int)$n; }
	// hero title: one line per line typed in the back office, each rising from behind a mask, [words] in red
	function o4_lines($s){
		$out = '';
		foreach(preg_split('/\s*\R\s*/u', trim($s)) as $i => $line){
			$line = preg_replace('/\[(.+?)\]/s', '<em>$1</em>', o4_e($line));
			$out .= '<span class="line"><span style="--i:'.$i.'">'.$line.'</span></span> ';
		}
		return $out;
	}
	// the product description, or a short line about its category when it is empty
	function o4_text($product, $cat_title, $texts){
		// English paragraph only: the Arabic paragraph is shown on its own line (o4_text_ar)
		$text = isset($product['text_en']) ? $product['text_en'] : trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags((string)$product['description']), ENT_QUOTES, 'UTF-8')));
		if($text !== ''){
			if(mb_strlen($text, 'UTF-8') > 260) $text = rtrim(mb_substr($text, 0, 250, 'UTF-8')).'…';
			return $text;
		}
		return o4_icon($cat_title, $texts, $texts['default']);
	}
}
$hp = $home_page;
$base = base_url();
$cat_icons = array('refrig|fridge|cold' => 'fridge', 'heat|oven|grill' => 'flame', 'stain|steel|process' => 'layers');
$client_icons = array('hospital' => 'medical', 'restaurant' => 'utensils', 'pastr|baker|sweet' => 'cake', 'supermarket' => 'cart');
$cat_texts = array(
	'refrig|fridge|cold' => 'Professional refrigeration for shops, restaurants, butcheries and pastry kitchens, supplied, installed and serviced by Traboulsi Est.',
	'heat|oven|grill' => 'Commercial cooking equipment built for long days of professional use, with installation and after-sales service by our own team.',
	'stain|steel|process' => 'Stainless-steel work and food processing machines for kitchens, butcheries and bakeries. Custom sizes are made to order.',
	'default' => 'Professional equipment for food businesses, supplied, installed and serviced by Traboulsi Est.',
);

$cat_names = array();
foreach($categories as $category) $cat_names[$category['id_category']] = o4_title($category['title']);
$product_total = count($products);

// "All" shows the categories in turn (fridge, cooker, stainless, fridge…) instead of 50 fridges first;
// each category keeps its own back office order
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
$stat_products = o4_num($hp['stat_products_number'], $product_total);
$stat_clients = o4_num($hp['stat_clients_number'], count($unique_clients));
$features = array();
for($n = 1; $n <= 4; $n++){
	if(trim($hp['feature_'.$n.'_title']) !== '') $features[] = array('title' => $hp['feature_'.$n.'_title'], 'text' => $hp['feature_'.$n.'_text']);
}
$storefront = is_file('uploads/about_us/tra.jpg') ? 'uploads/about_us/tra.jpg' : (is_file('uploads/about_us/530x400/tra.jpg') ? 'uploads/about_us/530x400/tra.jpg' : '');
// main About photo: the last banner (the showroom), the building photo sits over its corner
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

$form = array();
foreach(array('name', 'email', 'telephone', 'company', 'subject', 'message') as $field){
	$form[$field] = ($valid == 'true') ? '' : set_value($field);
}
$call_href = $contact['mobile'] ? $contact['mobile_href'] : $contact['phone_href'];
$call_num = $contact['mobile'] ? $contact['mobile'] : $contact['phone'];

// Facebook: the page of the back office (or the Contact Us link), shown with Facebook's own page plugin
$fb_page = trim(o4_or($hp['facebook_page'], isset($contact_us['facebook']) ? (string)$contact_us['facebook'] : ''));
if($fb_page !== '' && !preg_match('#^https?://(www\.|m\.|web\.)?facebook\.com/#i', $fb_page)) $fb_page = '';
$fb_plugin = $fb_page === '' ? '' : 'https://www.facebook.com/plugins/page.php?href='.rawurlencode($fb_page).'&tabs=timeline&width=500&height=600&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true';
$videos = isset($videos) ? $videos : array();

// photos of the Facebook card: showroom photos, then product photos
$fb_photos = array_slice($showroom, 0, 3);
foreach($products as $product){
	if(count($fb_photos) >= 7) break;
	$file = is_file('uploads/products/600x455/'.$product['image']) ? 'uploads/products/600x455/'.$product['image'] : 'uploads/products/290x220/'.$product['image'];
	if($product['image'] !== '' && is_file($file)) $fb_photos[] = array('src' => $file, 'title' => isset($product['alt']) ? $product['alt'] : $product['title']);
}

// photo of the parallax band: the last banner (or the first showroom photo)
$band_img = count($hero_images) ? $hero_images[count($hero_images) - 1]['src'] : (count($showroom) ? $showroom[0]['src'] : '');

$nav = array('home' => 'Home', 'products' => 'Products', 'about' => 'About', 'clients' => 'Our clients', 'showroom' => 'Showroom', 'videos' => 'Videos', 'contact' => 'Contact');
if(!count($showroom)) unset($nav['showroom']);
if(!count($videos) && !$fb_plugin) unset($nav['videos']);
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
	<symbol id="i-all" viewBox="0 0 48 48"><rect x="6" y="6" width="15" height="15" rx="2"/><rect x="27" y="6" width="15" height="15" rx="2"/><rect x="6" y="27" width="15" height="15" rx="2"/><rect x="27" y="27" width="15" height="15" rx="2"/></symbol>
	<symbol id="i-fridge" viewBox="0 0 48 48"><rect x="11" y="3" width="26" height="42" rx="3"/><line x1="11" y1="19" x2="37" y2="19"/><line x1="17" y1="9" x2="17" y2="14"/><line x1="17" y1="25" x2="17" y2="32"/></symbol>
	<symbol id="i-flame" viewBox="0 0 48 48"><path d="M17 29a5 5 0 0 0 5-5c0-2.8-1-4-2-6-2.1-4.3-.4-8.1 4-12 1 5 4 9.8 8 13 4 3.2 6 7 6 11a14 14 0 1 1-28 0c0-2.3.9-4.6 2-6a5 5 0 0 0 5 5z"/></symbol>
	<symbol id="i-layers" viewBox="0 0 48 48"><polygon points="24 4 4 14 24 24 44 14 24 4"/><polyline points="4 34 24 44 44 34"/><polyline points="4 24 24 34 44 24"/></symbol>
	<symbol id="i-medical" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></symbol>
	<symbol id="i-utensils" viewBox="0 0 24 24"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></symbol>
	<symbol id="i-cake" viewBox="0 0 24 24"><path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/><path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2-1 2-1"/><path d="M2 21h20"/><path d="M7 8v3"/><path d="M12 8v3"/><path d="M17 8v3"/></symbol>
	<symbol id="i-cart" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></symbol>
	<symbol id="i-building" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></symbol>
	<symbol id="i-whatsapp" viewBox="0 0 24 24"><path fill="currentColor" stroke="none" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></symbol>
</svg>

<a class="skip-link" href="#main">Skip to content</a>

<div class="loader" id="loader" aria-hidden="true">
	<div class="loader-panel"></div>
	<div class="loader-inner">
		<img src="<?=$base?>images/logo.png" width="300" height="54" alt="<?=o4_e(o4_or($hp['contact_company'], 'Traboulsi Est. for Trading & Industry'))?>">
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
				<?php if($contact['phone']){ ?><a href="<?=o4_e($contact['phone_href'])?>"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o4_e($contact['phone'])?></span></a><?php } ?>
				<?php if($contact['email']){ ?><a href="mailto:<?=o4_e($contact['email'])?>"><svg class="ic"><use href="#i-mail"/></svg><?=o4_e($contact['email'])?></a><?php } ?>
			</div>
			<div class="topbar-side">
				<?=o4_tag('<span class="ar" lang="ar" dir="rtl">', $hp['topbar_name_ar'], '</span>')?>
				<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=o4_e($contact_us['facebook'])?>" target="_blank" rel="noopener" aria-label="Facebook"><svg class="ic"><use href="#i-facebook"/></svg></a><?php } ?>
				<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=o4_e($contact_us['instagram'])?>" target="_blank" rel="noopener" aria-label="Instagram"><svg class="ic"><use href="#i-instagram"/></svg></a><?php } ?>
			</div>
		</div>
	</div>

	<div class="navbar">
		<div class="container navbar-inner">
			<a href="#home" class="brand" aria-label="Traboulsi Est. — home">
				<img src="<?=$base?>images/logo.png" width="300" height="54" alt="Traboulsi Est. — Since 1982">
			</a>

			<nav class="nav" id="nav" aria-label="Main">
				<ul>
					<?php foreach($nav as $id => $label){
						if($id == 'products'){ ?>
					<li class="has-drop">
						<a href="#products"><?=$label?><svg class="ic caret"><use href="#i-down"/></svg></a>
						<div class="drop">
							<ul>
								<li><a href="#products" data-filter-link="*">All products <small><?=$product_total?></small></a></li>
								<?php foreach($categories as $category){
									if(!$cat_counts[$category['id_category']]) continue; ?>
								<li><a href="#products" data-filter-link="prod<?=$category['id_category']?>"><?=o4_e($cat_names[$category['id_category']])?> <small><?=$cat_counts[$category['id_category']]?></small></a></li>
								<?php } ?>
							</ul>
						</div>
					</li>
					<?php }else{ ?>
					<li><a href="#<?=$id?>"><?=$label?></a></li>
					<?php } } ?>
				</ul>
				<div class="nav-mobile-info">
					<?php if($call_num){ ?><a href="<?=o4_e($call_href)?>" class="tel"><svg class="ic"><use href="#i-phone"/></svg><?=o4_e($call_num)?></a><?php } ?>
					<?=o4_tag('<p class="ar" lang="ar" dir="rtl">', $hp['topbar_name_ar'], '</p>')?>
				</div>
			</nav>

			<div class="navbar-side">
				<button type="button" class="icon-btn" id="searchToggle" aria-label="Search a product"><svg class="ic"><use href="#i-search"/></svg></button>
				<button type="button" class="burger" id="menuToggle" aria-controls="nav" aria-expanded="false" aria-label="Open menu"><span></span><span></span><span></span></button>
			</div>
		</div>
	</div>
	<div class="progress" aria-hidden="true"><span id="scrollBar"></span></div>
</header>

<main id="main">

<!-- ============ Slider ============ -->
<section id="home" class="slider" aria-roledescription="carousel" aria-label="Traboulsi Est. photos">
	<div class="slides">
		<?php foreach($hero_images as $i => $image){ ?>
		<div class="slide<?=$i == 0 ? ' is-active' : ''?>"><img src="<?=$base.o4_e($image['src'])?>" alt="<?=o4_e($image['title'])?>" <?=$i == 0 ? 'fetchpriority="high"' : 'fetchpriority="low"'?>></div>
		<?php } ?>
	</div>
	<div class="slider-shade" aria-hidden="true"></div>
	<div class="container slider-caption">
		<?=o4_tag('<p class="hero-eyebrow">', $hp['hero_eyebrow'], '</p>')?>
		<?php if(trim($hp['hero_title']) !== ''){ ?><h1 class="hero-title"><?=o4_lines($hp['hero_title'])?></h1><?php } ?>
		<div class="hero-actions">
			<?=o4_tag('<a href="#products" class="btn-more btn-white" data-magnetic><span>', $hp['hero_button_1'], '</span><svg class="ic"><use href="#i-plus"/></svg></a>')?>
			<?=o4_tag('<a href="#contact" class="btn-link btn-link-light">', $hp['hero_button_2'], '<svg class="ic"><use href="#i-arrow"/></svg></a>')?>
		</div>
	</div>
	<?php if(count($hero_images) > 1){ ?>
	<p class="slider-count" aria-hidden="true"><b id="slideNow">01</b><span></span><?=str_pad(count($hero_images), 2, '0', STR_PAD_LEFT)?></p>
	<button type="button" class="slider-arrow prev" aria-label="Previous photo"><svg class="ic"><use href="#i-left"/></svg></button>
	<button type="button" class="slider-arrow next" aria-label="Next photo"><svg class="ic"><use href="#i-right"/></svg></button>
	<div class="slider-dots">
		<?php foreach($hero_images as $i => $image){ ?>
		<button type="button" class="<?=$i == 0 ? 'is-active' : ''?>" aria-label="Show photo <?=$i + 1?>"></button>
		<?php } ?>
	</div>
	<?php } ?>
</section>

<!-- ============ Quote ============ -->
<section class="quote">
	<?php if($founded){ ?><div class="ghost-year" aria-hidden="true" data-drift><?=$founded?></div><?php } ?>
	<div class="container narrow">
		<h2 class="quote-title" data-reveal="split">&ldquo;TRABOULSI EST.&rdquo;</h2>
		<?=o4_tag('<p class="lead" data-words>', $hp['hero_text'], '</p>')?>
		<?=o4_tag('<p class="lead-ar" lang="ar" dir="rtl" data-reveal="up" style="--d:220ms">', $hp['hero_text_ar'], '</p>')?>
		<ul class="figures">
			<?php if($years){ ?><li data-reveal="up" style="--d:100ms"><strong><span data-count="<?=$years?>"><?=$years?></span>+</strong><?=o4_e(o4_or($hp['stat_years_label'], 'Years of experience'))?></li><?php } ?>
			<li data-reveal="up" style="--d:200ms"><strong><span data-count="<?=$stat_products?>"><?=$stat_products?></span>+</strong><?=o4_e(o4_or($hp['stat_products_label'], 'Products'))?></li>
			<?php if($stat_clients){ ?><li data-reveal="up" style="--d:300ms"><strong><span data-count="<?=$stat_clients?>"><?=$stat_clients?></span>+</strong><?=o4_e(o4_or($hp['stat_clients_label'], 'Clients'))?></li><?php } ?>
		</ul>
	</div>
</section>

<!-- ============ Category tiles ============ -->
<section class="categories is-dark" aria-label="Product categories">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="glow glow-b" aria-hidden="true"></div>
	<div class="container">
		<div class="section-head" data-reveal="up">
			<?=o4_tag('<p class="eyebrow">', $hp['solutions_eyebrow'], '</p>')?>
			<?=o4_tag('<h2 class="h2">', $hp['solutions_title'], '</h2>')?>
			<?=o4_tag('<p class="lead">', $hp['solutions_text'], '</p>')?>
		</div>
		<div class="cat-tiles">
			<a class="cat-tile" href="#products" data-filter-link="*" data-reveal="up" data-tilt>
				<svg class="cat-icon"><use href="#i-all"/></svg>
				<h2>All products</h2>
				<span><?=$product_total?> products</span>
			</a>
			<?php foreach($categories as $i => $category){
				$id = $category['id_category'];
				$count = $cat_counts[$id]; ?>
			<a class="cat-tile" data-reveal="up" data-tilt style="--d:<?=($i + 1) * 110?>ms" <?php if($count){ ?>href="#products" data-filter-link="prod<?=$id?>"<?php }else{ ?>href="#contact"<?php } ?>>
				<svg class="cat-icon"><use href="#i-<?=o4_icon($category['title'], $cat_icons, 'layers')?>"/></svg>
				<h2><?=o4_e($cat_names[$id])?></h2>
				<span><?=$count ? $count.' products' : 'Made to order'?></span>
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
				<?=o4_tag('<p class="eyebrow">', $hp['products_eyebrow'], '</p>')?>
				<h2 class="h2"><?=o4_e(o4_or($hp['products_title'], 'Our products'))?></h2>
			</div>
			<div class="products-tools">
				<label class="search">
					<svg class="ic"><use href="#i-search"/></svg>
					<input type="search" id="productSearch" placeholder="Search a product…" aria-label="Search products" autocomplete="off">
				</label>
				<div class="view-switch" role="group" aria-label="Display">
					<button type="button" class="is-active" data-view="rows" aria-pressed="true" title="Large rows"><svg class="ic"><use href="#i-rows"/></svg><span class="sr">Large rows</span></button>
					<button type="button" data-view="grid" aria-pressed="false" title="Grid"><svg class="ic"><use href="#i-grid"/></svg><span class="sr">Grid</span></button>
				</div>
			</div>
		</div>
		<div class="chips" role="toolbar" aria-label="Filter products by category" data-reveal="up" style="--d:120ms">
			<button type="button" class="chip is-active" data-filter="*" aria-pressed="true">All <small><?=$product_total?></small></button>
			<?php foreach($categories as $category){
				if(!$cat_counts[$category['id_category']]) continue; ?>
			<button type="button" class="chip" data-filter="prod<?=$category['id_category']?>" aria-pressed="false"><?=o4_e($cat_names[$category['id_category']])?> <small><?=$cat_counts[$category['id_category']]?></small></button>
			<?php } ?>
		</div>
	</div>

	<div class="p-list is-rows" id="productList">
		<?php foreach($products as $n => $product){
			$cat = isset($cat_names[$product['id_category']]) ? $cat_names[$product['id_category']] : '';
			$name = o4_name($product['title']);
			$big = is_file('uploads/products/600x455/'.$product['image']) ? 'uploads/products/600x455/' : 'uploads/products/290x220/';
			// small photos are not blown up more than 1.4 times, so they stay sharp
			$size = @getimagesize($big.$product['image']);
			$max_h = $size ? round($size[1] * 1.4) : 640; ?>
		<article class="p-row<?=$n % 2 ? ' is-flip' : ''?>" data-cat="prod<?=$product['id_category']?>" data-search="<?=o4_e(strtolower($product['title'].' '.$cat))?>">
			<div class="p-row-inner">
				<a class="p-img<?=($size && $size[1] < 380) ? ' is-small' : ''?>" href="<?=$base.$big.o4_e($product['image'])?>" aria-label="View <?=o4_e($name)?>" data-cursor="View">
					<span class="p-disc" aria-hidden="true"></span>
					<?php if(preg_match('/(\d{2,4})\s*$/', $product['title'], $ref)){ ?><span class="p-ghost" aria-hidden="true"><?=$ref[1]?></span><?php } ?>
					<img src="<?=$base.$big.o4_e($product['image'])?>" alt="<?=o4_e($product['alt'])?>" loading="lazy" style="--max-h:<?=$max_h?>px"<?php if($size){ ?> width="<?=$size[0]?>" height="<?=$size[1]?>"<?php } ?>>
				</a>
				<div class="p-text">
					<span class="p-cat"><?=o4_e($cat)?></span>
					<h3 class="p-name"><?=o4_e($name)?></h3>
					<p class="p-desc"><?=o4_e(o4_text($product, $cat, $cat_texts))?></p>
					<?php if(!empty($product['text_ar'])){ ?><p class="p-desc-ar" lang="ar" dir="rtl"><?=o4_e($product['text_ar'])?></p><?php } ?>
					<div class="p-actions">
						<button type="button" class="btn-more" data-more><span>More</span><svg class="ic"><use href="#i-plus"/></svg></button>
						<button type="button" class="btn-link" data-ask>Ask for a quote<svg class="ic"><use href="#i-arrow"/></svg></button>
					</div>
				</div>
			</div>
		</article>
		<?php } ?>
	</div>

	<div class="container">
		<p class="empty" id="productsEmpty" hidden>No products match your search. <a href="#contact">Ask us directly</a>, we may have it in stock or make it to order.</p>
		<div class="more" id="loadMoreWrap">
			<p>Showing <b id="shownCount"><?=$product_total?></b> of <b id="totalCount"><?=$product_total?></b> products</p>
			<button type="button" class="btn-more btn-wide" id="loadMore" hidden><span>Load more products</span><svg class="ic"><use href="#i-plus"/></svg></button>
		</div>
	</div>
</section>

<!-- ============ Parallax band: years + call to action ============ -->
<section class="band">
	<?php if($band_img){ ?><div class="band-media" aria-hidden="true"><img src="<?=$base.o4_e($band_img)?>" alt="<?=o4_e(o4_or($hp['contact_company'], 'Traboulsi Est.'))?> showroom" loading="lazy" data-parallax></div><?php } ?>
	<div class="container band-inner">
		<?php if($years){ ?>
		<div class="band-years" data-reveal="left">
			<strong><span data-count="<?=$years?>"><?=$years?></span><sup>+</sup></strong>
			<span><?=o4_e(o4_or($hp['stat_years_label'], 'Years of experience'))?></span>
		</div>
		<?php } ?>
		<div class="band-text" data-reveal="right" style="--d:150ms">
			<?=o4_tag('<h2>', $hp['cta_title'], '</h2>')?>
			<?=o4_tag('<p>', $hp['cta_text'], '</p>')?>
			<div class="cta-actions">
				<?=o4_tag('<a href="#contact" class="btn-solid btn-glow" data-magnetic><span>', $hp['cta_button'], '</span><svg class="ic"><use href="#i-arrow"/></svg></a>')?>
				<?php if($call_num){ ?><a href="<?=o4_e($call_href)?>" class="cta-call"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o4_e($call_num)?></span></a><?php } ?>
			</div>
		</div>
	</div>
</section>

<!-- ============ About (full width text + image, like the reference) ============ -->
<section id="about" class="about">
	<div class="container about-grid<?=$about_main ? '' : ' no-img'?>">
		<div class="about-body" data-reveal="left">
			<p class="eyebrow"><?=o4_e($about_us['title'])?></p>
			<?=o4_tag('<h2 class="h2">', $hp['about_title'], '</h2>')?>
			<div class="about-text is-clamped" id="aboutText"><?=$about_us['description']?></div>
			<button type="button" class="btn-more" data-toggle-about aria-expanded="false" aria-controls="aboutText"><span>Read more</span><svg class="ic"><use href="#i-plus"/></svg></button>
		</div>
		<?php if($about_main){ ?>
		<figure class="about-img<?=$storefront_sub ? ' has-sub' : ''?>" data-reveal="wipe" style="--d:150ms">
			<span class="about-photo"><img src="<?=$base.o4_e($about_main)?>" alt="Traboulsi Est. showroom with commercial kitchen equipment"></span>
			<?php if($storefront_sub){ ?><span class="about-sub"><img src="<?=$base.$storefront_sub?>" width="530" height="400" alt="Traboulsi Est. building in Deir El Zahrani"></span><?php } ?>
			<?php if($founded){ ?><figcaption><strong><?=$founded?></strong><?=o4_e(o4_or($hp['about_badge_label'], 'Established'))?></figcaption><?php } ?>
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
				<h3><?=o4_e($feature['title'])?></h3>
				<?=o4_tag('<p>', $feature['text'], '</p>')?>
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
			<?=o4_tag('<p class="eyebrow">', $hp['clients_eyebrow'], '</p>')?>
			<?=o4_tag('<h2 class="h2">', $hp['clients_title'], '</h2>')?>
			<?=o4_tag('<p class="lead">', $hp['clients_text'], '</p>')?>
		</div>
	</div>
	<?php if(count($unique_clients)){ ?>
	<div class="marquee" aria-hidden="true">
		<div class="marquee-skew" id="marqueeSkew"><div class="marquee-track">
			<?php for($copy = 0; $copy < 2; $copy++){ foreach($unique_clients as $client_name){ ?><span><?=o4_e($client_name)?></span><?php } } ?>
		</div></div>
	</div>
	<?php } ?>
	<div class="container">
		<?php foreach($client_groups as $country => $groups){ ?>
		<?php if(count($client_groups) > 1){ ?><p class="c-country"><svg class="ic"><use href="#i-pin"/></svg><?=o4_e($country)?></p><?php } ?>
		<div class="c-grid">
			<?php foreach($groups as $i => $group){ ?>
			<div class="c-card" data-reveal="up" style="--d:<?=($i % 4) * 110?>ms">
				<div class="c-head">
					<svg class="ic"><use href="#i-<?=o4_icon($group['title'], $client_icons, 'building')?>"/></svg>
					<h3><?=o4_e($group['title'])?></h3>
					<?php if(count($group['names'])){ ?><span><?=count($group['names'])?></span><?php } ?>
				</div>
				<?php if(count($group['names'])){ ?>
				<ul>
					<?php foreach($group['names'] as $client_name){ ?><li><?=o4_e($client_name)?></li><?php } ?>
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

<!-- ============ Showroom (like the news carousel of the reference) ============ -->
<?php if(count($showroom)){ ?>
<section id="showroom" class="showroom">
	<div class="container">
		<div class="row-title" data-reveal="up">
			<h2 class="h3"><?=o4_e(o4_or($hp['showroom_title'], 'Our showroom'))?></h2>
			<div class="row-arrows">
				<button type="button" class="arrow-btn" data-scroll="-1" aria-label="Previous photos"><svg class="ic"><use href="#i-left"/></svg></button>
				<button type="button" class="arrow-btn" data-scroll="1" aria-label="Next photos"><svg class="ic"><use href="#i-right"/></svg></button>
			</div>
		</div>
		<p class="lead"><?=o4_e(o4_or($hp['showroom_text'], 'Visit our factory in Deir El Zahrani or our branch in Khalde: refrigerators, cooking lines and stainless-steel work, on display and ready to deliver.'))?></p>
		<div class="show-track" id="showTrack">
			<?php foreach($showroom as $i => $photo){ ?>
			<a href="<?=$base.o4_e($photo['src'])?>" class="show-item" data-gallery data-reveal="zoom" style="--d:<?=min($i, 3) * 120?>ms" aria-label="Open photo <?=$i + 1?>">
				<img src="<?=$base.o4_e($photo['src'])?>" alt="<?=o4_e($photo['title'])?> — showroom photo <?=$i + 1?>" loading="lazy">
				<span class="show-num"><?=str_pad($i + 1, 2, '0', STR_PAD_LEFT)?></span>
				<span class="show-cap"><b><?=o4_e($photo['title'])?></b><small>Deir El Zahrani · click to enlarge</small><i class="show-zoom"><svg class="ic"><use href="#i-plus"/></svg></i></span>
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
				<p class="eyebrow"><?=o4_e(o4_or($hp['videos_eyebrow'], 'Watch'))?></p>
				<h2 class="h2"><?=o4_e(o4_or($hp['videos_title'], 'Our equipment in action'))?></h2>
			</div>
			<?php if(count($videos) > 1){ ?>
			<div class="row-arrows">
				<button type="button" class="arrow-btn" data-scroll="-1" data-track="videoTrack" aria-label="Previous videos"><svg class="ic"><use href="#i-left"/></svg></button>
				<button type="button" class="arrow-btn" data-scroll="1" data-track="videoTrack" aria-label="Next videos"><svg class="ic"><use href="#i-right"/></svg></button>
			</div>
			<?php } ?>
		</div>
		<p class="lead" data-reveal="up" style="--d:100ms"><?=o4_e(o4_or($hp['videos_text'], 'Installations, new arrivals and our workshop, straight from our Facebook page.'))?></p>

		<?php if(count($videos)){ ?>
		<div class="video-track" id="videoTrack">
			<?php foreach($videos as $i => $video){ ?>
			<figure class="video-card<?=$video['vertical'] ? ' is-vertical' : ''?>" data-reveal="up" style="--d:<?=min($i, 3) * 120?>ms">
				<div class="video-frame" data-cursor="Play">
					<iframe src="<?=o4_e($video['src'])?>" title="<?=o4_e($video['title'])?>" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowfullscreen></iframe>
				</div>
				<figcaption>
					<h3><?=o4_e($video['title'])?></h3>
					<?=o4_tag('<p>', (string)$video['description'], '</p>')?>
				</figcaption>
			</figure>
			<?php } ?>
		</div>
		<?php } ?>

		<?php if($fb_plugin){ ?>
		<div class="fb-wrap" data-reveal="up">
			<div class="fb-text">
				<span class="fb-badge"><svg class="ic"><use href="#i-facebook"/></svg></span>
				<p class="eyebrow">facebook.com/<?=o4_e(trim(parse_url($fb_page, PHP_URL_PATH), '/'))?></p>
				<h3>Follow us on Facebook</h3>
				<p>New equipment, finished projects and offers are posted on our page first. Follow us to see them as soon as they are out.</p>
				<ul class="fb-points">
					<li><svg class="ic"><use href="#i-check"/></svg>New arrivals and installations</li>
					<li><svg class="ic"><use href="#i-check"/></svg>Photos and videos from our workshop</li>
					<li><svg class="ic"><use href="#i-check"/></svg>Message us directly on Facebook</li>
				</ul>
				<div class="fb-actions">
					<a href="<?=o4_e($fb_page)?>" target="_blank" rel="noopener" class="btn-solid btn-glow" data-magnetic><span>Follow our page</span><svg class="ic"><use href="#i-arrow"/></svg></a>
					<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" class="btn-link btn-link-light"><svg class="ic"><use href="#i-whatsapp"/></svg>WhatsApp</a><?php } ?>
				</div>
			</div>
			<div class="fb-feed">
				<?php $this->load->view('include/_fb_card', array('fb_page' => $fb_page, 'photos' => $fb_photos, 'labels' => array('name' => 'Traboulsi Est.', 'follow' => 'Follow', 'all' => 'See all posts on Facebook', 'aria' => 'Latest posts of Traboulsi Est. on Facebook'))); ?>
			</div>
		</div>
		<?php } ?>
	</div>
</section>
<?php } ?>

<!-- ============ Contact ============ -->
<?php $this->load->view('include/_seo_section', array('wrap' => 'container')); // Arabic services + FAQ (Google) ?>

<section id="contact" class="contact">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<?=o4_tag('<p class="eyebrow">', $hp['contact_eyebrow'], '</p>')?>
			<?=o4_tag('<h2 class="h2">', $hp['contact_title'], '</h2>')?>
			<?=o4_tag('<p class="lead">', $hp['contact_text'], '</p>')?>
		</div>

		<div class="contact-grid">
			<aside class="info" data-reveal="left">
				<h3><?=o4_e(o4_or($hp['contact_company'], $contact_us['title']))?></h3>
				<?php if(count($contact_items)){ ?>
				<ul class="info-list">
					<?php foreach($contact_items as $item){
						$label = strtolower($item['label']);
						$icon = $item['type'] == 'mailto' ? 'mail' : (strpos($label, 'fax') !== false ? 'fax' : 'phone'); ?>
					<li>
						<svg class="ic"><use href="#i-<?=$icon?>"/></svg>
						<div><small><?=o4_e($item['label'])?></small><a href="<?=o4_e($item['href'])?>"<?=$item['type'] == 'tel' ? ' class="tel"' : ''?>><?=o4_e($item['value'])?></a></div>
					</li>
					<?php } ?>
					<?php foreach($locations as $location){ ?>
					<li>
						<svg class="ic"><use href="#i-pin"/></svg>
						<div><small>Branch</small><span><?=o4_e($location)?></span><?php $role = branch_role($location); if($role !== ''){ ?><span class="branch-role"><?=o4_e($role)?></span><?php } ?></div>
					</li>
					<?php } ?>
				</ul>
				<?php }else{ ?>
				<div class="info-raw"><?=$contact_us['description']?></div>
				<?php } ?>
				<div class="info-map">
					<iframe src="<?=o4_e($map_src)?>" title="Map — Deir El Zahrani, Lebanon" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
				</div>
			</aside>

			<form id="main-contact-form" class="form" name="contact-form" method="post" action="<?=o4_e($page_url)?>#contact" data-reveal="right" style="--d:120ms">
				<?=o4_tag('<h3>', $hp['contact_form_title'], '</h3>')?>
				<?php if($valid == 'not' && validation_errors()){ ?>
				<div class="alert alert-error" role="alert"><?=validation_errors()?></div>
				<?php } ?>
				<?php if($valid == 'true'){ ?>
				<div class="alert alert-success" role="status"><svg class="ic"><use href="#i-check"/></svg>Thank you! Your message has been sent. We'll get back to you shortly.</div>
				<?php } ?>
				<div class="form-row">
					<label class="field"><span>Full name *</span><input type="text" name="name" value="<?=$form['name']?>" autocomplete="name" required></label>
					<label class="field"><span>Email *</span><input type="email" name="email" value="<?=$form['email']?>" autocomplete="email" required></label>
				</div>
				<div class="form-row">
					<label class="field"><span>Phone *</span><input type="tel" name="telephone" value="<?=$form['telephone']?>" autocomplete="tel" required></label>
					<label class="field"><span>Company name *</span><input type="text" name="company" value="<?=$form['company']?>" autocomplete="organization" required></label>
				</div>
				<label class="field"><span>Subject *</span><input type="text" name="subject" id="subjectField" value="<?=$form['subject']?>" required></label>
				<label class="field"><span>Message *</span><textarea name="message" id="message" rows="5" required><?=$form['message']?></textarea></label>
				<button type="submit" name="submit" class="btn-solid btn-glow"><span>Send message</span><svg class="ic"><use href="#i-arrow"/></svg></button>
			</form>
		</div>
	</div>
</section>

</main>

<!-- ============ Footer ============ -->
<footer class="footer">
	<div class="container footer-grid">
		<div class="footer-col footer-brand">
			<img src="<?=$base?>images/logo.png" width="222" height="40" alt="Traboulsi Est." loading="lazy">
			<?=o4_tag('<p>', $hp['footer_text'], '</p>')?>
			<?=o4_tag('<p class="ar" lang="ar" dir="rtl">', $hp['footer_text_ar'], '</p>')?>
		</div>
		<div class="footer-col" data-collapse>
			<button type="button" class="footer-title" aria-expanded="false">Company<svg class="ic plus"><use href="#i-plus"/></svg><svg class="ic minus"><use href="#i-minus"/></svg></button>
			<ul>
				<li><a href="#about">About us</a></li>
				<li><a href="#clients">Our clients</a></li>
				<?php if(count($showroom)){ ?><li><a href="#showroom">Showroom</a></li><?php } ?>
				<?php if(isset($nav['videos'])){ ?><li><a href="#videos">Videos</a></li><?php } ?>
				<li><a href="#contact">Contact us</a></li>
			</ul>
		</div>
		<div class="footer-col" data-collapse>
			<button type="button" class="footer-title" aria-expanded="false">Products<svg class="ic plus"><use href="#i-plus"/></svg><svg class="ic minus"><use href="#i-minus"/></svg></button>
			<ul>
				<?php foreach($categories as $category){
					if(!$cat_counts[$category['id_category']]) continue; ?>
				<li><a href="#products" data-filter-link="prod<?=$category['id_category']?>"><?=o4_e($cat_names[$category['id_category']])?></a></li>
				<?php } ?>
			</ul>
		</div>
		<div class="footer-col" data-collapse>
			<button type="button" class="footer-title" aria-expanded="false">Contacts<svg class="ic plus"><use href="#i-plus"/></svg><svg class="ic minus"><use href="#i-minus"/></svg></button>
			<ul>
				<?php if($contact['phone']){ ?><li><a href="<?=o4_e($contact['phone_href'])?>" class="tel"><?=o4_e($contact['phone'])?></a></li><?php } ?>
				<?php if($contact['mobile']){ ?><li><a href="<?=o4_e($contact['mobile_href'])?>" class="tel"><?=o4_e($contact['mobile'])?></a></li><?php } ?>
				<?php if($contact['email']){ ?><li><a href="mailto:<?=o4_e($contact['email'])?>"><?=o4_e($contact['email'])?></a></li><?php } ?>
				<?php foreach($locations as $location){ ?><li><span><?=o4_e($location)?></span></li><?php } ?>
			</ul>
		</div>
		<div class="footer-col footer-follow">
			<p class="footer-title">Follow us</p>
			<div class="footer-social">
				<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=o4_e($contact_us['facebook'])?>" target="_blank" rel="noopener" aria-label="Facebook"><svg class="ic"><use href="#i-facebook"/></svg></a><?php } ?>
				<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=o4_e($contact_us['instagram'])?>" target="_blank" rel="noopener" aria-label="Instagram"><svg class="ic"><use href="#i-instagram"/></svg></a><?php } ?>
				<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="WhatsApp"><svg class="ic"><use href="#i-whatsapp"/></svg></a><?php } ?>
			</div>
		</div>
	</div>
	<div class="footer-giant" aria-hidden="true" data-reveal="up"><span class="giant-glow">TRABOULSIEST</span><span class="giant-text">TRABOULSIEST</span></div>
	<div class="copyright">
		<p>&copy; <?=date('Y')?> <?=o4_e($hp['footer_copyright'])?><?php if($contact['phone']){ ?> &nbsp;|&nbsp; Tel: <span class="tel"><?=o4_e($contact['phone'])?></span><?php } ?><?php if($contact['email']){ ?> &nbsp;|&nbsp; <?=o4_e($contact['email'])?><?php } ?></p>
		<p>Powered by <a href="https://creativelebanon.com/" target="_blank" rel="noopener">CreativeLebanon</a></p>
	</div>
</footer>

<?php if($contact['whatsapp']){ ?>
<a class="wa-float" href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp"><svg class="ic"><use href="#i-whatsapp"/></svg></a>
<?php } ?>
<a class="to-top" id="toTop" href="#home" aria-label="Back to top"><svg class="ic"><use href="#i-up"/></svg></a>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-labelledby="lbTitle" hidden>
	<div class="lb-box">
		<button type="button" class="lb-btn lb-close" aria-label="Close"><svg class="ic"><use href="#i-x"/></svg></button>
		<div class="lb-img">
			<button type="button" class="lb-btn lb-prev" aria-label="Previous"><svg class="ic"><use href="#i-left"/></svg></button>
			<img id="lbImg" alt="">
			<button type="button" class="lb-btn lb-next" aria-label="Next"><svg class="ic"><use href="#i-right"/></svg></button>
		</div>
		<div class="lb-text">
			<span class="p-cat" id="lbCat"></span>
			<h3 id="lbTitle"></h3>
			<p id="lbDesc"></p>
			<div class="lb-foot">
				<button type="button" class="btn-solid" id="lbAsk"><span>Ask about this product</span><svg class="ic"><use href="#i-arrow"/></svg></button>
				<span class="lb-count" id="lbCount"></span>
			</div>
		</div>
	</div>
</div>
