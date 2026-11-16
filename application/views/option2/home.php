<?php
/* Traboulsi Est. — option 2 (industrial design). Same data as option 1, see controllers/home.php */
if(!function_exists('o2_e')){
	function o2_e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8', false); }
	function o2_title($s){ return ucwords(strtolower(preg_replace('/\s+/', ' ', trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8'))))); }
	function o2_icon($title, $map, $default){
		$t = strtolower($title);
		foreach($map as $needle => $icon){ if(preg_match('/'.$needle.'/', $t)) return $icon; }
		return $default;
	}
	// hero title: one animated line per line typed in the back office, [words] in red
	function o2_hero_title($s){
		$out = '';
		foreach(preg_split('/\s*\R\s*/u', trim($s)) as $i => $line){
			$line = preg_replace('/\[(.+?)\]/s', '<em>$1</em>', o2_e($line));
			$out .= '<span class="line"><span style="--i:'.$i.'">'.$line.'</span></span> ';
		}
		return $out;
	}
	// a back office text inside its tag, or nothing when it was left empty
	function o2_tag($open, $text, $close){ return trim($text) === '' ? '' : $open.o2_e($text).$close; }
	// a back office text, or the default when it was left empty
	function o2_or($text, $default){ return trim($text) === '' ? $default : $text; }
	function o2_num($typed, $auto){ $n = preg_replace('/\D/', '', $typed); return $n === '' ? $auto : (int)$n; }
	// "showcase fridge 108" -> array('Showcase Fridge', '108')
	function o2_model($title){
		$title = trim($title);
		if(preg_match('/^(.*\D)\s*(\d{2,4})$/', $title, $m)) return array(ucfirst(trim($m[1])), $m[2]);
		return array(ucfirst($title), '');
	}
}
$hp = $home_page;
$cat_icons = array('refrig|fridge|cold' => 'fridge', 'heat|oven|grill' => 'flame', 'stain|steel|process' => 'layers');
$client_icons = array('hospital' => 'medical', 'restaurant' => 'utensils', 'pastr|baker|sweet' => 'cake', 'supermarket' => 'cart');

$cat_names = array();
$cat_products = array();
foreach($categories as $category) $cat_names[$category['id_category']] = o2_title($category['title']);
foreach($products as $product) $cat_products[$product['id_category']][] = $product;

$founded = (int)$hp['founded_year'];
$years = $founded ? date('Y') - $founded : 0;
$product_total = count($products);
$unique_clients = array();
foreach($client_groups as $groups){
	foreach($groups as $group){
		foreach($group['names'] as $client_name) $unique_clients[strtolower($client_name)] = $client_name;
	}
}
$stat_products = o2_num($hp['stat_products_number'], $product_total);
$stat_sectors = o2_num($hp['stat_sectors_number'], count($categories));
$stat_clients = o2_num($hp['stat_clients_number'], count($unique_clients));
$features = array(1 => 'factory', 2 => 'layers', 3 => 'package', 4 => 'wrench');
$services = array();
foreach($features as $n => $icon){
	if(trim($hp['feature_'.$n.'_title']) !== '') $services[] = array('icon' => $icon, 'title' => $hp['feature_'.$n.'_title'], 'text' => $hp['feature_'.$n.'_text']);
}

$hero_main = count($hero_images) ? $hero_images[count($hero_images) - 1]['src'] : '';
$storefront = is_file('uploads/about_us/530x400/tra.jpg') ? 'uploads/about_us/530x400/tra.jpg' : '';

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
$badge_text = strtoupper(($founded ? 'Since '.$founded.' • ' : '').'Traboulsi Est. • Trading & Industry • ');
$base = base_url();
?>
<svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true">
	<symbol id="i-phone" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
	<symbol id="i-mail" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></symbol>
	<symbol id="i-pin" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></symbol>
	<symbol id="i-fax" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></symbol>
	<symbol id="i-facebook" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></symbol>
	<symbol id="i-instagram" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></symbol>
	<symbol id="i-arrow" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></symbol>
	<symbol id="i-arrow-ur" viewBox="0 0 24 24"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></symbol>
	<symbol id="i-down" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></symbol>
	<symbol id="i-up" viewBox="0 0 24 24"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></symbol>
	<symbol id="i-left" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></symbol>
	<symbol id="i-right" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></symbol>
	<symbol id="i-x" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></symbol>
	<symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></symbol>
	<symbol id="i-plus" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></symbol>
	<symbol id="i-send" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></symbol>
	<symbol id="i-check" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></symbol>
	<symbol id="i-fridge" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="5" y1="10" x2="19" y2="10"/><line x1="8.5" y1="5" x2="8.5" y2="7"/><line x1="8.5" y1="13" x2="8.5" y2="16"/></symbol>
	<symbol id="i-flame" viewBox="0 0 24 24"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></symbol>
	<symbol id="i-cake" viewBox="0 0 24 24"><path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/><path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2-1 2-1"/><path d="M2 21h20"/><path d="M7 8v3"/><path d="M12 8v3"/><path d="M17 8v3"/></symbol>
	<symbol id="i-cart" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></symbol>
	<symbol id="i-layers" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></symbol>
	<symbol id="i-medical" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></symbol>
	<symbol id="i-utensils" viewBox="0 0 24 24"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></symbol>
	<symbol id="i-building" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></symbol>
	<symbol id="i-factory" viewBox="0 0 24 24"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M17 18h1"/><path d="M12 18h1"/><path d="M7 18h1"/></symbol>
	<symbol id="i-package" viewBox="0 0 24 24"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></symbol>
	<symbol id="i-wrench" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
	<symbol id="i-star" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z"/></symbol>
	<symbol id="i-whatsapp" viewBox="0 0 24 24"><path fill="currentColor" stroke="none" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></symbol>
</svg>

<a class="skip-link" href="#main">Skip to content</a>
<div class="scroll-progress" aria-hidden="true"><span id="scrollBar"></span></div>

<!-- ============ Top bar ============ -->
<div class="topbar">
	<div class="wrap topbar-inner">
		<div class="topbar-info">
			<?php if($contact['phone']){ ?><a href="<?=o2_e($contact['phone_href'])?>"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o2_e($contact['phone'])?></span></a><?php } ?>
			<?php if($contact['email']){ ?><a href="mailto:<?=o2_e($contact['email'])?>"><svg class="ic"><use href="#i-mail"/></svg><?=o2_e($contact['email'])?></a><?php } ?>
			<?php if(count($locations)){ ?><span><svg class="ic"><use href="#i-pin"/></svg><?=o2_e(implode(' · ', $locations))?></span><?php } ?>
		</div>
		<div class="topbar-side">
			<?=o2_tag('<span class="ar" lang="ar" dir="rtl">', $hp['topbar_name_ar'], '</span>')?>
			<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=o2_e($contact_us['facebook'])?>" target="_blank" rel="noopener" aria-label="Facebook"><svg class="ic"><use href="#i-facebook"/></svg></a><?php } ?>
			<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=o2_e($contact_us['instagram'])?>" target="_blank" rel="noopener" aria-label="Instagram"><svg class="ic"><use href="#i-instagram"/></svg></a><?php } ?>
		</div>
	</div>
</div>

<!-- ============ Header ============ -->
<header class="header" id="siteHeader">
	<div class="wrap header-inner">
		<a href="#home" class="brand" aria-label="Traboulsi Est. — home">
			<img src="<?=$base?>images/logo.png" width="300" height="54" alt="Traboulsi Est. — Since 1982">
		</a>
		<nav class="nav" aria-label="Main">
			<a href="#home" class="is-active">Home</a>
			<a href="#about">About</a>
			<a href="#solutions">Solutions</a>
			<a href="#portfolio">Products</a>
			<a href="#clients">Clients</a>
			<a href="#showroom">Showroom</a>
			<a href="#contact">Contact</a>
		</nav>
		<div class="header-side">
			<?php if($call_num){ ?>
			<a class="header-call" href="<?=o2_e($call_href)?>">
				<span class="header-call-icon"><svg class="ic"><use href="#i-phone"/></svg></span>
				<span><small>Call us</small><strong class="tel"><?=o2_e($call_num)?></strong></span>
			</a>
			<?php } ?>
			<a href="#contact" class="btn btn-red header-cta"><span>Get a quote</span><svg class="ic"><use href="#i-arrow-ur"/></svg></a>
			<button class="burger" id="menuToggle" type="button" aria-controls="menu" aria-expanded="false" aria-label="Open menu"><span></span><span></span></button>
		</div>
	</div>
</header>

<!-- ============ Full screen menu (mobile) ============ -->
<div class="menu" id="menu" hidden>
	<div class="menu-inner">
		<nav class="menu-links" aria-label="Menu">
			<?php $n = 0; foreach(array('home' => 'Home', 'about' => 'About', 'solutions' => 'Solutions', 'portfolio' => 'Products', 'clients' => 'Clients', 'showroom' => 'Showroom', 'contact' => 'Contact') as $id => $label){ $n++; ?>
			<a href="#<?=$id?>" style="--i:<?=$n?>"><small>0<?=$n?></small><?=$label?></a>
			<?php } ?>
		</nav>
		<div class="menu-info">
			<?php if($call_num){ ?><a href="<?=o2_e($call_href)?>" class="tel"><?=o2_e($call_num)?></a><?php } ?>
			<?php if($contact['email']){ ?><a href="mailto:<?=o2_e($contact['email'])?>"><?=o2_e($contact['email'])?></a><?php } ?>
			<?=o2_tag('<p class="ar" lang="ar" dir="rtl">', $hp['topbar_name_ar'], '</p>')?>
		</div>
	</div>
</div>

<main id="main">

<!-- ============ Hero ============ -->
<section id="home" class="hero">
	<div class="hero-media" aria-hidden="true">
		<?php foreach($hero_images as $i => $image){ ?>
		<img class="hero-slide<?=$i == 0 ? ' is-active' : ''?>" src="<?=$base.o2_e($image['src'])?>" alt="<?=o2_e(o2_or($image['title'], 'Traboulsi Est. showroom'))?>" <?=$i == 0 ? 'fetchpriority="high"' : 'loading="lazy"'?>>
		<?php } ?>
	</div>
	<div class="hero-shade"></div>
	<div class="hero-grid" aria-hidden="true"></div>

	<div class="wrap hero-inner">
		<div class="hero-content">
			<?=o2_tag('<p class="kicker kicker-light">', $hp['hero_eyebrow'], '</p>')?>
			<?php if(trim($hp['hero_title']) !== ''){ ?><h1 class="hero-title"><?=o2_hero_title($hp['hero_title'])?></h1><?php } ?>
			<div class="hero-bottom">
				<div>
					<?=o2_tag('<p class="hero-lead">', $hp['hero_text'], '</p>')?>
					<?=o2_tag('<p class="hero-ar" lang="ar" dir="rtl">', $hp['hero_text_ar'], '</p>')?>
				</div>
				<div class="hero-actions">
					<?=o2_tag('<a href="#portfolio" class="btn btn-red btn-lg"><span>', $hp['hero_button_1'], '</span><svg class="ic"><use href="#i-arrow-ur"/></svg></a>')?>
					<?=o2_tag('<a href="#contact" class="btn btn-line btn-lg"><span>', $hp['hero_button_2'], '</span></a>')?>
				</div>
			</div>
		</div>

		<a href="#about" class="spin-badge hero-badge" aria-label="About Traboulsi Est.">
			<svg viewBox="0 0 200 200" class="spin-text" aria-hidden="true">
				<defs><path id="badgeCircle" d="M100,100 m-78,0 a78,78 0 1,1 156,0 a78,78 0 1,1 -156,0"/></defs>
				<text><textPath href="#badgeCircle" textLength="485"><?=o2_e($badge_text)?></textPath></text>
			</svg>
			<span class="spin-core"><svg class="ic"><use href="#i-down"/></svg></span>
		</a>
	</div>

	<div class="hero-foot">
		<div class="wrap hero-foot-inner">
			<?php if(count($hero_images) > 1){ ?>
			<div class="hero-pager" aria-label="Choose slide">
				<span class="hero-count"><b id="heroNow">01</b> / <?=str_pad(count($hero_images), 2, '0', STR_PAD_LEFT)?></span>
				<?php foreach($hero_images as $i => $image){ ?>
				<button type="button" class="hero-dot<?=$i == 0 ? ' is-active' : ''?>" aria-label="Show slide <?=$i + 1?>"><span></span></button>
				<?php } ?>
			</div>
			<?php } ?>
			<div class="hero-stats">
				<?php if($years){ ?><div><strong><span data-count="<?=$years?>"><?=$years?></span>+</strong><?=o2_tag('<span>', $hp['stat_years_label'], '</span>')?></div><?php } ?>
				<div><strong><span data-count="<?=$stat_products?>"><?=$stat_products?></span>+</strong><?=o2_tag('<span>', $hp['stat_products_label'], '</span>')?></div>
				<div><strong><span data-count="<?=$stat_clients?>"><?=$stat_clients?></span>+</strong><?=o2_tag('<span>', $hp['stat_clients_label'], '</span>')?></div>
			</div>
		</div>
	</div>
</section>

<!-- ============ Ticker ============ -->
<div class="ticker" aria-hidden="true">
	<div class="ticker-track">
		<?php
		$ticker = array();
		foreach($categories as $category) $ticker[] = $cat_names[$category['id_category']];
		foreach($services as $service) $ticker[] = $service['title'];
		for($copy = 0; $copy < 2; $copy++){ foreach($ticker as $word){ ?>
		<span><?=o2_e($word)?></span><svg class="ic"><use href="#i-star"/></svg>
		<?php } } ?>
	</div>
</div>

<!-- ============ About ============ -->
<section id="about" class="section about">
	<div class="wrap about-grid">
		<div class="about-media" data-reveal>
			<?php if($hero_main){ ?>
			<figure class="about-img-main"><img src="<?=$base.o2_e($hero_main)?>" alt="Traboulsi Est. showroom with commercial kitchen equipment" loading="lazy" data-parallax></figure>
			<?php } ?>
			<?php if($storefront){ ?>
			<figure class="about-img-sub"><img src="<?=$base.$storefront?>" width="530" height="400" alt="Traboulsi Est. building in Deir El Zahrani" loading="lazy"></figure>
			<?php } ?>
			<?php if($years){ ?>
			<div class="about-years">
				<strong><span data-count="<?=$years?>"><?=$years?></span><sup>+</sup></strong>
				<span><?=o2_e(o2_or($hp['stat_years_label'], 'Years of experience'))?></span>
			</div>
			<?php } ?>
		</div>

		<div class="about-body" data-reveal style="--d:120ms">
			<p class="kicker"><?=o2_e($about_us['title'])?></p>
			<?=o2_tag('<h2 class="h2">', $hp['about_title'], '</h2>')?>
			<div class="about-text is-clamped" id="aboutText"><?=$about_us['description']?></div>
			<button type="button" class="text-btn" data-toggle-about aria-expanded="false" aria-controls="aboutText"><span>Read more</span><svg class="ic"><use href="#i-plus"/></svg></button>

			<ul class="about-points">
				<?php foreach($services as $service){ ?>
				<li><svg class="ic"><use href="#i-check"/></svg><?=o2_e($service['title'])?></li>
				<?php } ?>
			</ul>

			<div class="about-cta">
				<a href="#contact" class="btn btn-dark"><span>More about us</span><svg class="ic"><use href="#i-arrow-ur"/></svg></a>
				<?php if($founded){ ?>
				<div class="about-since"><strong><?=$founded?></strong><?=o2_tag('<span>', $hp['about_badge_label'], '</span>')?></div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>

<!-- ============ Solutions (categories) ============ -->
<section id="solutions" class="section dark">
	<div class="wrap">
		<div class="head head-split" data-reveal>
			<div>
				<?=o2_tag('<p class="kicker kicker-light">', $hp['solutions_eyebrow'], '</p>')?>
				<?=o2_tag('<h2 class="h2">', $hp['solutions_title'], '</h2>')?>
			</div>
			<?=o2_tag('<p class="head-lead">', $hp['solutions_text'], '</p>')?>
		</div>

		<div class="sol-grid">
			<?php foreach($categories as $i => $category){
				$id = $category['id_category'];
				$count = $cat_counts[$id];
				$thumbs = isset($cat_products[$id]) ? array_slice($cat_products[$id], 0, 3) : array(); ?>
			<a class="sol-card" data-reveal style="--d:<?=($i % 3) * 90?>ms"
				<?php if($count){ ?>href="#portfolio" data-filter-link="prod<?=$id?>"<?php }else{ ?>href="#contact"<?php } ?>>
				<div class="sol-top">
					<span class="sol-num"><?=str_pad($i + 1, 2, '0', STR_PAD_LEFT)?></span>
					<span class="sol-icon"><svg class="ic"><use href="#i-<?=o2_icon($category['title'], $cat_icons, 'package')?>"/></svg></span>
				</div>
				<div class="sol-stack" aria-hidden="true">
					<?php foreach($thumbs as $t => $thumb){ ?>
					<img src="<?=$base?>uploads/products/290x220/<?=o2_e($thumb['image'])?>" alt="<?=o2_e($thumb['alt'])?>" loading="lazy" style="--t:<?=$t?>">
					<?php } ?>
				</div>
				<div class="sol-body">
					<h3><?=o2_e($cat_names[$id])?></h3>
					<p><?=$count ? $count.' products in the catalogue' : 'Made to order — ask us for details'?></p>
				</div>
				<span class="sol-link"><span><?=$count ? 'View products' : 'Ask for a quote'?></span><svg class="ic"><use href="#i-arrow-ur"/></svg></span>
			</a>
			<?php } ?>
		</div>
	</div>
</section>

<!-- ============ Services (the 4 points of About) ============ -->
<?php if(count($services)){ ?>
<section id="services" class="section services">
	<div class="wrap">
		<div class="head head-center" data-reveal>
			<p class="kicker"><?=o2_e(o2_or($hp['services_eyebrow'], 'What we do'))?></p>
			<h2 class="h2"><?=o2_e(o2_or($hp['services_title'], 'Our services & solutions'))?></h2>
			<p class="head-lead"><?=o2_e(o2_or($hp['services_text'], 'From manufacturing to installation and after-sales care, one team follows your project from the first sketch to the last screw.'))?></p>
		</div>
		<ol class="steps">
			<?php foreach($services as $i => $service){ ?>
			<li class="step" data-reveal style="--d:<?=$i * 110?>ms">
				<span class="step-num"><?=str_pad($i + 1, 2, '0', STR_PAD_LEFT)?></span>
				<span class="step-dot" aria-hidden="true"></span>
				<span class="step-icon"><svg class="ic"><use href="#i-<?=$service['icon']?>"/></svg></span>
				<h3><?=o2_e($service['title'])?></h3>
				<?=o2_tag('<p>', $service['text'], '</p>')?>
			</li>
			<?php } ?>
		</ol>
	</div>
</section>
<?php } ?>

<!-- ============ Products ============ -->
<section id="portfolio" class="section products">
	<div class="wrap">
		<div class="head head-split" data-reveal>
			<div>
				<?=o2_tag('<p class="kicker">', $hp['products_eyebrow'], '</p>')?>
				<?=o2_tag('<h2 class="h2">', $hp['products_title'], '</h2>')?>
			</div>
			<label class="search">
				<svg class="ic"><use href="#i-search"/></svg>
				<input type="search" id="productSearch" placeholder="Search a product…" aria-label="Search products" autocomplete="off">
			</label>
		</div>

		<div class="tabs" role="toolbar" aria-label="Filter products by category">
			<button type="button" class="tab is-active" data-filter="*" aria-pressed="true">All <sup><?=$product_total?></sup></button>
			<?php foreach($categories as $category){
				if(!$cat_counts[$category['id_category']]) continue; ?>
			<button type="button" class="tab" data-filter="prod<?=$category['id_category']?>" aria-pressed="false"><?=o2_e($cat_names[$category['id_category']])?> <sup><?=$cat_counts[$category['id_category']]?></sup></button>
			<?php } ?>
		</div>

		<div class="p-grid" id="productGrid">
			<?php foreach($products as $n => $product){
				$cat = isset($cat_names[$product['id_category']]) ? $cat_names[$product['id_category']] : '';
				list($p_name, $p_ref) = o2_model($product['title']); ?>
			<article class="p-card" data-cat="prod<?=$product['id_category']?>" data-name="<?=o2_e($p_name.($p_ref ? ' '.$p_ref : ''))?>" data-search="<?=o2_e(strtolower($product['title'].' '.$cat))?>">
				<a href="<?=$base?>uploads/products/600x455/<?=o2_e($product['image'])?>" class="p-media" aria-label="View <?=o2_e($product['title'])?>">
					<img src="<?=$base?>uploads/products/290x220/<?=o2_e($product['image'])?>" width="290" height="220" loading="lazy" alt="<?=o2_e($product['alt'])?>">
					<?php if($p_ref){ ?><span class="p-ref">Ref. <?=o2_e($p_ref)?></span><?php } ?>
				</a>
				<div class="p-body">
					<div>
						<span class="p-cat"><?=o2_e($cat)?></span>
						<h3 class="p-title"><?=o2_e($p_name)?></h3>
					</div>
					<button type="button" class="p-ask" data-ask aria-label="Ask about <?=o2_e($product['title'])?>"><svg class="ic"><use href="#i-arrow-ur"/></svg></button>
				</div>
			</article>
			<?php } ?>
		</div>

		<p class="empty" id="productsEmpty" hidden>No products match your search. <a href="#contact">Ask us directly</a> — we may have it in stock or make it to order.</p>

		<div class="more" id="loadMoreWrap">
			<p>Showing <b id="shownCount"><?=$product_total?></b> of <b id="totalCount"><?=$product_total?></b> products</p>
			<div class="bar"><span id="productsBar"></span></div>
			<button type="button" class="btn btn-dark" id="loadMore" hidden><span>Load more products</span><svg class="ic"><use href="#i-plus"/></svg></button>
		</div>
	</div>
</section>

<!-- ============ Clients ============ -->
<section id="clients" class="section dark clients">
	<div class="wrap">
		<div class="head head-split" data-reveal>
			<div>
				<?=o2_tag('<p class="kicker kicker-light">', $hp['clients_eyebrow'], '</p>')?>
				<?=o2_tag('<h2 class="h2">', $hp['clients_title'], '</h2>')?>
			</div>
			<?=o2_tag('<p class="head-lead">', $hp['clients_text'], '</p>')?>
		</div>
	</div>

	<?php if(count($unique_clients)){ ?>
	<div class="names-marquee" aria-hidden="true">
		<div class="names-track">
			<?php for($copy = 0; $copy < 2; $copy++){ foreach($unique_clients as $client_name){ ?>
			<span><?=o2_e($client_name)?></span>
			<?php } } ?>
		</div>
	</div>
	<?php } ?>

	<div class="wrap">
		<?php foreach($client_groups as $country => $groups){ ?>
		<div class="c-wrap" data-tabs data-reveal>
			<div class="c-tabs" role="tablist" aria-label="Client sectors in <?=o2_e($country)?>">
				<p class="c-country"><svg class="ic"><use href="#i-pin"/></svg><?=o2_e($country)?></p>
				<?php foreach($groups as $i => $group){
					$tab_id = 'o2ct-'.$group['id_our_clients']; ?>
				<button type="button" role="tab" id="<?=$tab_id?>" aria-controls="<?=$tab_id?>-panel" aria-selected="<?=$i == 0 ? 'true' : 'false'?>" tabindex="<?=$i == 0 ? '0' : '-1'?>" class="c-tab<?=$i == 0 ? ' is-active' : ''?>">
					<span class="c-num"><?=str_pad($i + 1, 2, '0', STR_PAD_LEFT)?></span>
					<span class="c-name"><?=o2_e($group['title'])?></span>
					<?php if(count($group['names'])){ ?><span class="c-count"><?=count($group['names'])?></span><?php } ?>
				</button>
				<?php } ?>
			</div>
			<div class="c-panels">
				<?php foreach($groups as $i => $group){
					$tab_id = 'o2ct-'.$group['id_our_clients']; ?>
				<div class="c-panel<?=$i == 0 ? ' is-active' : ''?>" role="tabpanel" id="<?=$tab_id?>-panel" aria-labelledby="<?=$tab_id?>">
					<div class="c-panel-head">
						<span class="c-icon"><svg class="ic"><use href="#i-<?=o2_icon($group['title'], $client_icons, 'building')?>"/></svg></span>
						<h3><?=o2_e($group['title'])?></h3>
					</div>
					<?php if(count($group['names'])){ ?>
					<ul class="c-names">
						<?php foreach($group['names'] as $client_name){ ?>
						<li><?=o2_e($client_name)?></li>
						<?php } ?>
					</ul>
					<?php }else{ ?>
					<div class="c-raw"><?=$group['description']?></div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
</section>

<!-- ============ Showroom ============ -->
<?php if(count($showroom)){ ?>
<section id="showroom" class="section showroom">
	<div class="wrap">
		<div class="head head-split" data-reveal>
			<div>
				<p class="kicker"><?=o2_e(o2_or($hp['showroom_eyebrow'], 'Our showroom'))?></p>
				<h2 class="h2"><?=o2_e(o2_or($hp['showroom_title'], 'See the equipment before you buy'))?></h2>
			</div>
			<p class="head-lead"><?=o2_e(o2_or($hp['showroom_text'], 'Visit our factory in Deir El Zahrani or our branch in Khalde: refrigerators, cooking lines and stainless-steel work, on display and ready to deliver.'))?></p>
		</div>
		<div class="gallery g-<?=min(count($showroom), 4)?>">
			<?php foreach($showroom as $i => $photo){ ?>
			<a href="<?=$base.o2_e($photo['src'])?>" class="g-item" data-gallery data-reveal style="--d:<?=$i * 90?>ms" aria-label="Open photo <?=$i + 1?>">
				<img src="<?=$base.o2_e($photo['src'])?>" alt="<?=o2_e($photo['title'])?> — showroom photo <?=$i + 1?>" loading="lazy">
				<span class="g-zoom"><svg class="ic"><use href="#i-plus"/></svg></span>
			</a>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>

<!-- ============ Call to action ============ -->
<section class="cta">
	<div class="wrap cta-inner" data-reveal>
		<div class="cta-text">
			<?=o2_tag('<h2>', $hp['cta_title'], '</h2>')?>
			<?=o2_tag('<p>', $hp['cta_text'], '</p>')?>
		</div>
		<div class="cta-actions">
			<?=o2_tag('<a href="#contact" class="btn btn-white btn-lg"><span>', $hp['cta_button'], '</span><svg class="ic"><use href="#i-arrow-ur"/></svg></a>')?>
			<?php if($call_num){ ?>
			<a href="<?=o2_e($call_href)?>" class="cta-call"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o2_e($call_num)?></span></a>
			<?php } ?>
		</div>
	</div>
</section>

<!-- ============ Contact ============ -->
<?php $this->load->view('include/_seo_section', array('wrap' => 'wrap')); // Arabic services + FAQ (Google) ?>

<section id="contact" class="section contact">
	<div class="wrap">
		<div class="head head-split" data-reveal>
			<div>
				<?=o2_tag('<p class="kicker">', $hp['contact_eyebrow'], '</p>')?>
				<?=o2_tag('<h2 class="h2">', $hp['contact_title'], '</h2>')?>
			</div>
			<?=o2_tag('<p class="head-lead">', $hp['contact_text'], '</p>')?>
		</div>

		<div class="contact-grid">
			<aside class="info-card" data-reveal>
				<h3><?=o2_e(o2_or($hp['contact_company'], $contact_us['title']))?></h3>
				<?php if(count($contact_items)){ ?>
				<ul class="info-list">
					<?php foreach($contact_items as $item){
						$label = strtolower($item['label']);
						$icon = $item['type'] == 'mailto' ? 'mail' : (strpos($label, 'fax') !== false ? 'fax' : 'phone'); ?>
					<li>
						<span class="info-icon"><svg class="ic"><use href="#i-<?=$icon?>"/></svg></span>
						<div><small><?=o2_e($item['label'])?></small><a href="<?=o2_e($item['href'])?>"<?=$item['type'] == 'tel' ? ' class="tel"' : ''?>><?=o2_e($item['value'])?></a></div>
					</li>
					<?php } ?>
					<?php foreach($locations as $location){ ?>
					<li>
						<span class="info-icon"><svg class="ic"><use href="#i-pin"/></svg></span>
						<div><small>Branch</small><span><?=o2_e($location)?></span><?php $role = branch_role($location); if($role !== ''){ ?><span class="branch-role"><?=o2_e($role)?></span><?php } ?></div>
					</li>
					<?php } ?>
				</ul>
				<?php }else{ ?>
				<div class="info-raw"><?=$contact_us['description']?></div>
				<?php } ?>
				<div class="info-social">
					<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=o2_e($contact_us['facebook'])?>" target="_blank" rel="noopener" aria-label="Facebook"><svg class="ic"><use href="#i-facebook"/></svg></a><?php } ?>
					<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=o2_e($contact_us['instagram'])?>" target="_blank" rel="noopener" aria-label="Instagram"><svg class="ic"><use href="#i-instagram"/></svg></a><?php } ?>
					<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="WhatsApp"><svg class="ic"><use href="#i-whatsapp"/></svg></a><?php } ?>
				</div>
				<div class="info-map">
					<iframe src="<?=o2_e($map_src)?>" title="Map — Deir El Zahrani, Lebanon" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
				</div>
			</aside>

			<form id="main-contact-form" class="form" name="contact-form" method="post" action="<?=o2_e($page_url)?>#contact" data-reveal style="--d:120ms">
				<div class="form-head">
					<?=o2_tag('<h3>', $hp['contact_form_title'], '</h3>')?>
					<p>Fields marked * are required.</p>
				</div>
				<?php if($valid == 'not' && validation_errors()){ ?>
				<div class="alert alert-error" role="alert"><?=validation_errors()?></div>
				<?php } ?>
				<?php if($valid == 'true'){ ?>
				<div class="alert alert-success" role="status"><svg class="ic"><use href="#i-check"/></svg>Thank you! Your message has been sent. We'll get back to you shortly.</div>
				<?php } ?>
				<div class="form-row">
					<label class="field"><input type="text" name="name" value="<?=$form['name']?>" autocomplete="name" placeholder=" " required><span>Full name *</span></label>
					<label class="field"><input type="email" name="email" value="<?=$form['email']?>" autocomplete="email" placeholder=" " required><span>Email *</span></label>
				</div>
				<div class="form-row">
					<label class="field"><input type="tel" name="telephone" value="<?=$form['telephone']?>" autocomplete="tel" placeholder=" " required><span>Phone *</span></label>
					<label class="field"><input type="text" name="company" value="<?=$form['company']?>" autocomplete="organization" placeholder=" " required><span>Company name *</span></label>
				</div>
				<label class="field"><input type="text" name="subject" id="subjectField" value="<?=$form['subject']?>" placeholder=" " required><span>Subject *</span></label>
				<label class="field"><textarea name="message" id="message" rows="5" placeholder=" " required><?=$form['message']?></textarea><span>Message *</span></label>
				<button type="submit" name="submit" class="btn btn-red btn-lg btn-block"><span>Send message</span><svg class="ic"><use href="#i-send"/></svg></button>
			</form>
		</div>
	</div>
</section>

</main>

<!-- ============ Footer ============ -->
<footer class="footer">
	<div class="wrap">
		<div class="footer-top">
			<p class="footer-claim">Let's equip your<br><em>next project.</em></p>
			<?php if($contact['email']){ ?><a class="footer-mail" href="mailto:<?=o2_e($contact['email'])?>"><?=o2_e($contact['email'])?><svg class="ic"><use href="#i-arrow-ur"/></svg></a><?php } ?>
		</div>
		<div class="footer-grid">
			<div class="footer-brand">
				<img src="<?=$base?>images/logo.png" width="222" height="40" alt="Traboulsi Est." loading="lazy">
				<?=o2_tag('<p>', $hp['footer_text'], '</p>')?>
				<?=o2_tag('<p class="ar" lang="ar" dir="rtl">', $hp['footer_text_ar'], '</p>')?>
			</div>
			<div>
				<h4>Explore</h4>
				<ul>
					<li><a href="#about">About us</a></li>
					<li><a href="#solutions">Solutions</a></li>
					<li><a href="#portfolio">Products</a></li>
					<li><a href="#clients">Our clients</a></li>
					<li><a href="#showroom">Showroom</a></li>
				</ul>
			</div>
			<div>
				<h4>Products</h4>
				<ul>
					<?php foreach($categories as $category){
						if(!$cat_counts[$category['id_category']]) continue; ?>
					<li><a href="#portfolio" data-filter-link="prod<?=$category['id_category']?>"><?=o2_e($cat_names[$category['id_category']])?></a></li>
					<?php } ?>
				</ul>
			</div>
			<div>
				<h4>Get in touch</h4>
				<ul class="footer-contact">
					<?php if($contact['phone']){ ?><li><svg class="ic"><use href="#i-phone"/></svg><a href="<?=o2_e($contact['phone_href'])?>" class="tel"><?=o2_e($contact['phone'])?></a></li><?php } ?>
					<?php if($contact['mobile']){ ?><li><svg class="ic"><use href="#i-whatsapp"/></svg><a href="<?=o2_e($contact['mobile_href'])?>" class="tel"><?=o2_e($contact['mobile'])?></a></li><?php } ?>
					<?php foreach($locations as $location){ ?><li><svg class="ic"><use href="#i-pin"/></svg><span><?=o2_e($location)?></span></li><?php } ?>
				</ul>
				<div class="footer-social">
					<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=o2_e($contact_us['facebook'])?>" target="_blank" rel="noopener" aria-label="Facebook"><svg class="ic"><use href="#i-facebook"/></svg></a><?php } ?>
					<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=o2_e($contact_us['instagram'])?>" target="_blank" rel="noopener" aria-label="Instagram"><svg class="ic"><use href="#i-instagram"/></svg></a><?php } ?>
					<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="WhatsApp"><svg class="ic"><use href="#i-whatsapp"/></svg></a><?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-giant" aria-hidden="true">TRABOULSI</div>
	<div class="wrap">
		<div class="footer-bottom">
			<p>&copy; <?=date('Y')?> <?=o2_e($hp['footer_copyright'])?>. All rights reserved.</p>
			<p>Powered by <a href="https://creativelebanon.com/" target="_blank" rel="noopener">CreativeLebanon</a></p>
		</div>
	</div>
</footer>

<?php if($contact['whatsapp']){ ?>
<a class="wa-float" href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp"><svg class="ic"><use href="#i-whatsapp"/></svg></a>
<?php } ?>
<a class="to-top" id="toTop" href="#home" aria-label="Back to top">
	<svg class="to-top-ring" viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="22"/><circle cx="24" cy="24" r="22" id="topRing"/></svg>
	<svg class="ic"><use href="#i-up"/></svg>
</a>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-labelledby="lbTitle" hidden>
	<button type="button" class="lb-btn lb-close" aria-label="Close"><svg class="ic"><use href="#i-x"/></svg></button>
	<div class="lb-stage">
		<button type="button" class="lb-btn lb-prev" aria-label="Previous"><svg class="ic"><use href="#i-left"/></svg></button>
		<figure class="lb-figure">
			<div class="lb-img"><img id="lbImg" alt=""></div>
			<figcaption class="lb-caption">
				<div>
					<span class="lb-cat" id="lbCat"></span>
					<h3 id="lbTitle"></h3>
				</div>
				<span class="lb-count" id="lbCount"></span>
				<button type="button" class="btn btn-red" id="lbAsk"><span>Ask about this product</span><svg class="ic"><use href="#i-arrow-ur"/></svg></button>
			</figcaption>
		</figure>
		<button type="button" class="lb-btn lb-next" aria-label="Next"><svg class="ic"><use href="#i-right"/></svg></button>
	</div>
</div>
